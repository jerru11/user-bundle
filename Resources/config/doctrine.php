<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events as DoctrineOrmEvents;
use MsgPhp\User\Infrastructure\Doctrine;
use MsgPhp\UserBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->private()
            ->bind(EntityManagerInterface::class, ref('msgphp.doctrine.entity_manager'))

        ->set(Doctrine\UsernameLookup::class)

        ->set(Doctrine\Event\UsernameListener::class)
            ->tag('doctrine.event_listener', ['event' => DoctrineOrmEvents::preFlush])
    ;

    foreach (Configuration::getPackageMetadata()->getDoctrineServicePrototypes() as $resource => $namespace) {
        $services->load($namespace, $resource);
    }
};
