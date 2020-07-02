<?php

namespace App\Controller\TableVacation;

use App\Repository\TableVacationRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Экшен получения графиков отпусков
 *
 * @Route("/api/tablevacations", name="tablevacations", methods={"GET"})
 */
class ActGetTableVacations extends AbstractController
{
    public function __invoke(TableVacationRepository $tableVacationRepository, UserRepository $userRepository)
    {
        $user = $this->getUser();

        if (is_null($user)) {
            return new JsonResponse(['result' => 'failed'], 400);
        }

        $tableVacations = $tableVacationRepository->findBy(['deleted' => false]);

        $data = [];
        foreach ($tableVacations as $tableVacation) {

            $date = new DateTime($tableVacation->getUpdatedAt());
            $currentDate = $date->format('d M Y') == date('d M Y') ? 'Сегодня' : $date->format('d M Y');

            $data[] = [
                'id' => $tableVacation->getId(),
                'user_id' => $tableVacation->getUser()->getId(),
                'name' => $tableVacation->getName(),
                'date' => $tableVacation->getCreatedAt(),
                'year' => $tableVacation->getYear(),
                'approved_at' => $tableVacation->getApprovedAt() != null ? 'Утвержден' : 'Черновик',
                'updated_at' => $currentDate
            ];
        }

        return $this->json($data);
    }
}
