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

        //load the view
        require 'View/homepage.php';
    }
}