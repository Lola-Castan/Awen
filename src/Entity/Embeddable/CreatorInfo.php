<?php

namespace App\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class CreatorInfo
{
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $instagramProfile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $facebookProfile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pinterestProfile = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $practicalInfos = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $coverImage = null;

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getInstagramProfile(): ?string
    {
        return $this->instagramProfile;
    }

    public function setInstagramProfile(?string $instagramProfile): self
    {
        $this->instagramProfile = $instagramProfile;
        return $this;
    }

    public function getFacebookProfile(): ?string
    {
        return $this->facebookProfile;
    }

    public function setFacebookProfile(?string $facebookProfile): self
    {
        $this->facebookProfile = $facebookProfile;
        return $this;
    }

    public function getPinterestProfile(): ?string
    {
        return $this->pinterestProfile;
    }

    public function setPinterestProfile(?string $pinterestProfile): self
    {
        $this->pinterestProfile = $pinterestProfile;
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

    public function getPracticalInfos(): ?string
    {
        return $this->practicalInfos;
    }

    public function setPracticalInfos(?string $practicalInfos): self
    {
        $this->practicalInfos = $practicalInfos;
        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;
        return $this;
    }
    
    /**
     * Détermine si cet utilisateur est un créateur actif
     * On considère qu'un créateur est actif s'il a défini un nom d'affichage
     */
    public function isActive(): bool
    {
        return $this->displayName !== null && trim($this->displayName) !== '';
    }
}

