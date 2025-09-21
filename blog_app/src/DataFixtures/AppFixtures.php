<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur
        $user = new User();
        $user->setFullName('Admin Jstore');
        $user->setEmail('admin@jstore.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCreatedAt(new \DateTimeImmutable());
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);
        
        $manager->persist($user);

        // Créer une catégorie
        $category = new Category();
        $category->setName('Technologie');
        $category->setDescription('Articles sur la technologie et l\'informatique');
        $category->setImageUrl('/build/images/category-tech.jpg');
        $category->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($category);

        // Créer quelques articles
        for ($i = 1; $i <= 5; $i++) {
            $article = new Article();
            $article->setTitle("Article de test #$i");
            $article->setContent("Ceci est le contenu de l'article numéro $i. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
            $article->setImageUrl("/build/images/default-article.jpg");
            $article->setAuthor($user);
            $article->setCreatedAt(new \DateTimeImmutable("-$i days"));
            $article->addCategory($category);
            
            $manager->persist($article);
        }

        $manager->flush();
    }
}
