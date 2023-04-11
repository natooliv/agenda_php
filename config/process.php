<?php

include_once("connection.php");
include_once("url.php");

$data = $_POST;

// MODIFICAÇÕES NO BANCO
if(!empty($data)) {

  // Criar contato
  if($data["type"] === "create") {

    $name = $data["name"];
    $phone = $data["phone"];
    $observations = $data["observations"];

    $photo = null;
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
      $EXTENSAO = pathinfo($_FILES["photo"]['name'], PATHINFO_EXTENSION);
      $NOME_FOTO= './public/' . sha1(uniqid().$_FILES['photo']['name']). "." . $EXTENSAO;
      if (!is_dir("./public")) {
       mkdir("./public", 0755, true);
   }
      move_uploaded_file($_FILES['photo']['tmp_name'], $NOME_FOTO);
      $photo = $NOME_FOTO;
     
      
    }
    $query = "INSERT INTO contacts (name, phone, observations, photo) VALUES (:name, :phone, :observations, :photo)";
   
    $stmt = $conn->prepare($query);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":observations", $observations);
    $stmt->bindParam(":photo", $photo , PDO::PARAM_LOB);
      

    try {

      $stmt->execute();
      $_SESSION["msg"] = "Contato criado com sucesso!";
      header("Location:" . $BASE_URL . "../index.php");
      exit();
  
    } catch(PDOException $e) {
      // erro na conexão
      $error = $e->getMessage();
      echo "Erro: $error";
    }
  }elseif($data["type"] === "edit") {

    $name = $data["name"];
    $phone = $data["phone"];
    $observations = $data["observations"];
    $id = $data["id"];
    $photo = null;
    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
      $EXTENSAO = pathinfo($_FILES["photo"]['name'], PATHINFO_EXTENSION);
      $NOME_FOTO= './public/' . sha1(uniqid().$_FILES['photo']['name']). "." . $EXTENSAO;
      if (!is_dir("./public")) {
       mkdir("./public", 0755, true);
   }
      move_uploaded_file($_FILES['photo']['tmp_name'], $NOME_FOTO);
      $photo = $NOME_FOTO;
     
      
    }

    $query = "UPDATE contacts 
              SET name = :name, phone = :phone, observations = :observations, photo = :photo
              WHERE id = :id";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":observations", $observations);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":photo", $photo, PDO::PARAM_LOB);

    try {

      $stmt->execute();
      $_SESSION["msg"] = "Contato atualizado com sucesso!";
      header("Location:" . $BASE_URL . "../index.php");
  
    } catch(PDOException $e) {
      // erro na conexão
      $error = $e->getMessage();
      echo "Erro: $error";
    }

  } else if($data["type"] === "delete") {

    $id = $data["id"];

    $query = "DELETE FROM contacts WHERE id = :id";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $id);
    
    try {

      $stmt->execute();
      $_SESSION["msg"] = "Contato removido com sucesso!";
      header("Location:" . $BASE_URL . "../index.php");
  
    } catch(PDOException $e) {
      // erro na conexão
      $error = $e->getMessage();
      echo "Erro: $error";
    }

  }



// SELEÇÃO DE DADOS
} else {
  
  $id;

  if(!empty($_GET)) {
    $id = $_GET["id"];
  }

  // Retorna o dado de um contato
  if(!empty($id)) {

    $query = "SELECT * FROM contacts WHERE id = :id";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(":id", $id);

    $stmt->execute();

    $contact = $stmt->fetch();

  } else {

    // Retorna todos os contatos
    $contacts = [];

    $query = "SELECT * FROM contacts";

    $stmt = $conn->prepare($query);

    $stmt->execute();
    
    $contacts = $stmt->fetchAll();

  }

}

// FECHAR CONEXÃO
$conn = null;