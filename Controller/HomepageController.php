<?php
declare(strict_types=1);

class HomepageController
{
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        $pdo = Connection::Open();

        function getProducts($pdo)
        {
            $handle = $pdo->prepare('SELECT * FROM product');
            $handle->execute();
            $products = $handle->fetchAll();
            return $products;
        }

        function createProducts($pdo)
        {
            $products = getProducts($pdo);
            $result = [];
            foreach ($products as $product) {
                $new_product = new Product((int)$product['id'], $product['name'], (int)$product['price']);
                $result[] = $new_product;
            };
            return $result;
        }

        function getCustomers($pdo)
        {
            $handle = $pdo->prepare('SELECT * FROM customer');
            $handle->execute();
            $customers = $handle->fetchAll();
            return $customers;
        }

        function createCustomers($pdo)
        {
            $customers = getCustomers($pdo);
            $result = [];
            foreach ($customers as $customer) {
                $customerObj = new Customer($customer['firstname'], $customer['lastname'], (int)$customer['fixed_discount'], (int)$customer['variable_discount'], (int)$customer['id'], (int)$customer['group_id']);
                $result[] = $customerObj;
            }
            return $result;
        }

        //Customersgroup
        function getCustomersGroup($pdo)
        {
            $handle = $pdo->prepare('SELECT customer_group.id, name, parent_id, customer_group.fixed_discount, customer_group.variable_discount FROM customer_group LEFT JOIN customer ON customer.group_id = customer_group.id WHERE customer.group_id = :group_id');
            $handle->bindValue(':group_id', $_POST['customer-select']);
            $handle->execute();
            $customersGroup = $handle->fetch();
            return $customersGroup;
        }

        function createCustomersGroup($pdo)
        {
            $customerGroup = getCustomersGroup($pdo);
            $result = [];
            $customGroup = new CustomerGroup((int)$customerGroup['id'], $customerGroup['name'], (int)$customerGroup['parent_id'], (int)$customerGroup['fixed_discount'], (int)$customerGroup['variable_discount']);
            $result[] = $customGroup;
            return $result;
        }


        // Run function
        $products = createProducts($pdo);
        $customersGroup = createCustomersGroup($pdo);
        $customers = createCustomers($pdo);

        //load the view
        require 'View/homepage.php';
    }
}