<?php


namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ClientFixtures
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package App\DataFixtures
 */
class ClientFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoder
     */
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $client
            ->setEmail("vente@rakuten.com")
            ->setUsername("rakuten")
            ->setPassword($this->encoder->encodePassword($client, 'motdepasserakuten'))
            ->setRoles((array)"ROLE_CLIENT");;
        $manager->persist($client);
        
        $client = new Client();
        $client
            ->setEmail("vente@cdiscount.com")
            ->setUsername("CDiscount")
            ->setPassword($this->encoder->encodePassword($client, 'motdepassecdiscount'))
            ->setRoles((array)"ROLE_CLIENT");;
        $manager->persist($client);
    
        $client = new Client();
        $client
            ->setEmail("vente@fnac.com")
            ->setUsername("Fnac")
            ->setPassword($this->encoder->encodePassword($client, 'motdepassefnac'))
            ->setRoles((array)"ROLE_CLIENT");;
        $manager->persist($client);
        
        $client = new Client();
        $client
            ->setEmail("admin@bilemo.com")
            ->setUsername("admin")
            ->setPassword($this->encoder->encodePassword($client, 'motdepasseadmin'))
            ->setRoles((array)"ROLE_ADMIN");;
        $manager->persist($client);
        
        $manager->flush();
    }
}