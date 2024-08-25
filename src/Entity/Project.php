<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="Developer", inversedBy="projects")
     * @ORM\JoinTable(name="developers_projects")
     */
    private $developers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer;

    public function __construct()
    {
        $this->developers = new ArrayCollection();
    }



    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): self
    {
        if (!$this->developers->contains($developer)) {
            $this->developers[] = $developer;
            $developer->addProject($this);
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): self
    {
        if ($this->developers->removeElement($developer)) {
            $developer->removeProject($this);
        }

        return $this;
    }
}