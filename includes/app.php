<?php 
use Dotenv\Dotenv;
require 'funciones.php';
require 'database.php';
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);