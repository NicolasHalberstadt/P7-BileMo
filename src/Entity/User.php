<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"list"})
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"list", "details"})
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"list", "details"})
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @Serializer\Groups({"list", "details"})
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
