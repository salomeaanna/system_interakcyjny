<?php
/**
 * Article fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Article;

/**
 * Class ArticleFixtures.
 */
class ArticleFixtures extends AbstractBaseFixtures
{
    /**
     * Load Data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $article = new Article();
            $article->setTitle($this->faker->sentence);
            $article->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $article->setUpdatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $this->manager->persist($article);
        }

        $this->manager->flush();
    }
}
