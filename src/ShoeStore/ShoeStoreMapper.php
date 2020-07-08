<?php declare(strict_types=1);

namespace App\ShoeStore;

use App\Common\DoctrineMapper;
use App\Shoe\Shoe;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;

class ShoeStoreMapper extends DoctrineMapper
{
    public function __invoke() : void
    {
        $builder = $this->builder;

        $builder->setTable('shoe_stores');
        $builder->setCustomRepositoryClass(ShoeStoreRepository::class);

        $this->createAutoincIdField()->build();

        $builder->createManyToOne('shoe',Shoe::class)
            ->addJoinColumn('shoe_id', 'id', false, false)
            ->cascadePersist()
            ->build()
        ;

        $builder->createField('store', 'string')
            ->columnName('store')
            ->length(8)->option('fixed', true)->nullable(false)
            // Create an index
            ->build();

        $builder->createField('price', 'integer')
            ->columnName('price')
            ->nullable(false)
            ->build();

        $builder->addIndex(['store','shoe_id'],'SHOE_STORE_STORE_INDEX');

    }
}