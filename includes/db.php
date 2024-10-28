<?php 

// 1. localhost 2. username 3. password 4. database name
$connection = new mysqli('db', 'root', 'root_password', 'login');

if ($connection->connect_error) {
  die('Could not connect: ' . $connection->connect_error);
} else {
  echo 'Connected successfully';
}


?>