<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[
        Vich\UploadableField(
            mapping: 'media.original',
            fileNameProperty: 'originalFileName',
            size: 'size',
            mimeType: 'mimeType',
            originalName: 'originalName',
            dimensions: 'dimensions',
        ),
    ]
    private ?File $originalFile = null;

    #[ORM\Column(length: 255)]
    private ?string $originalFileName = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column(length: 100)]
    private ?string $mimeType = null;

    #[ORM\Column(nullable: true)]
    private ?array $dimensions = [];

    #[ORM\ManyToMany(targetEntity: MediaCollection::class, mappedBy: 'mediaList')]
    private Collection $mediaCollections;

    #[Vich\UploadableField(mapping: 'media.100w', fileNameProperty: 'imageFileName100w')]
    public ?File $imageFile100w = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $imageFileName100w = null;

    #[Vich\UploadableField(mapping: 'media.150w', fileNameProperty: 'imageFileName150w')]
    public ?File $imageFile150w = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $imageFileName150w = null;

    #[Vich\UploadableField(mapping: 'media.300w', fileNameProperty: 'imageFileName300w')]
    public ?File $imageFile300w = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $imageFileName300w = null;

    public function __construct()
    {
        $this->mediaCollections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getOriginalFile(): ?File
    {
        return $this->originalFile;
    }

    public function setOriginalFile(?File $originalFile = null): void
    {
        $this->originalFile = $originalFile;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(?string $originalFileName): self
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function __toString(): string
    {
        return $this->originalFileName;
    }

    /**
     * @return Collection<int, MediaCollection>
     */
    public function getMediaCollections(): Collection
    {
        return $this->mediaCollections;
    }

    public function addMediaCollection(MediaCollection $mediaCollection): self
    {
        if (!$this->mediaCollections->contains($mediaCollection)) {
            $this->mediaCollections->add($mediaCollection);
            $mediaCollection->addMediaList($this);
        }

        return $this;
    }

    public function removeMediaCollection(MediaCollection $mediaCollection): self
    {
        if ($this->mediaCollections->removeElement($mediaCollection)) {
            $mediaCollection->removeMediaList($this);
        }

        return $this;
    }
}
