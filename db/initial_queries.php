
<?php

$reviews_table = "CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `message` text,
  `allowed` tinyint(1) DEFAULT '0',
  `answered` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

$answers_table = "CREATE TABLE IF NOT EXISTS `answers` (
`id` int NOT NULL AUTO_INCREMENT,
`message` text,
`review` int,
PRIMARY KEY (`id`),
FOREIGN KEY (`review`) REFERENCES reviews (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";


///////////////////set administrator if he not exists
$admins_table= "CREATE TABLE IF NOT EXISTS admins
(
	id int PRIMARY KEY AUTO_INCREMENT,
	login varchar(20) UNIQUE KEY,
	password varchar(255)
)
";

$admins_row = 
"INSERT INTO admins (login,password) SELECT '$ADMIN', '$PASSWORD' WHERE NOT EXISTS(SELECT * FROM admins WHERE login= '$ADMIN' AND password = '$PASSWORD')";
 ?>





