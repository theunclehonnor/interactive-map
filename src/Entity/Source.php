<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SourceRepository::class)
 */
class Source
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="source")
     */
    private $report;

    public function __construct()
    {
        $this->report = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReport(): Collection
    {
        return $this->report;
    }

    public function addReport(Report $report): self
    {
        if (!$this->report->contains($report)) {
            $this->report[] = $report;
            $report->setSource($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getSource() === $this) {
                $report->setSource(null);
            }
        }

        return $this;
    }
}
