<?php

namespace App\EventListener;

use App\Entity\Developer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        /** @var Developer $user */
        $user = $event->getUser();

        $event->setData([
            'token' => $data['token'],
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'roles' => $user->getRoles(),
                'profileImage' => $user->getProfileImage(),
                'amountOfNotifications' => $user->getAmountOfReceivedNotifications()
            ]
        ]);
    }
}
