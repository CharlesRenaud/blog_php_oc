<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Doctrine
{
    private static $entityManager;

    public static function getEntityManager()
    {
        if (self::$entityManager === null) {
            // Create a simple "default" Doctrine ORM configuration for Annotations
            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/Entities"], $isDevMode, null, null, false);

            // database configuration parameters
            $conn = array(
                'driver'   => 'pdo_mysql',
                'user'     => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'dbname'   => 'blog_php',
                'host'     => 'localhost',
            );

            $connection = DriverManager::getConnection($conn);
            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }
}
