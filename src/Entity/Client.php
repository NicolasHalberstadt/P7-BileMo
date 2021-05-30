<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(fields={"email", "username"})
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *     "app_clients_show",
 *     parameters = { "id" = "expr(object.getId())" },
 *     absolute = true
 *     )
 * )
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"list", "details", "creation"})
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @Serializer\Groups({"list", "details", "creation"})
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"creation"})
     */
    private $password;
    
    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client", orphanRemoval=true)
     * @Serializer\Groups({"details"})
     */
    private $users;
    
    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    public function setUsername(string $username): self
    {
        $this->username = $username;
        
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
    
    public function setPassword(string $password): self
    {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }
    
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }
        
        return $this;
    }
    
    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }
        
        return $this;
    }
    
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_CLIENT';
        
        return array_unique($roles);
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    
}
