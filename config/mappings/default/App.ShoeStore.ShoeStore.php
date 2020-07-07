<?php declare(strict_types=1);

use App\ShoeStore\ShoeStoreMapper;

/** @var  Doctrine\ORM\Mapping\ClassMetadata $metadata */
$metadata = isset($metadata) ? $metadata : null;

(new ShoeStoreMapper($metadata))();

return;
