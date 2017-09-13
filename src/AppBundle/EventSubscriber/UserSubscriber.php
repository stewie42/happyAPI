<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 01.08.17
 * Time: 21:28
 */

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Mood;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


final class UserSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['checkUser', EventPriorities::PRE_RESPOND]]
        ];

    }

    public function checkUser(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $mood   = $event->getControllerResult();
        /*$method = $

        if (!$mood instanceof Mood) {
            return;
        }

        $lastMood = $this->entityManager->getRepository('AppBundle:Mood')->findBy(
            array('user' => $mood->getUser()),
            array('id' => 'DESC'),
            1);

        if(!$lastMood)
            return;

        $lastDate = $lastMood[0]->getPostedAt()->format('d.m.Y');

        $response = new JsonResponse();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        if($method == Request::METHOD_POST && $lastDate == date('d.m.Y',$_SERVER['REQUEST_TIME'])){
            $response->setStatusCode(400);
            $response->setData(array(
                'response' => 'Nur 1x Eintrag am Tag möglich',
                'posts' => $serializer->serialize($mood,'json'),
                'lastId' => $lastMood[0]->getId(),
                'ok' => false
            ));

            $response->send();
            $event->stopPropagation();
        }

        if($method == Request::METHOD_DELETE && $lastDate != date('d.m.Y',$_SERVER['REQUEST_TIME'])){
            $response->setStatusCode(400);
            $response->setData(array(
                'response' => 'Entfernen nicht mehr möglich',
                'posts' => $serializer->serialize($mood,'json'),
                'ok' => false
            ));
            $response->send();
            $event->stopPropagation();
        }*/

    }
}
