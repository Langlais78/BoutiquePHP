<?php
require_once 'inc/init.inc.php';

// Si l' internaut N EST PA SCONNECTER  sur le site, il n'a rien à faire sur la page profil, on le redirige vers la page connexion.php
if(!connect())
{
    header('location: connexion.php');
}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';
?>

<!-- 
    exo : tenter d' afficher Bonjour 'pseudo' sur la page web en passant par la session de l'utilisateur
-->

<h1 class="text-center my-5"><span class="text-success">Bon</span><span class="text-warning">jour</span> <span class="text-danger"><?php echo $_SESSION['user']['pseudo']; ?></span></h1>

    <div class="col-md-5 mx-auto card shadow-sm mb-5">
        <h5 class="card-header text-center">Vos données personnelle</h5>
        <div class="card-body">
            <?php 
            foreach($_SESSION['user'] as $key => $value): 
                if($key != 'id_user' && $key != 'sexe' && $key != 'statut'): 
            ?>

            <p class="card-text d-flex justify-content-between">
                <strong><?= ucfirst($key);?></strong>
                <span><?= $value ?>
            </p>

            <?php 
            endif; 
            endforeach; 
            ?>
            <a href="" class="btn btn-dark">Envoyer !</a>
        </div>
    </div>

<?php   
require_once 'inc/footer.inc.php';
