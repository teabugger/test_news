<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Filter\DateWithoutTimeFilter;
use App\Filter\OffsetPaginator;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable
 * @ApiResource(shortName="News", order={"publishedAt": "desc", "title": "asc"}, paginationEnabled=false)
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ApiFilter(DateWithoutTimeFilter::class, properties={"publishedAt"})
 * @ApiFilter(OffsetPaginator::class)
 */
class News
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private string $text;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private string $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $publishedAt;

    public function __construct(string $title, string $text, string $author, DateTime $publishedAt)
    {
        $this->title = $title;
        $this->author = $author;
        $this->text = $text;
        $this->publishedAt = $publishedAt;
    }

    public function getId(): ?int
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

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
