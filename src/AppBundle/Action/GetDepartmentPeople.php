<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16.08.17
 * Time: 17:15
 */

namespace AppBundle\Action;

use AppBundle\Entity\User;
use AppBundle\Entity\Mood;
use AppBundle\Entity\Department;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

class GetDepartmentPeople
{

    private $requestStack;
    private $em;

    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->em           = $em;
    }

    /**
     * @param $data
     * @return JsonResponse
     * @Route(
     *     name="get_departmentmoods",
     *     path="/api/v1/departments/{id}/moods",
     *     defaults={"_api_resource_class"=Department::class, "_api_collection_operation_name"="moods"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
    {
        $request = $this->requestStack->getCurrentRequest();
        $departmentId = $request->get("id");
        $peopleArr = $this->em->getRepository(User::class)->findBy(array('department' => $departmentId));

        $moodArr = $this->em->getRepository(Mood::class)->findBy(array('user' => $peopleArr));
        $arr = array();
        foreach($moodArr AS $mood){
            $result = array("id" => $mood->getId(),"mood" => $mood->getMood(), "Explanation" => $mood->getExplanation(), "Date" => $mood->getPostedAt());
            array_push($arr,$result);
        }

        $response = new JsonResponse(json_encode($arr));

        return $response;
    }
}
