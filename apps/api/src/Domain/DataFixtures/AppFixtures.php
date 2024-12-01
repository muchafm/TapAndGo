<?php

declare(strict_types=1);

namespace App\Domain\DataFixtures;

use App\Domain\Data\Model\City;
use App\Domain\Data\Model\Station;
use App\Domain\Data\Enum;
use App\Domain\Data\ValueObject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{   
    protected Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $city = new City($this->faker->city, new ValueObject\Position($this->faker->latitude(), $this->faker->longitude()), true);
        $manager->persist($city);

        for ($i = 0; $i < 10; $i++) {
            $station = new Station(
                $this->faker->lexify("{$city->getName()}-??????"), 
                new ValueObject\Position(
                    $this->faker->latitude(), 
                    $this->faker->longitude()
                ), 
                $this->faker->address(),
                Enum\State::ENABLED,
                rand(10, 15),
                $city
            );
            $manager->persist($station);
        }

        $manager->flush();
    }
}
