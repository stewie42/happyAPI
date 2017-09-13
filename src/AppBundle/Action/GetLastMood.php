<?php
/**
 * User: steven
 */

namespace AppBundle\Action;

use AppBundle\Entity\User;
use AppBundle\Entity\Mood;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

class GetLastMood
{
    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    /**
     * @param $request
     * @return JsonResponse
     *
     * @Route(
     *     name="get_last",
     *     path="/api/v1/users/{id}/last",
     *     defaults={"_api_resource_class"=User::class, "_api_collection_operation_name"="last"}
     * )
     * @Method("GET")
     */
    public function __invoke(Request $request)
    {
        //$request = $this->requestStack->getCurrentRequest();
        $userId = $request->get("id");

        $moodArr = $this->em->getRepository(Mood::class)->findBy(array('user' => $userId), array('id' => 'DESC'), 1);

        if(count($moodArr) == 0) {
            $response = new JsonResponse(array('response' => 'Keine Bewertung gefunden!'));
            $response->setStatusCode(404);
        }

        else {
            $mood = $moodArr[0];
            $response = new JsonResponse(array('id' => $mood->getId(),
                'explanation' => $mood->getExplanation(),
                'mood' => $mood->getMood(),
                'postedAt' => $mood->getPostedAt()));
        }

        return $response;
    }
}