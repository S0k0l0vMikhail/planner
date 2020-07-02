<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Создание базовой организации
 */
class OrganizationFixtures extends Fixture
{
    function load(ObjectManager $manager)
    {
        $organization = new Organization();
        $organization->setName('ООО Рога и Копыта');

        //Создаем ссылку что бы позже могли получить эту организацию.
        //Первый аргумент произвольное название ссылки, второй - объект класса
        $this->addReference('organization-default', $organization);

        $manager->persist($organization);
        $manager->flush();
    }
}
