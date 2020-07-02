<?php

namespace App\Controller;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контроллер отделов
 */
class DepartmentsController extends AbstractController
{
    /**
     * Список отделов
     * @Route("/api/departments", name="departments", methods={"GET"})
     */
    public function getDepartments()
    {
        $user = $this->getUser();
        $userOrganizationId = $user->getOrganization()->getId();
        $department = $this->getDoctrine()
            ->getRepository(Department::class)
            ->findByOrganizationId($userOrganizationId);

        if (is_null($department)) {
            return $this->json(['result' => 'null']);
        }

        foreach ($department as $value) {
            $data[] = [
                'id' => $value->getId(),
                'name' => $value->getName(),
                'users_count' => 2, // TODO
                'date' => $value->getCreatedAt()
            ];
        }

        return $this->json($data);
    }

    /**
     * Добавление отдела
     * @Route("/api/departments", name="add_department", methods={"POST"})
     */
    public function addDepartment(Request $request,
                                  EntityManagerInterface $entityManager,
                                  DepartmentRepository $departmentRepository)
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!$request || !$data['name'] || is_null($user)) {
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        if ($departmentRepository->findOneBy(['name' => $data['name']]) !== null) {
            return new JsonResponse(['result' => 'name already taken'], 400);
        }

        $userOrganization = $user->getOrganization();

        $department = new Department();
        $department->setName($data['name']);
        $department->setOrganization($userOrganization);

        $entityManager->persist($department);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }

    /**
     * Добавление существующих пользователей в отдел
     *
     * @Route("/api/department/{id}/addusers", name="add_addusers", methods={"PATCH"})
     */
    public function addUsers(
        Request $request,
        EntityManagerInterface $entityManager,
        Department $department,
        UserRepository $userRepository
    ) {
        $usersId = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$request || $usersId['users_id'] == [] || is_null($user) || is_null($department)) {
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        $userOrganization = $user->getOrganization();
        $userCollection = $userOrganization->getUsers();

        if (!$userOrganization->getDepartments()->contains($department)) {
            return new JsonResponse(['result' => 'forbidden'], 403);
        }

        foreach ($usersId['users_id'] as $userId) {
            $userToAdd = $userRepository->find((int)$userId);
            if ($userCollection->contains($userToAdd)) {
                $department->addUser($userToAdd);
            }
        }

        $entityManager->persist($department);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }

    /**
     * Удаление существующего отдела
     *
     * @Route("/api/department/{id}", name="remove_department", methods={"DELETE"})
     */
    public function removeDepartment(Request $request, EntityManagerInterface $entityManager, Department $department)
    {
        $user = $this->getUser();

        if (!$request || is_null($user)) {
            return new JsonResponse(['result' => 'failed removing'], 400);
        }

        $userOrganization = $user->getOrganization();

        if (!$userOrganization->getDepartments()->contains($department)) {
            return new JsonResponse(['result' => 'not found'], 404);
        }

        $entityManager->remove($department);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }

    /**
     * Удаление существующих пользователей в отдел
     *
     * @Route("/api/department/{id}/removeusers", name="remove_users", methods={"PATCH"})
     */
    public function removeUsers(
        Request $request,
        EntityManagerInterface $entityManager,
        Department $department,
        UserRepository $userRepository
    ) {
        $usersId = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$request || $usersId['users_id'] == [] || is_null($user) || is_null($department)) {
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        $userOrganization = $user->getOrganization();
        $userCollection = $userOrganization->getUsers();

        if (!$userOrganization->getDepartments()->contains($department)) {
            return new JsonResponse(['result' => 'forbidden'], 403);
        }

        foreach ($usersId['users_id'] as $userId) {
            $userToRemove = $userRepository->find((int)$userId);
            if ($userCollection->contains($userToRemove) && $department->getUsers()->contains($userToRemove)) {
                $department->removeUser($userToRemove);
            }
        }

        $entityManager->persist($department);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }
}
