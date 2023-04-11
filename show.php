<?php 
  include_once("templates/header.php");
?>
  <div class="container" id="view-contact-container"> 
    <?php include_once("templates/backbtn.html"); ?>
    <h1 id="main-title"><?= $contact["name"] ?></h1>
    <p class="bold">Telefone:</p>
    <p><?= $contact["phone"] ?></p>
    <p class="bold">Observações:</p>
    <p><?= $contact["observations"] ?></p>
    <p class="bold">Foto:</p>
    <?php if (!empty($contact["photo"])): ?>
      <img src="<?= "./config/". $contact["photo"] ?>" alt="<?= $contact["name"] ?>" width="150" height="150">
    <?php endif; ?>
  </div>
<?php
  include_once("templates/footer.php");
?>
