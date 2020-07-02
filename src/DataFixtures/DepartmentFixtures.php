<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Создание списка базовых отделов
 */
class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{
    function load(ObjectManager $manager)
    {
        $departments = [
            'Отдел разработки', 'Отдел интеграций', 'Отдел тестирования',
            'Отдел технической поддержки', 'Руководящий состав'
        ];

        //Получаем объект по ссылке по имени.
        $organization = $this->getReference('organization-default');
        foreach ($departments as $departmentName) {
            $department = new Department();
            $department->setName($departmentName);
            $department->setOrganization($organization);
            $department->addUser($this->getReference('user-default'));
            $manager->persist($department);
        }

        $manager->flush();
    }

    /**
     * Стандартный метод. Устанавливает порядок(зависимость).
     * Должен возвращать классы фикстур которые должны создаться раньше.
     */
    public function getDependencies()
    {
        return array(
            OrganizationFixtures::class,
            UserFixtures::class,
        );
    }
}
