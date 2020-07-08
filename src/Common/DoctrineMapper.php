<?php declare(strict_types=1);

namespace App\Common;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineMapper
{
    protected ClassMetadata        $metadata;
    protected ClassMetadataBuilder $builder;

    public function __construct(ClassMetadata $metadata)
    {
        $this->metadata = $metadata;
        $this->builder = new ClassMetadataBuilder($metadata);
    }
    protected function createAutoincIdField(string $propertyName = 'id', string $columnName = 'id') : FieldBuilder
    {
        return $this->builder->createField($propertyName, 'integer')
            ->columnName($columnName)
            ->makePrimaryKey()
            ->generatedValue();
    }
}