<?php

namespace App\Controller\Organization;

use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Экшен редактирования организации
 *
 * @Route("/api/organization", name="edit_organization", methods={"PUT"})
 */
class ActEditOrganization extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        DepartmentRepository $departmentRepository,
        UserRepository $userRepository
    ) {
        $requestData = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$request || empty($requestData['name']) || is_null($user)) {
            return new JsonResponse(['result' => 'ERR_COMMON'], 400);
        }

        $organization = $user->getOrganization();
        $organization->setName($requestData['name']);

        $entityManager->persist($organization);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }
}
