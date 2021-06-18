<?php
declare(strict_types=1);

class HomepageController {
    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST) {
        session_start();
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

        function getCustomers($pdo) {
            $handle = $pdo->prepare('SELECT * FROM customer');
            $handle->execute();
            $customers = $handle->fetchAll();
            return $customers;
        }

        function createCustomers($pdo) {
            $customers = getCustomers($pdo);
            $result = [];
            foreach ($customers as $customer) {
                $customerObj = new Customer($customer['firstname'], $customer['lastname'], (int)$customer['fixed_discount'], (int)$customer['variable_discount'], (int)$customer['id'], (int)$customer['group_id']);
                $result[] = $customerObj;
            }
            return $result;
        }

        //Customersgroup
        function getCustomersGroup($pdo) {
            $handle = $pdo->prepare('SELECT customer_group.id, name, parent_id, customer_group.fixed_discount, customer_group.variable_discount FROM customer_group LEFT JOIN customer ON customer.group_id = customer_group.id WHERE customer.group_id = :group_id');
            $handle->bindValue(':group_id', $_SESSION['customer-groupId'] ?: 2);
            $handle->execute();
            $customersGroup = $handle->fetch();
            return $customersGroup;
        }

        function createCustomersGroup($customerGroup) {
            $customGroup = new CustomerGroup((int)$customerGroup['id'], $customerGroup['name'], (int)$customerGroup['parent_id'], (int)$customerGroup['fixed_discount'], (int)$customerGroup['variable_discount']);
            return $customGroup;
        }

        function addSubGroups($pdo, $customer, $id) {
            if ($id === 0) {
                return;
            }

            $handle = $pdo->prepare('SELECT cg2.id, cg2.name, cg2.parent_id, cg2.fixed_discount, cg2.variable_discount FROM calculator.customer_group cg1 LEFT JOIN calculator.customer_group as cg2 ON cg1.parent_id = cg2.id WHERE cg1.parent_id = :id;');
            $handle->bindValue(':id', $id);
            $handle->execute();
            $customerGroupData = $handle->fetch();
            $customerGroup = createCustomersGroup($customerGroupData);
            $customer->setGroup($customerGroup);
            addSubGroups($pdo, $customer, $customerGroup->getParentId());
        }

        function getCompleteCustomerGroups($pdo, $customer) {
            $customerGroupData = getCustomersGroup($pdo);
            $customerGroup = createCustomersGroup($customerGroupData);
            $customer->setGroup($customerGroup);

            addSubGroups($pdo, $customer, $customerGroup->getParentId());
        }

        function findCustomer($customer) {
            if ($customer->getId() === $_SESSION['customer-id']) {
                return $customer;
            }
        }

        function findProduct($product) {
            if ($product->getId() == $_GET['id']) {
                return $product;
            }
        }

        function getCheckout($products) {
            $product = array_filter($products, 'findProduct');
            $product = reset($product);
            return $product;
        }

        function calcTotalBasket($products) {
            foreach ($products as $product) {
                $subSumArr[] = $product->getPrice();
            }
            return Array_sum($subSumArr);
        }

        function calcTotalAmount($products) {
            foreach ($products as $product) {
                $subSumArr[] = $product->getPrice();
            }
            return Array_sum($subSumArr);
        }

        //compare group fixed and variable => highest VALUE of customergroup
        function checkForHighestVariableCustomerGroup($totalPrice, $fixedValueGroup, $variableDiscountGroup) {
            if ($variableDiscountGroup) {
                $variableValueGroup = $totalPrice * ($variableDiscountGroup / 100);
            }

            $highestValue = max($fixedValueGroup, $variableValueGroup);
            return $highestValue == $variableValueGroup ? true : false;
        }

        //compare customer and group discounts (only if there are variable discounts)
        function compareVariableDiscountsCustomerAndGroup($variableDiscountCustomer, $variableDiscountCustomerGroup) {
            $highestVariableDiscount = max($variableDiscountCustomer, $variableDiscountCustomerGroup);
            return $highestVariableDiscount;
        }

        //calculate total price after discounts
        function getTotalPrice($totalPrice, $fixedDiscountCustomer, $fixedDiscountCustomerGroup, $variableDiscount) {
            $totalPrice -= $fixedDiscountCustomer;
            $totalPrice -= $fixedDiscountCustomerGroup;
            if ($variableDiscount) {
                $totalPrice *= (1 - ($variableDiscount / 100));
            }

            return $totalPrice;
        }

        // Run functions
        $products = createProducts($pdo);
        $customers = createCustomers($pdo);
        $checkoutProducts = [];
        $finalPrice = 0;

        if (isset($_POST['customer-id'])) {
            $_GET = [];
            $customerPost = json_decode($_POST['customer-id'], true);
            $_SESSION['customer-id'] = $customerPost['id'];
            $_SESSION['customer-groupId'] = $customerPost['groupId'];
            $customer = array_filter($customers, 'findCustomer');
            $customer = reset($customer);
            getCompleteCustomerGroups($pdo, $customer);

            // Calculate total price in basket
            $checkoutProducts = $_SESSION['checkout'];
            $totalBasket = calcTotalBasket($checkoutProducts);

            // Calculate total price with discounts for customer
            $customerFixedGroup = $customer->calcFixedDiscounts();
            $customerFixed = $customer->getFixedDiscount();
            $customerVarGroup = $customer->calcBiggestVariableDiscount();
            $customerVar = $customer->getVariableDiscount();

            if (checkForHighestVariableCustomerGroup($totalBasket, $customerFixedGroup, $customerVarGroup)) {
                $bestVarDiscount = compareVariableDiscountsCustomerAndGroup($customerVar, $customerVarGroup);
            } else {
                $bestVarDiscount = $customerVar;
            }

            $finalPrice = getTotalPrice($totalBasket, $customerFixed, $customerFixedGroup, $bestVarDiscount);
            $finalPrice = $finalPrice <= 0 ? 0 : number_format($finalPrice, 2, ',', '');
            $_SESSION['finalPrice'] = $finalPrice;
        }

        function dump(){
            var_dump((int)$_GET['id']);
            var_dump($_SESSION['checkout']);
            echo "----------------------";
        }

        if (isset($_GET['id']) && isset($_GET['button'])) {
            if (isset($_SESSION['finalPrice'])) {
                $finalPrice = $_SESSION['finalPrice'];
            }
            if ($_GET['button'] == 'Add') {
                if (isset($_SESSION['checkout'])) {
                    $checkoutProducts = $_SESSION['checkout'];
                }
                $checkoutProducts[] = getCheckout($products);
                $_SESSION['checkout'] = $checkoutProducts;
            } else {
                $checkoutProducts = $_SESSION['checkout'];
                unset($checkoutProducts[(int)$_GET['id']]);
                $_SESSION['checkout'] = array_values($checkoutProducts);
                $checkoutProducts = $_SESSION['checkout'];
            }
        }

        //load the view
        require 'View/homepage.php';
    }
}