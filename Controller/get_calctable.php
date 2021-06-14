<?php
//@todo Invalid query?
$handle = $pdo->prepare('SELECT product.name, product.price FROM product ORDER BY product.name');
$handle->execute();
$products = $handle->fetchAll();

$handle = $pdo->prepare('SELECT customer.firstname, customer.lastname, customer.id FROM customer ORDER BY customer.firstname');
$handle->execute();
$customers = $handle->fetchAll();

