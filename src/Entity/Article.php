<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column()]
  private ?int $id = null;

  #[ORM\ManyToMany(targetEntity: "App\Entity\Category", inversedBy: "articles")]
  private $categories;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $picture ;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $content = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $publicationDate = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $lastUpdateDate = null;

  #[ORM\Column]
  private ?bool $isPublished = null;

  public function __construct()
  {
    $this->categories = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getPicture(): ?string
  {
    return $this->picture;
  }

  public function setPicture(string $picture): self
  {
    $this->picture = $picture;

    return $this;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(?string $content): self
  {
    $this->content = $content;

    return $this;
  }

  public function getPublicationDate(): ?\DateTimeInterface
  {
    return $this->publicationDate;
  }

  public function setPublicationDate(?\DateTimeInterface $publicationDate): self
  {
    $this->publicationDate = $publicationDate;

    return $this;
  }

  public function getLastUpdateDate(): ?\DateTimeInterface
  {
    return $this->lastUpdateDate;
  }

  public function setLastUpdateDate(\DateTimeInterface $lastUpdateDate): self
  {
    $this->lastUpdateDate = $lastUpdateDate;

    return $this;
  }

  public function getIsPublished(): ?bool
  {
    return $this->isPublished;
  }

  public function setIsPublished(bool $isPublished): self
  {
    $this->isPublished = $isPublished;

    return $this;
  }

  /**
   * @return Collection<int, Category>
   */
  public function getCategories(): Collection
  {
    return $this->categories;
  }

  public function addCategory(Category $category): self
  {
    if (!$this->categories->contains($category)) {
      $this->categories[] = $category;
    }

    return $this;
  }

  public function removeCategory(Category $category): self
  {
    $this->categories->removeElement($category);

    return $this;
  }
}
