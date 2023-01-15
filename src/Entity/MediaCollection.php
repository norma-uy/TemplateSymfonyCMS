<?php

namespace App\Entity;

use App\Repository\MediaCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaCollectionRepository::class)]
class MediaCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $linkTo = null;

    #[
        ORM\ManyToMany(
            targetEntity: Media::class,
            inversedBy: 'mediaCollections',
        ),
    ]
    private Collection $mediaList;

    #[ORM\Column]
    private bool $setAsHomeSlider = false;

    public function __construct()
    {
        $this->mediaList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getLinkTo(): ?string
    {
        return $this->linkTo;
    }

    public function setLinkTo(?string $linkTo): self
    {
        $this->linkTo = $linkTo;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMediaList(): Collection
    {
        return $this->mediaList;
    }

    public function addMediaList(Media $mediaList): self
    {
        if (!$this->mediaList->contains($mediaList)) {
            $this->mediaList->add($mediaList);
        }

        return $this;
    }

    public function removeMediaList(Media $mediaList): self
    {
        $this->mediaList->removeElement($mediaList);

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function isSetAsHomeSlider(): ?bool
    {
        return $this->setAsHomeSlider;
    }

    public function setAsHomeSlider(bool $setAsHomeSlider): self
    {
        $this->setAsHomeSlider = $setAsHomeSlider;

        return $this;
    }
}
