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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


final class MoodSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['checkUser', EventPriorities::PRE_DESERIALIZE]],
            KernelEvents::VIEW => [['checkPOST', EventPriorities::PRE_WRITE]]
        ];
    }

    public function checkUser(GetResponseEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $token  = $this->tokenStorage->getToken();
        // Zugriff auf moods-Listenressource nur erlauben, wenn der Nutzer ein Vorgesetzter ist
        if($method == 'GET' && preg_match( '/(moods)$/', $event->getRequest()->getPathInfo()) != 0 && !is_null($token)) {
            $user = $token->getUser();
            if ($user == 'anon.' || !$user->hasRole('ROLE_CHEF')){
                $body = array(
                   'response' => 'nicht erlaubt!',
                    'ok' => false
                );
                $this->denyAccess($event,$body, 403);
            }
        }

        // Zugriff auf moods-Ressourceninstanz nur erlauben, wenn der Nutzer sie selber erstellt hat oder
        // wenn der Nutzer ein Vorgesetzter ist

        if($method == 'GET' && preg_match( '/moods\/+\d/', $event->getRequest()->getRequestUri())){
            $kernel = $event->getKernel();
            $moodId = $event->getRequest()->attributes->get('id');
            if(is_null($moodId))
                return;

            if(!is_null($token) && $token->getUser() != 'anon.') {
                $user = $token->getUser();
                $mood       = $this->entityManager->getRepository('AppBundle:Mood')->find($moodId);
                $moodUser   = $mood->getUser()->getId();

                if($moodUser == $user->getId() || $user->hasRole('ROLE_CHEF'))
                    return;
            }

            $body = array(
                'response' => 'Zugriff nicht erlaubt!',
                'ok' => false
            );

            $this->denyAccess($event, $body, 403);
        }

        return;
  }

    public function checkPOST(GetResponseForControllerResultEvent $event)
    {
        $mood   = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

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

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // Überprüfung, ob an diesem Tag vom Nutzer bereits eine Bewertung abgegeben wurde
        if($method == Request::METHOD_POST && $lastDate == date('d.m.Y',$_SERVER['REQUEST_TIME'])){
            $body = array(
                'response' => 'Nur 1x Eintrag am Tag möglich',
                'posts' => $serializer->serialize($mood,'json'),
                'lastId' => $lastMood[0]->getId(),
                'ok' => false
            );

            $this->denyAccess($event, $body, 403);
        }

        // Überprüfung, ob die zu löschende Bewertung älter als ein Tag ist
        if($method == Request::METHOD_DELETE && $lastDate != date('d.m.Y',$_SERVER['REQUEST_TIME'])){
            $body = array(
                'response' => 'Entfernen nicht mehr möglich',
                'posts' => $serializer->serialize($mood,'json'),
                'ok' => false
            );

            $this->denyAccess($event, $body, 403);
        }
    }

    public function denyAccess($event, $body, $statusCode){
        $response = new JsonResponse();
        $response->setStatusCode($statusCode);
        $response->setData($body);
        $response->send();

        $event->stopPropagation();
    }
}
