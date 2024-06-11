<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PdfRepository;

#[ORM\Entity(repositoryClass: PdfRepository::class)]
class Pdf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $is_deleted = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'pdfs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->is_deleted = false;
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\Column(type: 'blob')]
    private $content;

    #[ORM\OneToOne(mappedBy: 'pdf', cascade: ['persist', 'remove'])]
    private ?PdfHistory $pdfHistory = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPdfHistory(): ?PdfHistory
    {
        return $this->pdfHistory;
    }

    public function setPdfHistory(?PdfHistory $pdfHistory): static
    {
        // unset the owning side of the relation if necessary
        if ($pdfHistory === null && $this->pdfHistory !== null) {
            $this->pdfHistory->setPdf(null);
        }

        // set the owning side of the relation if necessary
        if ($pdfHistory !== null && $pdfHistory->getPdf() !== $this) {
            $pdfHistory->setPdf($this);
        }

        $this->pdfHistory = $pdfHistory;

        return $this;
    }
}
