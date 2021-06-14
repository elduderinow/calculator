<?php
declare(strict_types=1);

class Customer
{
    private int $id;
    private int $groupId;
    private string $firstname;
    private string $lastname;
    private int $fixedDiscount;
    private int $variableDiscount;

    public function __construct(string $firstname, string $lastname, int $fixedDiscount, int $variableDiscount, int $id, int $groupId)
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->fixedDiscount = $fixedDiscount;
        $this->variableDiscount = $variableDiscount;
    }

    /**
     * Get the full name
     */
    public function getFullName() : string
    {
        return "$this->firstname $this->lastname";
    }

    /**
     * Get the value of fixedDiscount
     */
    public function getFixedDiscount(): int
    {
        return $this->fixedDiscount;
    }

    /**
     * Get the value of variableDiscount
     */
    public function getVariableDiscount(): int
    {
        return $this->variableDiscount;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of groupId
     */
    public function getGroupId(): int
    {
        return $this->groupId;
    }
}