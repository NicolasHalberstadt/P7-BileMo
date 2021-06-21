<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product
            ->setName("iPhone 11")
            ->setBrand("Apple")
            ->setDescription(
                "L'iPhone 11 est le modèle le plus accessible de la douzième génération du célèbre smartphone d'Apple. Successeur direct de l'iPhone XR, il dispose d'un écran LCD de 6,1 pouces, un SoC Apple A13 Bionic et un double capteur photo arrière."
            )
            ->setStock(true)
            ->setPrice(689);
        
        $manager->persist($product);
        
        $product = new Product();
        $product
            ->setName("SONY XPERIA 1 II")
            ->setBrand("Sony")
            ->setDescription(
                "Dévoilé à l’occasion du MWC 2020 — annulé par le coronavirus — en février dernier, le Sony Xperia 1 II est enfin une réalité. Il reprend grosso modo le design de l’ancien modèle avec des bordures en haut et en bas, entourant un écran OLED 4K de 6,5 pouces. Le ratio 21:9 est évidemment toujours de la partie. Si cela rallonge nécessairement le téléphone, c’est avant tout la promesse de dévorer son contenu vidéo de manière optimale sur smartphone."
            )
            ->setStock(true)
            ->setPrice(999);
        
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("LG Wing")
            ->setBrand("LG")
            ->setDescription(
                "Le LG Wing est un smartphone annoncé le 14 septembre 2020 qui possède un écran rotatif (de 6.8 pouces) laissant apparaître un deuxième écran de 3.9 pouces. Il est équipé d'un SoC Qualcomm Snapdragon 765G épaulé par 8 Go de RAM et 128 ou 256 Go de stockage, extensible via microSD. Il possède 3 capteurs photo à l'arrière, le principal fait 64 mégapixels. Pour les selfies l'appareil intègre une caméra pop-up de 32 mégapixels. Il possède une batterie de 4000 mAh compatible charge rapide et charge sans fil."
            )
            ->setStock(false)
            ->setPrice(569);
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Apple iPhone 12 Pro Max")
            ->setBrand("Apple")
            ->setDescription(
                "L'iPhone 12 Pro Max est le modèle grand-format haut de gamme de la 14e génération de smartphone d'Apple annoncé le 13 octobre 2020. Il est équipé d'un écran de 6,7 pouces OLED HDR 60 Hz, d'un triple capteur photo avec ultra grand-angle et téléobjectif (x5 optique) et d'un SoC Apple A14 Bionic compatible 5G (sub-6 GHz)."
            )
            ->setStock(true)
            ->setPrice(1139);
        
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Apple iPhone 12 Mini")
            ->setBrand("Apple")
            ->setDescription(
                "Les smartphones haut de gamme n’ont cessé de proposer des écrans plus grands, des batteries plus grosses et des prix plus élevés. Cependant, certaines marques ont tenu de continuer à satisfaire les amateurs de smartphones de moins de six pouces. C’est le cas de la marque à la Pomme qui, lors de sa keynote l’année dernière, a présenté son smartphone le plus compact : l’iPhone 12 Mini avec une diagonale de 5,4 pouces. "
            )
            ->setStock(true)
            ->setPrice(629);
        
        $manager->persist($product);
        
        $product = new Product();
        $product->setName("Xiaomi Redmi Note 10 Pro")
            ->setBrand("Xiaomi")
            ->setDescription(
                "Le Xiaomi Redmi Note 10 Pro est un smartphone 4G de la famille « Redmi Note 10 » annoncé en mars 2021. Tourné autour de la photographie, il est équipé d'un capteur principal de 108 mégapixels épaulé par 3 capteurs secondaires de 8+5+2 mégapixels. Il est équipé d'un SoC Qualcomm Snapdragon 732G, d'une batterie de 5020 mAh et d'un écran Super AMOLED Full HD+ 120 Hz."
            )
            ->setStock(true)
            ->setPrice(249);
        
        $manager->persist($product);
        
        $manager->flush();
    }
}
