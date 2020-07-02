<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Обработчик события отправки токена.
 * Добавляет мета-информацию для отрисовки элементов на фронтенде.
 *
 * https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/2-data-customization.md#eventsauthentication_success---adding-public-data-to-the-jwt-response
 *
 * @author Stas Lozitskiy
 */
class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['meta'] = [
            // флаг is_admin для определения прав доступа
            'is_admin' => in_array('ROLE_ADMIN', $user->getRoles()),
            // название организации
            'org_name' => $user->getOrganization()->getName()
        ];

        $event->setData($data);
    }
}
