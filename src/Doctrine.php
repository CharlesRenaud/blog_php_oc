<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Doctrine
{
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager
    {
        if (self::$entityManager === null) {
            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities'], $isDevMode, null, null, false);

            $conn = [
                'driver'   => 'pdo_mysql',
                'user'     => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'dbname'   => 'blog_php',
                'host'     => 'localhost',
            ];

            $connection = DriverManager::getConnection($conn);
            self::$entityManager = EntityManager::create($connection, $config);
        }
        return self::$entityManager;
    }
}
