<?php declare(strict_types=1);

namespace App\Misc;

use App\Entity\ShoeStore;

class ShoeStoreSummaryRow
{
    public string $shoeCode = '';
    public string $shoeName;
    public string $shoeColor;
    public array  $prices;
    public array  $storeCodes = ['CELB', 'WG', 'INT', 'VINE'];

    public function setShoeStore(ShoeStore $shoeStore) : void
    {
        $shoe = $shoeStore->getShoe();
        $this->shoeCode = $shoe->getCodeColor();
        $this->shoeName = $shoe->getName();
        $this->shoeColor = $shoe->getColor();

        $this->prices = [];
        foreach($this->storeCodes as $storeCode) {
            $this->prices[$storeCode] = '';
        }
        $this->prices[$shoeStore->getStore()] = $shoeStore->getPrice();
    }
    public function addShoeStore(ShoeStore $shoeStore) : void
    {
        $this->prices[$shoeStore->getStore()] = $shoeStore->getPrice();
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
    public function getPrice(string $storeCode) : ?float {
        $price = $this->prices[$storeCode];
        if ($price < 1) return null;
        return (float)$price / 100.00;
    }
}