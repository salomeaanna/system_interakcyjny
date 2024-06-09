<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    /**
     * Id.
     *
     * @var int|null id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nick.
     *
     * @var string|null nick
     */
    #[ORM\Column(length: 64)]
    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $nick = null;

    /**
     * Email.
     *
     * @var string|null email
     */
    #[ORM\Column(length: 64)]
    #[Assert\Type('string')]
    #[Assert\Email]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $email = null;

    /**
     * Comment.
     *
     * @var string|null comment
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\Length(min: 3)]
    private ?string $comment = null;

    /**
     * Article.
     *
     * @var Article|null article
     */
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Assert\Type(Article::class)]
    private ?Article $article = null;

    /**
     * Getter for id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for nick.
     *
     * @return string|null nick
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Setter for nick.
     *
     * @param string $nick nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * Getter for email.
     *
     * @return string|null email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for comment.
     *
     * @return string|null comment
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Setter for comment.
     *
     * @param string $comment comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter for article.
     *
     * @return Article|null article
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * Setter for article.
     *
     * @param Article|null $article article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }
}
