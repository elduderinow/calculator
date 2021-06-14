<?php
declare(strict_types=1);

//include all your model files here
require 'Model/pdo_SQL.php';
require 'Model/Customer.php';


//include all your controllers here
require 'Controller/HomepageController.php';
$controller = new HomepageController();
$controller->render($_GET, $_POST);

