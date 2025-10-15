<?php

namespace ShopFlow\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'projects')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status = 'planning';

    #[ORM\Column(type: 'integer')]
    private int $progress = 0;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $clientId = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $deadline = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $gitRepository = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', cascade: ['persist', 'remove'])]
    private Collection $tasks;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_team_members')]
    private Collection $teamMembers;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tasks = new ArrayCollection();
        $this->teamMembers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): self
    {
        $this->progress = $progress;
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

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getDeadline(): ?\DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTime $deadline): self
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function getGitRepository(): ?string
    {
        return $this->gitRepository;
    }

    public function setGitRepository(?string $gitRepository): self
    {
        $this->gitRepository = $gitRepository;
        return $this;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }
        return $this;
    }

    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    public function addTeamMember(User $user): self
    {
        if (!$this->teamMembers->contains($user)) {
            $this->teamMembers[] = $user;
        }
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'status' => $this->status,
            'progress' => $this->progress,
            'description' => $this->description,
            'client_id' => $this->clientId,
            'start_date' => $this->startDate?->format('Y-m-d'),
            'deadline' => $this->deadline?->format('Y-m-d'),
            'git_repository' => $this->gitRepository,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'team_members' => array_map(fn($u) => $u->toArray(), $this->teamMembers->toArray()),
        ];
    }
}
