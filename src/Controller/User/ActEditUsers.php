<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Экшен редактирования пользователей
 *
 * @Route("/api/users", name="edit_user", methods={"PUT"})
 */
class ActEditUsers extends AbstractController
{
    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        UserRepository $userRepository
    ) {
        $userData = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $anyFieldsSet = (isset($userData['name']) || isset($userData['email']) || isset($userData['password']));

        if ($userData['id'] == '' || is_null($user) || !$anyFieldsSet) {
            return new JsonResponse(['result' => 'failed adding'], 400);
        }

        $userToChange = $userRepository->find((int)$userData['id']);

        $userOrganization = $user->getOrganization();

        if (!$userOrganization->getUsers()->contains($userToChange)) {
            return new JsonResponse(['result' => 'forbidden'], 403);
        }

        if (isset($userData['name'])) {
            $userToChange->setUsername(trim($userData['name']));
        }

        if (isset($userData['email'])) {
            if ($userRepository->findOneBy(['email' => $userData['email']]) != null) {

                return new JsonResponse(['result' => 'email already taken'], 400);
            }

            if (filter_var($userData['email'], FILTER_VALIDATE_EMAIL) === false) {
                return new JsonResponse(['result' => 'invalid email'], 400);
            }

            $userToChange->setEmail(trim($userData['email']));
        }

        if (isset($userData['password'])) {
            $password = $encoder->encodePassword($userToChange, (string) $userData['password']);
            $userToChange->setPassword($password);
        }

        $entityManager->persist($userToChange);
        $entityManager->flush();

        return new JsonResponse(['result' => 'success'], 200);
    }
}
