<?php


namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var ClientRepository
     */
    private $cRepository;
    
    public function __construct(UserPasswordEncoderInterface $encoder, ClientRepository $cRepository)
    {
        $this->encoder = $encoder;
        $this->cRepository = $cRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $client = $this->cRepository->findOneBy(["username" => "rakuten"]);
        $user
            ->setFirstname("John")
            ->setLastname("Doe")
            ->setEmail("johndoe@user.com")
            ->setPassword($this->encoder->encodePassword($user, "johndoepassword"))
            ->setClient($client);
        $client->addUser($user);
        $manager->persist($user);
        
        $user = new User();
        $client = $this->cRepository->findOneBy(["username" => "cdiscount"]);
        $user
            ->setFirstname("John")
            ->setLastname("Two")
            ->setEmail("johntwo@user.com")
            ->setPassword($this->encoder->encodePassword($user, "johntwopassword"))
            ->setClient($client);
        $client->addUser($user);
        $manager->persist($user);
        
        $user = new User();
        $client = $this->cRepository->findOneBy(["username" => "cdiscount"]);
        $user
            ->setFirstname("John")
            ->setLastname("Three")
            ->setEmail("johnthree@user.com")
            ->setPassword($this->encoder->encodePassword($user, "johnthreepassword"))
            ->setClient($client);
        $client->addUser($user);
        $manager->persist($user);
        
        $user = new User();
        $client = $this->cRepository->findOneBy(["username" => "Fnac"]);
        $user
            ->setFirstname("John")
            ->setLastname("Four")
            ->setEmail("johnfour@user.com")
            ->setPassword($this->encoder->encodePassword($user, "johnfourpassword"))
            ->setClient($client);
        $client->addUser($user);
        $manager->persist($user);
        
        $user = new User();
        $client = $this->cRepository->findOneBy(["username" => "Fnac"]);
        $user
            ->setFirstname("John")
            ->setLastname("Five")
            ->setEmail("johnfive@user.com")
            ->setPassword($this->encoder->encodePassword($user, "johnfivepassword"))
            ->setClient($client);
        $client->addUser($user);
        $manager->persist($user);
        
        $manager->flush();
    }
}