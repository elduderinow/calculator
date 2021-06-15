<?php
declare(strict_types=1);

class HomepageController {
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST) {
        $pdo = Connection::Open();

        function getProducts($pdo) {
            $handle = $pdo->prepare('SELECT * FROM product');
            $handle->execute();
            $products = $handle->fetchAll();
            return $products;
        }

        function createProducts($pdo) {
            $products = getProducts($pdo);
            $result = [];
            foreach ($products as $product) {
                $new_product = new Product((int)$product['id'], $product['name'], (int)$product['price']);
                $result[] = $new_product;
            };
            return $result;
        }

        $handle = $pdo->prepare('SELECT id, firstname, lastname, group_id, fixed_discount, variable_discount FROM customer ORDER BY firstname');
        $handle->execute();
        $customers = $handle->fetchAll();


        //Customersgroup
        function getCustomersGroup($pdo) {
            $handle = $pdo->prepare('SELECT id, name, parent_id, fixed_discount, variable_discount FROM customer_group');
            $handle->execute();
            $customersGroup = $handle->fetchAll();
            return $customersGroup;
        }

        function createCustomersGroup($pdo) {
            $customersGroup = getCustomersGroup($pdo);
            $result = [];
            foreach ($customersGroup as $customerGroup) {
                $customGroup = new CustomerGroup((int)$customerGroup['id'], $customerGroup['name'], (int)$customerGroup['parent_id'], (int)$customerGroup['fixed_discount'], (int)$customerGroup['variable_discount']);
                $result[] = $customGroup;
            };
            return $result;
        }



        // Run function
        $products = createProducts($pdo);
        $customersGroup = createCustomersGroup($pdo);

        var_dump($products);
        var_dump($customersGroup);

        //load the view
        require 'View/homepage.php';
    }
}