<?php declare(strict_types=1);

namespace App\ShoeStore;

use App\ShoeStore\ShoeStore;

class ShoeStoreSummaryRow
{
    public string $shoeCode = '';
    public string $shoeName;
    public string $shoeColor;

    private array  $prices;
    private array  $storeCodes;

    public function __construct(array $storeCodes)
    {
        $this->storeCodes = $storeCodes;
    }
    public function setShoeStore(ShoeStore $shoeStore) : void
    {
        $shoe = $shoeStore->getShoe();
        $this->shoeCode = $shoe->getCodeColor();
        $this->shoeName = $shoe->getName();
        $this->shoeColor = $shoe->getColor();

        $this->prices = [];
        foreach($this->storeCodes as $storeCode) {
            $this->prices[$storeCode] = null;
        }
        $this->prices[$shoeStore->getStore()] = $shoeStore->getPriceCurrency();
    }
    public function addShoeStore(ShoeStore $shoeStore) : void
    {
        $this->prices[$shoeStore->getStore()] = $shoeStore->getPriceCurrency();
    }
    public function hasShoeChanged(ShoeStore $shoeStore) : bool
    {
        if ($this->shoeCode === $shoeStore->getShoe()->getCodeColor()) {
            //echo "{$this->shoeCode}\n";
            return false;
        }
        // Hack for very first one;
        if ($this->shoeCode === '') {
            $this->setShoeStore($shoeStore);
            return false;
        }
        return true;
    }
    public function getPriceForStore(string $storeCode) : ?float {
        return $this->prices[$storeCode];
    }
}