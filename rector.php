<?php declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/srcIam/src',
        __DIR__ . '/srcIam/tests',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        /**
         * PHP
         */
        LevelSetList::UP_TO_PHP_80,

        /**
         * SYMFONY
         */
        SymfonySetList::SYMFONY_54,
        SymfonySetList::SYMFONY_CODE_QUALITY,

        /**
         * DOCTRINE
         */
        //        DoctrineSetList::DOCTRINE_DBAL_30,
        //        DoctrineSetList::DOCTRINE_ORM_29,
        DoctrineSetList::DOCTRINE_CODE_QUALITY,

        /**
         * PHPUNIT
         */
        PHPUnitSetList::PHPUNIT_91,
    ]);
};
