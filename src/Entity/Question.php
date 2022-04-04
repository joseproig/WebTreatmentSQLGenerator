<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
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
}
