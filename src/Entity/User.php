<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * @Serializer\ExclusionPolicy("ALL")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *     "app_users_show",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route(
 *     "app_users_remove",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @Serializer\Expose
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $password;
    
    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        
        return $this;
    }
    
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): self
    {
        $this->password = $password;
        
        return $this;
    }
    
    public function getClient(): ?Client
    {
        return $this->client;
    }
    
    public function setClient(?Client $client): self
    {
        $this->client = $client;
        
        return $this;
    }
    
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }
    
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    
    public function getUsername(): string
    {
        return ($this->getFirstname().' '.$this->getLastname());
    }
    
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
