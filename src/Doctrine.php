<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class Doctrine
{

    private static $entityManager;

    public static function getEntityManager()
    {


        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        echo $username . $password;

        if (self::$entityManager === null) {
            // Create a simple "default" Doctrine ORM configuration for Annotations
            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/Entities"], $isDevMode, null, null, false);

            // database configuration parameters
            $conn = array(
                'driver'   => 'pdo_mysql',
                'user'     => $username,
                'password' => $password,
                'dbname'   => 'blog_php',
                'host'     => 'localhost',
            );

            $connection = DriverManager::getConnection($conn);
            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }
}
