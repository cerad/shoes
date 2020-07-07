<?php declare(strict_types=1);

namespace App\Shoe;

use App\DoctrineMapper;
//use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
//use Doctrine\ORM\Mapping\ClassMetadata;

class ShoeMapper extends DoctrineMapper
{
    public function __invoke() : void
    {
        $builder = $this->builder;

        $builder->setTable('shoes');
        $builder->setCustomRepositoryClass(ShoeRepository::class);

        $this->createAutoincIdField()->build();

        $builder->createField('code', 'string')
            ->columnName('code')
            ->length(20)->option('fixed', true)->nullable(false)
            ->unique(true)
            ->build();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->length(40)->option('fixed', true)->nullable(false)
            ->build();

        $builder->createField('color', 'string')
            ->columnName('color')
            ->length(40)->option('fixed', true)->nullable(false)
            ->build();

        $builder->createField('image', 'string')
            ->columnName('image')
            ->length(40)->option('fixed', true)->nullable(true)
            ->build();

        $builder->createField('notes', 'string')
            ->columnName('notes')
            ->length(40)->option('fixed', true)->nullable(true)
            ->build();

    }
}