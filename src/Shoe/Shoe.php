<?php declare(strict_types=1);

namespace App\Shoe;

use phpDocumentor\Reflection\DocBlock\Tags\PropertyRead;

class Shoe
{
    private  int    $id;
    private  string $code;
    private  string $name;
    private  string $color;
    private ?string $image;
    private ?string $notes;

    public function __construct(string $code, string $name = '', string $color = '')
    {
        $this->id    = 0;
        $this->code  = $code;
        $this->name  = $name;
        $this->color = $color;
    }
    public function getCodeColor() : string
    {
        $code = $this->code;
        if (strlen($code) !== 9) {
            return $code;
        }
        return substr($code,0,6) . ' ' . substr($code,-3);
    }



    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
