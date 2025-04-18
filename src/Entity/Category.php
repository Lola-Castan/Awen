<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    private Collection $products;

    /**
     * @var Collection<int, User>
     * Relation avec les utilisateurs qui sont des créateurs
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'categories')]
    #[ORM\JoinTable(name: 'category_creator')]
    private Collection $creators;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->creators = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCreators(): Collection
    {
        return $this->creators;
    }

    public function addCreator(User $creator): static
    {
        if (!$this->creators->contains($creator)) {
            // Vérification (optionnelle) que l'utilisateur est bien un créateur
            $this->creators->add($creator);
        }

        return $this;
    }

    public function removeCreator(User $creator): static
    {
        $this->creators->removeElement($creator);

        return $this;
    }
}
