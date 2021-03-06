<?php
declare(strict_types=1);

//include all your model files here
require 'Model/Pdo.php';
require 'Model/Customer.php';
require 'Model/CustomerGroup.php';
require 'Model/Product.php';


//include all your controllers here
require 'Controller/HomepageController.php';
$controller = new HomepageController();
$controller->render($_GET, $_POST);
