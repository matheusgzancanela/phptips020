<?php

use Source\Models\User;

require __DIR__ . "/vendor/autoload.php";


$user = new User();
$user->first_name = "Matheus";
$user->last_name = "Teste";
$user->email = "Teste@teste.com.br";
$user->password = "12345";

if (!$user->save()) {
  echo "<h3>Ooops: {$user->fail()->getMessage()}</h3>";
}

echo "<h2>Usuario:</h2>";
var_dump($user->data());
