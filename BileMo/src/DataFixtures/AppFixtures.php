<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Users

        $user1 = new User();
        $hash = $this->encoder->encodePassword($user1, 'pass');
        $user1->setName('userTest')
            ->setCreatedAt(new \DateTime('2021-04-01 12:14:34'))
            ->setEmail('userTest@mail.com')
            ->setPassword($hash);
        $manager->persist($user1);

        $user2 = new User();
        $hash = $this->encoder->encodePassword($user2, 'pass2');
        $user2->setName('MobileStore63')
            ->setCreatedAt(new \DateTime('2021-02-23 13:12:48'))
            ->setEmail('mobilestore63@mail.fr')
            ->setPassword($hash);
        $manager->persist($user2);

        $user3 = new User();
        $hash = $this->encoder->encodePassword($user3, 'pass3');
        $user3->setName('Web Shop')
            ->setCreatedAt(new \DateTime('2021-01-15 12:14:34'))
            ->setEmail('webshop@mail.fr')
            ->setPassword($hash);
        $manager->persist($user3);


        // Products

        $product1= new Product();
        $product1 ->setName('I Phone 12 pro max')
            ->setPrice(969.99)
            ->setReference('IPH1234567890')
            ->setColor('Pacific Blue')
            ->setBrand('Apple')
            ->setStorageCapacity('128')
            ->setOperatingSystem('ios')
            ->setScreenSize('6.7');
        $manager->persist($product1);

        $product2= new Product();
        $product2 ->setName('SAMSUNG Galaxy Z Fold 2')
            ->setPrice(2020.99)
            ->setReference('SAMGZ98765432')
            ->setColor('Black')
            ->setBrand('Samsung')
            ->setStorageCapacity('256')
            ->setOperatingSystem('Android')
            ->setScreenSize('6.2');
        $manager->persist($product2);

        $product3= new Product();
        $product3 ->setName('I Phone 11')
            ->setPrice(689.99)
            ->setReference('IPH112233445566')
            ->setColor('Purple')
            ->setBrand('Apple')
            ->setStorageCapacity('64')
            ->setOperatingSystem('ios')
            ->setScreenSize('6.1');
        $manager->persist($product3);

        for($j=1; $j<=25; $j++) {
            $product= new Product();
            $product ->setName('Phone' .$j)
                ->setPrice(969.99)
                ->setReference('REFPHONE' .$j)
                ->setColor('Blue')
                ->setBrand('Apple')
                ->setStorageCapacity('128')
                ->setOperatingSystem('ios')
                ->setScreenSize('6.7');
            $manager->persist($product);
        }

        //Customers

        $customer1= new Customer();
        $customer1  ->setEmail('s.mart@mail.com')
            ->setFirstName('simone')
            ->setLastName('marteen')
            ->setCreatedAt(new \DateTime('2021-03-05 15:27:34'))
            ->setUser($user1);
        $manager->persist($customer1);

        $customer2 = new Customer();
        $customer2 ->setEmail('o.pope@mail.com')
            ->setFirstName('olivia')
            ->setLastName('pope')
            ->setCreatedAt(new \DateTime('2021-03-03 12:14:34'))
            ->setUser($user2);
        $manager->persist($customer2);

        $customer3 = new Customer();
        $customer3 ->setEmail('j.mikk@mail.com')
            ->setFirstName('jake')
            ->setLastName('mikkelson')
            ->setCreatedAt(new \DateTime('2021-03-03 12:14:34'))
            ->setUser($user1);
        $manager->persist($customer3);

        for($i=1; $i<=10; $i++)
        {
            $customer4 = new Customer();
            $customer4 ->setEmail("user.$i @email.com")
                ->setLastName("User" .$i)
                ->setFirstName("first name" .$i)
                ->setCreatedAt(new \DateTime('2021-03-05 15:27:34'))
                ->setUser($user1);
            $manager->persist($customer4);
        }

        for($k=11; $k<=20; $k++)
        {
            $customer5 = new Customer();
            $customer5 ->setEmail("user.$k @email.com")
                ->setLastName("User" .$k)
                ->setFirstName("first name" .$k)
                ->setCreatedAt(new \DateTime('2021-04-03 17:12:34'))
                ->setUser($user2);
            $manager->persist($customer5);
        }

        for($l=21; $l<=30; $l++) {
            $customer6 = new Customer();
            $customer6->setEmail("user.$l @email.com")
                ->setLastName("User" . $l)
                ->setFirstName("first name" . $l)
                ->setCreatedAt(new \DateTime('2021-04-03 17:12:34'))
                ->setUser($user3);
            $manager->persist($customer6);
        }

        $manager->flush();
    }
}
