<?php
/**
 * Task fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TaskFixtures.
 */
class BookFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'tasks', function ($i) {
            $book = new Book();
            $book->setIdBook($this->faker->numberBetween(1, 10));
            $book->setTitle($this->faker->sentence);
            $book->setPages($this->faker->numberBetween(180, 685));
            $book->setPublishDate($this->faker->dateTimeBetween('-50 years', '+1 day'));
            $book->setUpdatedAt(($this->faker->dateTimeBetween('-200 days', '+1 day')));
            $book->setDescription($this->faker->sentence());
            $book->setAvailability($this->faker->boolean());
            $book->setCategory($this->getRandomReference('categories'));
            $book->setAuthor($this->getRandomReference('authors'));

            return $book;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
