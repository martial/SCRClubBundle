<?php

namespace scrclub\SCRClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EPAnimation
 *
 * @ORM\Table()
 * @ORM\Entity
 */

class KiblindAnimation
{

    public function __construct() {

    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="speed", type="float", nullable=true)
     */
    private $speed;

    /**
     * @var string
     * @ORM\Column(name="data", type="text", nullable=true)
     *
     */
    private $data;

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $speed
     */
    public function setSpeed($speed) {
        $this->speed = $speed;
    }

    /**
     * @return string
     */
    public function getSpeed() {
        return $this->speed;
    }


    public function __toString()
    {
        return $this->getName();
    }


}
