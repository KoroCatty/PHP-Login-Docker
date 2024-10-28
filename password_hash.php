<?php 

$pass = "secret";

// Hashing
$hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);


// Verifying
if (password_verify($pass, $hash)) {
  echo "Password matched";
} else {
  echo "Password not matched";
}