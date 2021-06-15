<?php
declare(strict_types = 1);

class HomepageController
{
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        $pdo = Connection::Open();

        $handle = $pdo->prepare('SELECT product.name, product.price FROM product ORDER BY product.name');
        $handle->execute();
        $products = $handle->fetchAll();

        $handle = $pdo->prepare('SELECT customer.firstname, customer.lastname, customer.id FROM customer ORDER BY customer.firstname');
        $handle->execute();
        $customers = $handle->fetchAll();

        $handle = $pdo->prepare('SELECT id, name, parent_id, fixed_discount, variable_discount FROM customer_group');
        $handle->execute();
        $customersGroup = $handle->fetchAll();

        foreach ($customersGroup as $customerGroup) {
            $customGroup = new CustomerGroup((int)$customerGroup['id'],$customerGroup['name'],(int)$customerGroup['parent_id'],(int)$customerGroup['fixed_discount'],(int)$customerGroup['variable_discount']);
            var_dump($customGroup->getId());
        }

        //load the view
        require 'View/homepage.php';
    }
}

