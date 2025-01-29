<?php

namespace App\Controller;

use App\Entity\CarModels;
use App\Entity\Cars;
use App\Repository\CarsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/cars')]
final class CarsController extends AbstractController
{
    #[Route(name: 'app_cars_index', methods: ['GET'])]
    public function index(CarsRepository $carsRepository): Response
    {
        return $this->render('cars/index.html.twig', [
            'cars' => $carsRepository->findAll(),
        ]);
    }

    #[Route('/car/add', name: 'car_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['brand']) || empty($data['model']) || empty($data['description'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'All fields are required'], 400);
        }

        $car = new Cars();
        $car->setBrand($data['brand']);

        $carModel = new CarModels();
        $carModel->setModel($data['model']);
        $carModel->setDescription($data['description']);
        $car->addCarModel($carModel);

        $entityManager->persist($car);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Car added successfully']);
    }

    #[Route('/car/update', name: 'car_update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['id']) || empty($data['brand']) || empty($data['model']) || empty($data['description'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'All fields are required'], 400);
        }

        $car = $entityManager->getRepository(Cars::class)->find($data['id']);

        if (!$car) {
            return new JsonResponse(['status' => 'error', 'message' => 'Car not found'], 404);
        }

        // Обновление данных
        $car->setBrand($data['brand']);

        // Обновление модели и описания
        foreach ($car->getCarModels() as $carModel) {
            $carModel->setModel($data['model']);
            $carModel->setDescription($data['description']);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Car updated successfully']);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, Cars $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
