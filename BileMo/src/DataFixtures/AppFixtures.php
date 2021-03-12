<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Users
        $user1 = new User();
        $hash = $this->encoder->encodePassword($user1, 'pass1');
        $user1->setFirstName('jake')
            ->setLastName('mikkelson')
            ->setEmail('j.mikk@mail.com')
            ->setPassword($hash);
        $manager->persist($user1);

        $user2 = new User();
        $hash = $this->encoder->encodePassword($user2, 'pass2');
        $user2->setFirstName('simone')
            ->setLastName('marteen')
            ->setEmail('s.mart@mail.com')
            ->setPassword($hash);
        $manager->persist($user2);

        $user3 = new User();
        $hash = $this->encoder->encodePassword($user3, 'pass3');
        $user3->setFirstName('olivia')
            ->setLastName('pope')
            ->setEmail('à.pope@mail.com')
            ->setPassword($hash);
        $manager->persist($user3);


        // Products
        $product1= new Product();
        $product1 ->setName('I Phone 12 pro max')
                ->setCharacteristic('Non ergo erunt homines deliciis diffluentes audiendi, si quando de amicitia, quam nec usu nec ratione habent cognitam, disputabunt. Nam quis est, pro deorum fidem atque hominum! qui velit, ut neque diligat quemquam nec ipse ab ullo diligatur, circumfluere omnibus copiis atque in omnium rerum abundantia vivere? Haec enim est tyrannorum vita nimirum, in qua nulla fides, nulla caritas, nulla stabilis benevolentiae potest esse fiducia, omnia semper suspecta atque sollicita, nullus locus amicitiae.')
                ->setPrice(969.99)
                ->setReference('IPH1234567890');
        $manager->persist($product1);

        $product2= new Product();
        $product2 ->setName('SAMSUNG Galaxy Z Fold 2')
            ->setCharacteristic(' Paphius quin etiam et Cornelius senatores, ambo venenorum artibus pravis se polluisse confessi, eodem pronuntiante Maximino sunt interfecti. pari sorte etiam procurator monetae extinctus est. Sericum enim et Asbolium supra dictos, quoniam cum hortaretur passim nominare, quos vellent, adiecta religione firmarat, nullum igni vel ferro se puniri iussurum, plumbi validis ictibus interemit. et post hoe flammis Campensem aruspicem dedit, in negotio eius nullo sacramento constrictus.')
            ->setPrice(2020.99)
            ->setReference('SAMGZ98765432');
        $manager->persist($product2);

        $product3= new Product();
        $product3 ->setName('I Phone 11')
            ->setCharacteristic(' Hoc inmaturo interitu ipse quoque sui pertaesus excessit e vita aetatis nono anno atque vicensimo cum quadriennio imperasset. natus apud Tuscos in Massa Veternensi, patre Constantio Constantini fratre imperatoris, matreque Galla sorore Rufini et Cerealis, quos trabeae consulares nobilitarunt et praefecturae.')
            ->setPrice(759.99)
            ->setReference('IPH112233445566');
        $manager->persist($product3);

        //Clients
        $client1= new Client();
        $client1 ->setName('Web Shop')
            ->setUserName('WebShop43')
            ->setCreatedAt(new \DateTime('2021-01-15 12:14:34'))
            ->setPassword('password1');
        $manager->persist($client1);


        $manager->flush();
    }
}
