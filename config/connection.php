<?php

  $host = "127.0.0.1";
  $dbname = "contacts";
  $user = "root";
  $pass = "senha-password";
 

  try {

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    // Ativar o modo de erros
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
  } catch(PDOException $e) {
    // erro na conexão
    $error = $e->getMessage();
    echo "Erro: $error";
  }