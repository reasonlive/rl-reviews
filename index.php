
<?php
ini_set('display_errors', 1);       
ini_set('error_reporting', E_ALL);  
                        


require "./config.php";
require "./db/index.php";
require "./db/initial_queries.php";

$db = new Database($HOST,$LOGIN,$PASS,$DBNAME);

//initiates db tables
$db->execute($reviews_table);
$db->execute($answers_table);
$db->execute($admins_table);
$db->execute($admins_row);

include('./pages/_main.php');

?>