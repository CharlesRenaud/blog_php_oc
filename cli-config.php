<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;


require_once 'src/Doctrine.php';

// Récupération de l'EntityManager
$entityManager = Doctrine::getEntityManager();

// Création d'un HelperSet à partir de l'EntityManager
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
));

return $helperSet;
