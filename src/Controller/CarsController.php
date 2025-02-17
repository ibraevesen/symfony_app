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

    #[Route('/{id}', name: 'app_cars_show', methods: ['GET'])]
    public function show(CarModels $car): Response
    {
        return $this->render('cars/info.html.twig', [
            'model' => $car,
        ]);
    }

    #[Route('/car/addModel', name: 'car_addModel', methods: ['POST'])]
    public function addModel(Request $request, EntityManagerInterface $entityManager, CarsRepository $carsRepository): JsonResponse
    {
        $data = $request->request->all();
        $file = $request->files->get('photo');

        if (empty($data['brand_id']) || empty($data['model']) || empty($data['description'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'All fields are required'], 400);
        }

        // Получаем бренд из базы
        $car = $carsRepository->find($data['brand_id']);
        if (!$car) {
            return new JsonResponse(['status' => 'error', 'message' => 'Brand not found'], 404);
        }

        // Создаем новую модель
        $carModel = new CarModels();
        $carModel->setCar($car);
        $carModel->setModel($data['model']);
        $carModel->setDescription($data['description']);

        if ($file) {
            $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $newFilename = uniqid() . '.' . $file->guessExtension();
            $file->move($uploadsDir, $newFilename);
            $carModel->setPhoto('/uploads/' . $newFilename);
        }

        $entityManager->persist($carModel);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Model added successfully']);
    }

    #[Route('/car/addBrand', name: 'car_addBrand', methods: ['POST'])]
    public function addBrand(Request $request, EntityManagerInterface $entityManager, CarsRepository $carsRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['brand'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'All fields are required'], 400);
        }

        $existingBrand = $carsRepository->findOneBy(['brand' => $data['brand']]);
        if ($existingBrand) {
            return new JsonResponse(['status' => 'error', 'message' => 'Brand already exists'], 409);
        }

        $cars = new Cars();
        $cars->setBrand($data['brand']);

        $entityManager->persist($cars);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Brand added successfully']);
    }

    #[Route('/car/update', name: 'car_update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->request->all();
        $file = $request->files->get('photo');

        if (empty($data['id']) || empty($data['model']) || empty($data['description']) || empty($data['price'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'All fields are required'], 400);
        }

        $carModel = $entityManager->getRepository(CarModels::class)->find($data['id']);

        if (!$carModel) {
            return new JsonResponse(['status' => 'error', 'message' => 'Car not found'], 404);
        }

        // Обновление модели и описания
        $carModel->setModel($data['model']);
        $carModel->setModelPrice($data['price']);
        $carModel->setDescription($data['description']);

        if ($file) {
            $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $newFilename = uniqid() . '.' . $file->guessExtension();
            $file->move($uploadsDir, $newFilename);
            $carModel->setPhoto('/uploads/' . $newFilename);
        }

        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Car updated successfully',
            'updatedData' => [
                'id' => $carModel->getId(),
                'model' => $carModel->getModel(),
                'price' => $carModel->getModelPrice(),
                'description' => $carModel->getDescription(),
                'photo' => $carModel->getPhoto(),
            ]
        ]);
    }

    #[Route('/{id}', name: 'app_cars_delete', methods: ['POST'])]
    public function delete(Request $request, CarModels $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cars_index', [], Response::HTTP_SEE_OTHER);
    }
}
