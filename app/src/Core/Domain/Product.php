<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Domain;

class Product
{
    private string $id;
    private string $sku;
    private string $name;

    private function __construct(string $id, string $sku, string $name)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
    }

    /** 
     * Porque uma função estática? 
     * Esse método se torna um Factory Method, que é um padrão de projeto que visa a criação de objetos.
     * Isso faz com que o código fique mais limpo e organizado, além de facilitar a criação de objetos de diferentes formas.
     * Ex: eu poderia ter um método createFromName() que cria um objeto a partir do nome do produto e determina os outros atributos.
     **/ 
    public static function create(string $id, string $sku, string $name): self
    {
        return new self($id, $sku, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }
}