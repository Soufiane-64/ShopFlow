<?php

namespace ShopFlow\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'releases')]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $version;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status = 'planned';

    #[ORM\Column(type: 'integer')]
    private int $projectId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $gitTag = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $branch = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $deployedAt = null;

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

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
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

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function getGitTag(): ?string
    {
        return $this->gitTag;
    }

    public function setGitTag(?string $gitTag): self
    {
        $this->gitTag = $gitTag;
        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(?string $branch): self
    {
        $this->branch = $branch;
        return $this;
    }

    public function getDeployedAt(): ?\DateTime
    {
        return $this->deployedAt;
    }

    public function setDeployedAt(?\DateTime $deployedAt): self
    {
        $this->deployedAt = $deployedAt;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'name' => $this->name,
            'notes' => $this->notes,
            'status' => $this->status,
            'project_id' => $this->projectId,
            'git_tag' => $this->gitTag,
            'branch' => $this->branch,
            'deployed_at' => $this->deployedAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
