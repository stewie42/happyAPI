<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 11.08.17
 * Time: 23:56
 */

namespace AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        $data['data'] = array(
            'userId' => $user->getId(),
            'name' => $user->getUsername()
        );

        $event->setData($data);
    }
}
