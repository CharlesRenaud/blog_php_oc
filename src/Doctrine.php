<?php
<<<<<<< HEAD

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
=======
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
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
<<<<<<< HEAD
            $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/Entities'], $isDevMode, null, null, false);
=======
            $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/Entities"], $isDevMode, null, null, false);
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335

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
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        return self::$entityManager;
    }
}
