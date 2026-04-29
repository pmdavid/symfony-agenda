<?php


namespace App\Infrastructure\Controller;

use App\Domain\CommonArea;
use App\Application\UseCase\GetAvailabilityUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AvailabilityController extends AbstractController
{
    public function __construct(private GetAvailabilityUseCase $getAvailability) {}

    #[Route('/api/availability', name: 'api_availability', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $commonAreaId = (int) $request->query-> get('commonArea');
        $date         = new \DateTimeImmutable($request->query->get('date', 'now'));
        $commonArea   = new CommonArea($commonAreaId);

        $slots = $this->getAvailability->execute($commonArea, $date);

        return $this->json(['slots' => $slots]);
    }
}

