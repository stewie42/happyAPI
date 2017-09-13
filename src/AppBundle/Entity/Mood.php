<?php
/**
 * User: steven
 */

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * A mood.
 *
 * @ApiResource(itemOperations={"get"={"method"="GET"},
 *                              "delete"={"method"="DELETE"},
 *                              "put"={"method"="PUT"},
 *                              "last"={"route_name"="get_last"}
 *               })
 * @ORM\Entity
 */
class Mood
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1,max=5)
     * @ORM\Column(type="smallint")
     *
     */
    private $mood;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $explanation;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="posted_at",type="datetime", options={"default": 0})
     */
    private $postedAt;

    /**
     * @var User, die die Mood abgibt
     *
     * @Assert\NotNull
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="moods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mood
     *
     * @param int $mood
     *
     * @return $this
     */
    public function setMood($mood)
    {
        $this->mood = $mood;

        return $this;
    }

    /**
     * Gets mood
     *
     * @return int
     */
    public function getMood()
    {
        return $this->mood;
    }

    /**
     * Set explanation
     *
     * @param string $explanation
     *
     * @return Mood
     */
    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;

        return $this;
    }

    /**
     * Get explanation
     *
     * @return string
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * Set postedAt
     *
     * @param \DateTime $postedAt
     *
     * @return Mood
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user= $user;
    }

    public function __construct()
    {
        $this->postedAt = new DateTime();
    }

}
