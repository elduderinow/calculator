<?php
function openConnection(): PDO
{
    // No bugs in this function, just use the right credentials.
    $dbhost = "127.0.0.1";
    $dbuser = "becode";
    $dbpass = "becode";
    $db = "calculator";

    $driverOptions = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO('mysql:host=' . $dbhost . ';dbname=' . $db, $dbuser, $dbpass, $driverOptions);
}

$pdo = openConnection();

$handle = $pdo->prepare('SELECT product.name, product.price FROM product ORDER BY product.name');
$handle->execute();
$products = $handle->fetchAll();

$handle = $pdo->prepare('SELECT customer.firstname, customer.lastname, customer.id FROM customer ORDER BY customer.firstname');
$handle->execute();
$customers = $handle->fetchAll();

