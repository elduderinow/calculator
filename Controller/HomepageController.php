<?php
declare(strict_types=1);

class HomepageController
{
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        session_start();
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
            $handle->bindValue(':group_id', $_SESSION['customer-groupId'] ?: 2);
            $handle->execute();
            $customersGroup = $handle->fetch();
            return $customersGroup;
        }

        function createCustomersGroup($pdo)
        {
            $customerGroup = getCustomersGroup($pdo);
            $customGroup = new CustomerGroup((int)$customerGroup['id'], $customerGroup['name'], (int)$customerGroup['parent_id'], (int)$customerGroup['fixed_discount'], (int)$customerGroup['variable_discount']);
            return $customGroup;

        }


        function getGroupDiscount($pdo, $customer, $id=NULL){
            $getDiscount = createCustomersGroup($pdo);
            if (is_null($getDiscount->getParentId())){
                return;
            }

            $handle = $pdo->prepare('SELECT cg2.id, cg2.name, cg2.parent_id, cg2.fixed_discount, cg2.variable_discount FROM calculator.customer_group cg1 LEFT JOIN calculator.customer_group as cg2 ON cg1.parent_id = cg2.id WHERE cg1.id = :id;');
            $value = is_null($id) ? $customer->getGroupId() : $id;
            $handle->bindValue(':id', $value);
            $handle->execute();
            $discount = $handle->fetch();
            var_dump($discount);
            $customerGroup1 = new CustomerGroup((int)$discount['id'], $discount['name'], (int)$discount['parent_id'], (int)$discount['fixed_discount'], (int)$discount['variable_discount']);
            $customer->setGroups($customerGroup1);
            getGroupDiscount($pdo, $customer, $customerGroup1->getParentId());

        }




        // Run function
        $products = createProducts($pdo);

        if(isset($_POST['customer-id'])) {
            $customerPost = json_decode($_POST['customer-id'], true);
            $_SESSION['customer-id'] = $customerPost['id'];
            $_SESSION['customer-groupId'] = $customerPost['groupId'];
            $customersGroup = createCustomersGroup($pdo);
        }


        $customers = createCustomers($pdo);
        $xxx = new Customer('Aline', 'Baillargeon', 0, 21, 1, 2);
        $groupdiscount = getGroupDiscount($pdo, $xxx);


        var_dump($groupdiscount);


        //load the view
        require 'View/homepage.php';
    }
}