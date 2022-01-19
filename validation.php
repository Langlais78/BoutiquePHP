<?php
require_once 'inc/init.inc.php';

if(!connect())
{
    header('location: connexion.php');
}
require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';
?>

<h1 class="text-center my-5 text-warning">FELICITATION</h1>

<p class="text-center"> Votre commande n° <strong class="text-success"><?= $_SESSION['num_commande']; ?></strong> a bien été validée !</p>

<p class="text-center">Un mail de confirmation a été envoyé.</p>

<?php
require_once 'inc/footer.inc.php';