<?php

/**
 * Book Entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Book.
 *
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\Table(name="books")
 *
 * @UniqueEntity(fields={"title"})
 */
class Book
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     */
    private $title;

    /**
     * Pages.
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     */
    private $pages;

    /**
     * Publish date.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $publishDate;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class,
     *      mappedBy="book",
     *     fetch="EXTRA_LAZY",)
     */
    private $reservations;

    /**
     * Description of the boook.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type(type="string")
     */
    private $description;

    /**
     * Category.
     *
     * @ORM\ManyToOne(targetEntity=Category::class,
     *      inversedBy="books",
     *     fetch="EXTRA_LAZY",
     * )
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * When book was added?
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="books",
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     *
     * @ORM\JoinTable(name="books_tags")
     */
    private $tags;

    /**
     * Availability.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $availability;

    /**
     * Author.
     *
     * @var \App\Entity\Author Author
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Author",
     *     inversedBy="books",
     *     fetch="EXTRA_LAZY",
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Rent::class,
     *      mappedBy="book",
     *     fetch="EXTRA_LAZY",)
     */
    private $rent;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->rent = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getIdBook(): ?int
    {
        return $this->idBook;
    }

    /**
     * Setter for IdBook.
     *
     * @param int $idBook
     *
     * @return $this
     */
    public function setIdBook(int $idBook): self
    {
        $this->idBook = $idBook;

        return $this;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title
     *
     * @return $this Title $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for pages.
     *
     * @return int|null Pages
     */
    public function getPages(): ?int
    {
        return $this->pages;
    }

    /**
     * Setter fot pages.
     *
     * @param int $pages
     *
     * @return $this Pages
     */
    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Getter for PublishDate.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getPublishDate(): ?DateTimeInterface
    {
        return $this->publishDate;
    }

    /**
     * Setter for PublishDate.
     *
     * @param DateTimeInterface $publishDate
     *
     * @return $this
     */
    public function setPublishDate(DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Getter for description.
     * @return $this Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string|null $description
     *
     * @return $this Description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for Category.
     * @return $this Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Getter fot UpdatedAt.
     * @return $this UpdatedAt
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param DateTimeInterface $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for Tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Tag adding.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Tag remover.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    /**
     * @param bool|null $availability
     *
     * @return $this
     */
    public function setAvailability(?bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author|null $author
     *
     * @return $this
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Rent[]
     */
    public function getRent(): Collection
    {
        return $this->rent;
    }

    /**
     * @param Rent $rent
     *
     * @return $this
     */
    public function addRent(Rent $rent): self
    {
        if (!$this->rent->contains($rent)) {
            $this->rent[] = $rent;
            $rent->setBook($this);
        }

        return $this;
    }

    /**
     * @param Rent $rent
     *
     * @return $this
     */
    public function removeRent(Rent $rent): self
    {
        if ($this->rent->contains($rent)) {
            $this->rent->removeElement($rent);
            // set the owning side to null (unless already changed)
            if ($rent->getBook() === $this) {
                $rent->setBook(null);
            }
        }

        return $this;
    }
}
