<?php

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);       
ini_set('error_reporting', E_ALL);

use Reviews\Models\Users\User;
//require "./config.php";
//require "./db/index.php";
//require "./db/initial_queries.php";
var_dump(new User(['hello' => 'world']));
//$db = new Database($HOST,$LOGIN,$PASS,$DBNAME);

//initiates db tables
//$db->execute($reviews_table);
//$db->execute($answers_table);
//$db->execute($admins_table);
//$db->execute($admins_row);

//include('./pages/_main.php');