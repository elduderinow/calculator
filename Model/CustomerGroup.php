<?php
declare(strict_types=1);

class CustomerGroup {
    private int $id;
    private string $name;
    private int $parentId;
    private int $fixedDiscount;
    private int $variableDiscount;

    public function __construct(int $id, string $name, int $parentId, int $fixedDiscount, int $variableDiscount) {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->fixedDiscount = $fixedDiscount;
        $this->variableDiscount = $variableDiscount;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getParentId(): int {
        return $this->parentId;
    }

    public function getFixedDiscount(): int {
        return $this->fixedDiscount;
    }

    public function getVariableDiscount(): int {
        return $this->variableDiscount;
    }
}