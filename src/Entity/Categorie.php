<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[Vich\Uploadable]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie est obligatoire')]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;
    #[Vich\UploadableField(mapping: 'categorie_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    public function setImageFile(?File $imageFile = null): void {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) { $this->updatedAt = new \DateTimeImmutable(); }
    }
    public function getImageFile(): ?File { return $this->imageFile; }
    public function setUpdatedAt(?\DateTimeImmutable $u): void { $this->updatedAt = $u; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }



public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
