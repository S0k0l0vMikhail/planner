<?php

namespace App\Controller\Department;

use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Экшен изменения отдела
 *
 * @Route("/api/department", name="edit_department", methods={"PUT"})
 */
class ActEditDepartment extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        DepartmentRepository $departmentRepository,
        UserRepository $userRepository
    ) {
        $departmentData = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$request || $departmentData['name'] == '' || $departmentData['id'] == '' || is_null($user)) {
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        $department = $departmentRepository->find((int)$departmentData['id']);
        $userOrganization = $user->getOrganization();

        if (!$userOrganization->getDepartments()->contains($department)) {
            return new JsonResponse(['result' => 'forbidden'], 403);
        }

        if (isset($departmentData['master_user_id'])) {
            $masterUser = $userRepository->find((int) $departmentData['master_user_id']);

            if ($userOrganization->getUsers()->contains($masterUser) !== true) {
                return new JsonResponse(['result' => 'master user not found'], 404);
            }

            $department->setMasterUser($masterUser);
        }

        $department->setName(trim($departmentData['name']));

        $entityManager->persist($department);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }
}
