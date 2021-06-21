<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *     "app_products_show",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"details"})
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "details"})
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=900)
     * @Serializer\Groups({"details"})
     */
    private $description;
    
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Serializer\Groups({"list", "details"})
     */
    private $price;
    
    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"list", "details"})
     */
    private $stock;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "details"})
     */
    private $brand;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getPrice(): ?string
    {
        return $this->price;
    }
    
    public function setPrice(string $price): self
    {
        $this->price = $price;
        
        return $this;
    }
    
    public function getStock(): ?bool
    {
        return $this->stock;
    }
    
    public function setStock(bool $stock): self
    {
        $this->stock = $stock;
        
        return $this;
    }
    
    public function getBrand(): ?string
    {
        return $this->brand;
    }
    
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        
        return $this;
    }
}
