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

    #[
        Vich\UploadableField(
            mapping: 'media',
            fileNameProperty: 'fileName',
            size: 'size',
            mimeType: 'mimeType',
            originalName: 'originalName',
            dimensions: 'dimensions',
        ),
    ]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column(length: 100)]
    private ?string $mimeType = null;

    #[ORM\Column(nullable: true)]
    private ?array $dimensions = [];

    #[
        ORM\ManyToMany(
            targetEntity: MediaCollection::class,
            mappedBy: 'mediaList',
        ),
    ]
    private Collection $mediaCollections;

    public function __construct()
    {
        $this->mediaCollections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

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
        return $this->fileName;
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

    public function removeMediaCollection(
        MediaCollection $mediaCollection,
    ): self {
        if ($this->mediaCollections->removeElement($mediaCollection)) {
            $mediaCollection->removeMediaList($this);
        }

        return $this;
    }
}
