<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    public const STATE_DRAFT = 'draft';
    public const STATE_REVIEWED = 'reviewed';
    public const STATE_REJECTED = 'rejected';
    public const STATE_PUBLISHED = 'published';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?array $currentPlace;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    // getter/setter methods must exist for property access by the marking store.
    // in other words - current state or place of the graph
    public function getCurrentPlace(): ?array
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace($currentPlace, $context = []): void
    {
        $this->currentPlace = $currentPlace;
    }
}
