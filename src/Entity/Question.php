<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $templateQuestion;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'templateQuestions')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $project;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private $creator;

    #[ORM\OneToMany(mappedBy: 'templateQuestion', targetEntity: Answer::class, cascade: ["persist", "remove"])]
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemplateQuestion(): ?string
    {
        return $this->templateQuestion;
    }

    public function setTemplateQuestion(string $templateQuestion): self
    {
        $this->templateQuestion = $templateQuestion;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }


    public function setAnswers($answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setTemplateQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getTemplateQuestion() === $this) {
                $answer->setTemplateQuestion(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'templateQuestion' => $this->templateQuestion,
            'answers' => array_values($this->answers->toArray()),
            'project' => $this->project
        ];
    }
}
