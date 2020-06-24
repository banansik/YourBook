<?php
/**
 * Author fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class AuthorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'authors', function ($i) {
            $author = new Author();
            $author->setTitle($this->faker->firstName);
            $author->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $author->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $author->setDescription($this->faker->text);

            return $author;
        });

        $manager->flush();
    }
}
