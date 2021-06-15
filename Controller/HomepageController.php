<?php
declare(strict_types = 1);

class HomepageController
{
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        $pdo = Connection::Open();

        $handle = $pdo->prepare('SELECT name, price FROM product ORDER BY product.name');
        $handle->execute();
        $products = $handle->fetchAll();

        $handle = $pdo->prepare('SELECT id, firstname, lastname, group_id, fixed_discount, variable_discount FROM customer ORDER BY firstname');
        $handle->execute();
        $customers = $handle->fetchAll();


            //load the view
        require 'View/homepage.php';
    }
}