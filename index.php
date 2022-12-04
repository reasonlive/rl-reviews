<?php

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);       
ini_set('error_reporting', E_ALL);

use Reviews\Models\Users\User;
use Reviews\Models\Users\Admin;

use Reviews\Classes\Database;
use Reviews\Classes\DatabaseTableConstructor;

$connection_settings = array(
	'dbname'   => 'test',
  'host'     => '127.0.0.1',
  'username' => 'root',
  'password' => 'mitoteam',
);

Database::setConnectionSettings($connection_settings);

DatabaseTableConstructor::create(User::class);
Database::truncate();

DatabaseTableConstructor::create(Admin::class);


//include('./pages/_main.php');