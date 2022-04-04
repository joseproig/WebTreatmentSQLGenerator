<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $pathToDbFile;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Question::class, cascade: ['persist'])]
    private $templateQuestions;

    public function __construct()
    {
        $this->templateQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPathToDbFile(): ?string
    {
        return $this->pathToDbFile;
    }

    public function setPathToDbFile(string $pathToDbFile): self
    {
        $this->pathToDbFile = $pathToDbFile;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getTemplateQuestions(): Collection
    {
        return $this->templateQuestions;
    }

    public function setTemplateQuestions(Collection $collection): self
    {
        $this->templateQuestions = $collection;

        return $this;
    }

    public function addTemplateQuestion(Question $templateQuestion): self
    {
        if (!$this->templateQuestions->contains($templateQuestion)) {
            $this->templateQuestions[] = $templateQuestion;
            $templateQuestion->setProject($this);
        }

        return $this;
    }

    public function removeTemplateQuestion(Question $templateQuestion): self
    {
        if ($this->templateQuestions->removeElement($templateQuestion)) {
            // set the owning side to null (unless already changed)
            if ($templateQuestion->getProject() === $this) {
                $templateQuestion->setProject(null);
            }
        }

        return $this;
    }
}
