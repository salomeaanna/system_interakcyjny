<?php
/**
 * Article entity.
 */

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Article.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\Table(name: 'articles')]
class Article
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[Assert\Type('string')]
    private ?string $title = null;

    /**
     * Article content.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private ?string $content = null;

    /**
     * Slug.
     */
    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Assert\Length(min: 3, max: 255)]
    #[Assert\Type('string')]
    private ?string $slug = null;

    /**
     * Categories.
     *
     * @var Category|null category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(Category::class)]
    #[Assert\NotBlank]
    private ?Category $category = null;

//    /**
//     * Comments.
//     *
//     * @var Collection<int, Comment> Comment collection
//     */
//    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, cascade: ['persist', 'remove'])]
//    #[ORM\JoinColumn(name: 'article_id', referencedColumnName: 'id')]
//    private Collection $comments;

//    /**
//     * Constructor.
//     */
//    public function __construct()
//    {
//        $this->comments = new ArrayCollection();
//    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Updated at.
     *
     * @return \DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated at.
     *
     * @param \DateTimeImmutable $updatedAt Updated at
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Article content.
     *
     * @return string|null Article content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Article content.
     *
     * @param string|null $content Article content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    //    public function getSlug(): ?string
    //    {
    //        return $this->slug;
    //    }
    //
    //    public function setSlug(string $slug): static
    //    {
    //        $this->slug = $slug;
    //
    //        return $this;
    //    }

    /**
     * Getter for category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

//    /**
//     * Getter for comments.
//     *
//     * @return Collection<int, Comment> comment
//     */
//    public function getComments(): Collection
//    {
//        return $this->comments;
//    }
//
//    /**
//     * Add comment.
//     *
//     * @param Comment $comment comment
//     */
//    public function addComment(Comment $comment): void
//    {
//        if (!$this->comments->contains($comment)) {
//            $this->comments->add($comment);
//            $comment->setArticle($this);
//        }
//    }
//
//    /**
//     * Remove comment.
//     *
//     * @param Comment $comment comment
//     */
//    public function removeComment(Comment $comment): void
//    {
//        // set the owning side to null (unless already changed)
//        if ($this->comments->removeElement($comment) && $comment->getArticle() === $this) {
//            $comment->setArticle(null);
//        }
//    }
}
