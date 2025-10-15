<?php

namespace ShopFlow\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status = 'backlog';

    #[ORM\Column(type: 'string', length: 20)]
    private string $priority = 'medium';

    #[ORM\Column(type: 'integer')]
    private int $position = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $assigneeId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $storyPoints = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function getAssigneeId(): ?int
    {
        return $this->assigneeId;
    }

    public function setAssigneeId(?int $assigneeId): self
    {
        $this->assigneeId = $assigneeId;
        return $this;
    }

    public function getStoryPoints(): ?int
    {
        return $this->storyPoints;
    }

    public function setStoryPoints(?int $storyPoints): self
    {
        $this->storyPoints = $storyPoints;
        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'position' => $this->position,
            'assignee_id' => $this->assigneeId,
            'story_points' => $this->storyPoints,
            'project_id' => $this->project->getId(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
