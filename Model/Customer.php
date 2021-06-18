<?php
declare(strict_types=1);

class Customer {
    private int $id;
    private int $groupId;
    private string $firstname;
    private string $lastname;
    private int $fixedDiscount;
    private int $variableDiscount;
    private array $groups;

    public function __construct(string $firstname, string $lastname, int $fixedDiscount, int $variableDiscount, int $id, int $groupId) {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->fixedDiscount = $fixedDiscount;
        $this->variableDiscount = $variableDiscount;
        $this->groups = [];
    }

    /**
     * Get the full name
     */
    public function getFullName() : string {
        return "$this->firstname $this->lastname";
    }

    /**
     * Get the value of fixedDiscount
     */
    public function getFixedDiscount(): int {
        return $this->fixedDiscount;
    }

    /**
     * Get the value of variableDiscount
     */
    public function getVariableDiscount(): int {
        return $this->variableDiscount;
    }

    /**
     * Get the value of id
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get the value of groupId
     */
    public function getGroupId(): int {
        return $this->groupId;
    }

    /**
     * Set the value of groups
     */
    public function setGroup($group) {
        $this->groups[] = $group;
    }

    /**
     * Calculate all fixed discounts from customer groups
     */
    public function calcFixedDiscounts() {
        $total = 0;
        foreach ($this->groups as $group) {
            $total += $group->getFixedDiscount();
        }
        return $total;
    }

    /**
     * Calculate biggest variable discount from customer groups
     */
    public function calcBiggestVariableDiscount() {
        $bestDiscount = 0;
        foreach ($this->groups as $group) {
            if ($group->getVariableDiscount() > $bestDiscount) {
                $bestDiscount = $group->getVariableDiscount();
            }
        }
        return $bestDiscount;
    }
}