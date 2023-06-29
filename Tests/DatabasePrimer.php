<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabasePrimer
{
    public static function prime(ContainerInterface $container)
    {
        // Make sure we are in the test environment
        // if ('test' !== $container->getEnvironment()) {
        //     throw new \LogicException('Primer must be executed in the test environment');
        // }

        // Get the entity manager from the service container
        $entityManager = $container->get('doctrine')->getManager();

        // Run the schema update tool using our entity metadata
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadatas);
        $schemaTool->updateSchema($metadatas);

        // If you are using the Doctrine Fixtures Bundle you could load these here
    }
}
