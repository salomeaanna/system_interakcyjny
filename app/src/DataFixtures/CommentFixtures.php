<?php
/**
 * Comment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CommentFixtures.
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        $this->createMany(20, 'comments', function (int $i) {
            $comment = new Comment();
            $comment->setEmail($this->faker->email);
            $comment->setNick($this->faker->name);
            $comment->setComment($this->faker->sentence);

            /** @var Article $article Article entity */
            $article = $this->getRandomReference('articles');
            $comment->setArticle($article);

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: ArticleFixtures::class}
     */
    public function getDependencies(): array
    {
        return [ArticleFixtures::class];
    }
}
