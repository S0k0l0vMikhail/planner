<?php

namespace App\DataFixtures;

use App\Entity\TableVacation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Создание базового пользователя
 */
class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('root@root.root');
        $user->setUsername('root');
        $user->setRoles(['ROLE_ADMIN']);

        $password = $this->encoder->encodePassword($user, '000');
        $user->setPassword($password);

        $organization = $this->getReference('organization-default');
        $user->setOrganization($organization);
        $this->addReference('user-default', $user);
        $tableVacation = new TableVacation();
        $tableVacation->setName("График отпусков");
        $tableVacation->setYear(date('Y'));
        $tableVacation->setUser($user);

        $manager->persist($tableVacation);

        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            OrganizationFixtures::class
        );
    }
}
