<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @author Steven von Roden <s.roden@mailbox.tu-dresden.de>
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ApiResource(iri="http://schema.org/Person",
 *     attributes={ "normalization_context"={"groups"={"user", "user-read"}},
 *                  "denormalization_context"={"groups"={"user", "user-write"}}},
 *              itemOperations={
 *                  "get"={"method"="GET"},
 *                  "special"={"route_name"="get_last"}
 *           })
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"user"})
     * @Assert\Email
     * @ApiProperty(iri="http://schema.org/email")
     */
    protected $email;

    /**
     * @Groups({"user"})
     */
    protected $username;

    /**
     * @Groups({"user-write"})
     */
    protected $plainPassword;

    /**
     * @var string Family name. In the U.S., the last name of an User. This can be used along with givenName instead of the name property
     *
     * @Groups({"user"})
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/familyName")
     */
    private $familyName;

    /**
     * @var string Given name. In the U.S., the first name of a User. This can be used along with familyName instead of the name property
     *
     * @Groups({"user"})
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/givenName")
     */
    private $givenName;

    /**
     * @var string Gender of the person. While http://schema.org/Male and http://schema.org/Female may be used, text strings are also acceptable for people who do not identify as a binary gender
     *
     * @Groups({"user"})
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/gender")
     */
    private $gender;

    /**
     * @var \DateTime Date of birth
     *
     * @Groups({"user"})
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     * @ApiProperty(iri="http://schema.org/birthDate")
     */
    private $birthDate;

    /**
     * @var string The telephone number
     *
     * @Groups({"user"})
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/telephone")
     */
    private $telephone;

    /**
     * @var string The job title of the person (for example, Financial Manager)
     *
     * @Groups({"user"})
     * @ORM\Column(nullable=true)
     * @Assert\Type(type="string")
     * @ApiProperty(iri="http://schema.org/jobTitle")
     */
    private $jobTitle;

    /**
     * @var Abteilung in welcher der Mitarbeiter arbeitet
     *
     * @Groups({"user"})
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department")
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity="Mood", mappedBy="user")
     */
    private $moods;



    private $role;

    /**
     * Sets id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets familyName.
     *
     * @param string $familyName
     *
     * @return $this
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Gets familyName.
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Sets givenName.
     *
     * @param string $givenName
     *
     * @return $this
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * Gets givenName.
     *
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * Sets gender.
     *
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Gets gender.
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets birthDate.
     *
     * @param \DateTime $birthDate
     *
     * @return $this
     */
    public function setBirthDate(\DateTime $birthDate = null)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Gets birthDate.
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Sets telephone.
     *
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Gets telephone.
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Sets email.
     *
     * @param string $email
     *
     * @return $this
     */
    /*public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }*/

    /**
     * Gets email.
     *
     * @return string
     */
    /*public function getEmail()
    {
        return $this->email;
    }*/

    /**
     * Sets jobTitle.
     *
     * @param string $jobTitle
     *
     * @return $this
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Gets jobTitle.
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
        $this->roles = array("ROLE_USER");
    }
}

