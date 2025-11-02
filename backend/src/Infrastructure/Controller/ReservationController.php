<?php

namespace App\Infrastructure\Controller;

use App\Domain\CommonArea;
use App\Application\UseCase\ReservationUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    public function __construct(private ReservationUseCase $reservationUseCase) {}

    #[Route('/api/reserve', name: 'api_reserve', methods: ['POST','OPTIONS'])]
    public function reserve(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $commonArea = new CommonArea((int)$data['commonArea']);
            $date = new \DateTimeImmutable($data['date']);
            $hour = (int)$data['hour'];

            // Llamamos al UseCase
            $success = $this->reservationUseCase->execute($commonArea, $date, $hour);

            return $this->json(['success' => $success]);
        }
        catch (\JsonException $e) {
            throw new BadRequestHttpException('Invalid JSON body');
        }
        // Aqui puededn ir otros catchs para manejar excepciones custom si es necesario. Ejemplo: ReservationAlreadyExistsException
    }
}
