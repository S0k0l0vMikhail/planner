<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\TableVacation;
use App\Entity\User;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Контроллер пользователей
 */
class UsersController extends AbstractController
{
    /**
     * Список пользователей организации
     * @Route("/api/users", name="users", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
        $user = $this->getUser();

        if (is_null($user)){
            return $this->json(['result' => 'null']);
        }

        $userOrganizationId = $user->getOrganizationId();
        $staff = $userRepository->findStaffByOrganizationId($userOrganizationId);

        $data = [];
        foreach ($staff as $user) {
            $departments = [];
            foreach ($user->getDepartments() as $department) {
                $departments[] = [
                    'name' => $department->getName()
                ];
            }

            $data[] = [
                'id' => $user->getId(),
                'name' => $user->getUserName(),
                'date' => $user->getCreatedAt(),
                'email' => $user->getEmail(),
                'departments' => $departments
            ];
        }

        return $this->json($data);
    }

    /**
     * Список пользователей запрашиваемого отдела
     * @Route("/api/colleagues/{id}", name="colleagues", methods={"GET"})
     */
    public function colleagues(Department $department)
    {
        $user = $this->getUser();

        if (is_null($user)){
            return new JsonResponse(['result' => 'null'], 404);
        }

        if($user->getOrganization()->getDepartments()->contains($department)) {
            $data = [];
            foreach($department->getUsers() as $colleague) {
                $data[$colleague->getId()] = $colleague->getUsername();
            }
        } else {
            return new JsonResponse(['result' => 'error'], 403);
        }

        return new JsonResponse($data, 200);
    }

    /**
     * Создание пользователя
     * @Route("/api/user", name="user", methods={"POST"})
     */
    public function addUser(Request $request,
                            EntityManagerInterface $entityManager,
                            DepartmentRepository $departmentRepository,
                            UserPasswordEncoderInterface $encoder,
                            UserRepository $userRepository)
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!$request ||
            !$data['name'] ||
            !$data['email'] ||
            is_null($user)
        ){
            return new JsonResponse(['result' => 'failed adding'], 401);
        }

        if ($userRepository->findOneBy(['email' => $data['email']]) != null) {
            return new JsonResponse(['result' => 'email already taken'], 402);
        }

        $newUser = new User();
        $newUser->setUsername(trim($data['name']));
        $newUser->setEmail(trim($data['email']));
        $password = $encoder->encodePassword($newUser, $this->password());
        $newUser->setPassword($password);
        $newUser->setOrganization($user->getOrganization());

        if (isset($data['department_id']) &&
            $user->getOrganization()->getDepartments()->contains($departmentRepository->find((int)$data['department_id']))
        ) {
            $newUser->addDepartment($departmentRepository->find((int)$data['department_id']));
        }

        $entityManager->persist($newUser);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }

    /**
     * Создание первого пользователя и организации для него
     * @Route("/api/user/register", name="first_user", methods={"POST"})
     */
    public function createFirstUser(Request $request,
                            EntityManagerInterface $entityManager,
                            UserPasswordEncoderInterface $encoder,
                            UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true);

        if (!$request ||
            !$data['name'] ||
            !$data['password'] ||
            !$data['email'] ||
            !$data['organization_name']
        ){
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        if ($userRepository->findOneBy(['email' => $data['email']]) != null) {
            return new JsonResponse(['result' => 'email already taken'], 400);
        }

        $user = new User();
        $user->setUsername(trim($data['name']));
        $user->setEmail(trim($data['email']));
        $password = $encoder->encodePassword($user, (string) $data['password']);
        $user->setPassword($password);
        $organization = new Organization();
        $organization->setName(trim($data['organization_name']));
        $user->setOrganization($organization);

        // Первый пользователь организации - по-умолчанию админ
        $user->setRoles(['ROLE_ADMIN']);

        $tableVacation = new TableVacation();
        $tableVacation->setName("График отпусков");
        $tableVacation->setYear(date('Y'));
        $tableVacation->setUser($user);

        $entityManager->persist($user);
        $entityManager->persist($tableVacation);
        $entityManager->persist($organization);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }

    /**
     * Метод генерации пароля
     *
     * @param int $length
     * @return string
     * @throws \Exception
     */
    private function password($length = 8)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, $max)];
        }

        return $str;
    }
}
