<?php


namespace App\Controller\Api;

use App\Entity\CarModels;
use App\Entity\BuyHistory;
use Doctrine\ORM\EntityManagerInterface;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayPalController extends AbstractController
{
    private $client;
    private $entityManager;

    public function __construct($clientId, $clientSecret, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->client = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init($clientId, $clientSecret)
            )
            ->environment(Environment::SANDBOX) // или Environment::LIVE для продакшена
            ->build();
    }

    /**
     * @Route("/api/orders", name="create_order", methods={"POST"})
     */
    #[Route('/api/orders', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Ожидаем, что в теле запроса передаётся идентификатор модели автомобиля
        if (!isset($data['model_id'])) {
            return new JsonResponse(['error' => 'Missing model_id'], Response::HTTP_BAD_REQUEST);
        }
        $modelId = $data['model_id'];

        // Получаем модель автомобиля из базы данных
        $carModel = $this->entityManager->getRepository(CarModels::class)->find($modelId);
        if (!$carModel) {
            return new JsonResponse(['error' => 'Car model not found'], Response::HTTP_NOT_FOUND);
        }

        // Получаем стоимость модели (убедитесь, что поле modelPrice определено в сущности CarModels)
        $amount = $carModel->getModelPrice();
        if (!$amount) {
            return new JsonResponse(['error' => 'Car model price not set'], Response::HTTP_BAD_REQUEST);
        }

        // Формируем запрос на создание заказа в PayPal
        $orderBody = [
            "body" => OrderRequestBuilder::init("CAPTURE", [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init("USD", (string)$amount)->build()
                )->build(),
            ])->build(),
        ];

        try {
            $apiResponse = $this->client->getOrdersController()->ordersCreate($orderBody);
            $responseBody = json_decode($apiResponse->getBody(), true);
            return new JsonResponse($responseBody);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/orders/{orderId}/capture", name="capture_order", methods={"POST"})
     */
    #[Route('/api/orders/{orderId}/capture', name: 'capture_order', methods: ['POST'])]
    public function captureOrder(string $orderId, Request $request): JsonResponse
    {
        // Формируем запрос на захват заказа
        $captureBody = [
            "id" => $orderId,
        ];

        try {
            $apiResponse = $this->client->getOrdersController()->ordersCapture($captureBody);
            $responseBody = json_decode($apiResponse->getBody(), true);

            // Извлекаем данные транзакции (предполагается, что используется CAPTURE, поэтому данные в payments->captures)
            $captureData = $responseBody['purchase_units'][0]['payments']['captures'][0] ?? null;
            if (!$captureData) {
                return new JsonResponse(['error' => 'No capture data found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $transactionId = $captureData['id'] ?? null;
            $status = $captureData['status'] ?? null;
            $amountPaid = $captureData['amount']['value'] ?? null;

            if ($status !== 'COMPLETED') {
                return new JsonResponse(['error' => 'Transaction not completed'], Response::HTTP_BAD_REQUEST);
            }

            // Чтобы знать, к какой модели относится заказ, передаём model_id вместе с запросом
            $data = json_decode($request->getContent(), true);
            if (!isset($data['model_id'])) {
                return new JsonResponse(['error' => 'Missing model_id'], Response::HTTP_BAD_REQUEST);
            }
            $modelId = $data['model_id'];
            $carModel = $this->entityManager->getRepository(CarModels::class)->find($modelId);
            if (!$carModel) {
                return new JsonResponse(['error' => 'Car model not found'], Response::HTTP_NOT_FOUND);
            }

            // Сохраняем историю покупки в базе данных
            $buyHistory = new BuyHistory();
            $buyHistory->setAmountPaid($amountPaid);
            $buyHistory->setPaypalTransactionId($transactionId);
            $buyHistory->setDatetimePaid(new \DateTime());
            $buyHistory->setModelId($carModel);

            $this->entityManager->persist($buyHistory);
            $this->entityManager->flush();

            return new JsonResponse($responseBody);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
