<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
        $this->loadProducts($manager);
    }

    private function loadProducts(ObjectManager $manager): void
    {
        foreach ($this->getProductsData() as [$name, $image, $price, $category]) {
            $product = new Product();
            $product->setCreatedAt(new \DateTime('NOW'));
            $product->setUpdatedAt(new \DateTime('NOW'));
            $product->setName($name);
            $product->setImage($image);
            $product->setPrice($price);
            $product->setCategory($this->getReference($category));

            $manager->persist($product);
        }
        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager) :void
    {
        foreach ($this->getCategoriesData() as [$name]) {
            $category = new Category();
            $category->setName($name);

            $this->addReference($name, $category);

            $manager->persist($category);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    private function getCategoriesData(): array
    {
        return [
            // $categoriesData = [$name];
            [
                'PC Games',
            ],
            [
                'Accessories',

            ],
            [
                'PS Games',
            ]
        ];
    }

    /**
     * @return array
     */
    private function getProductsData(): array
    {
        return [
            // $productData = [$name, $image, $price, $category];
            [
                'Headphones',
                null,
                150,
                "Accessories"
            ],
            [
                'Apple MacBook Air',
                null,
                3799,
                "Accessories"
            ],
            [
                'Dell Inspiron 5570',
                null,
                2499,
                "Accessories"
            ],
            [
                'PC game name',
                null,
                50,
                "PC Games"
            ], [
                'PS game name',
                null,
                120,
                "PS Games"
            ]
        ];
    }
}
