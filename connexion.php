<?php
require_once 'inc/init.inc.php';

 // Si l' internaute est authentifié sur le site, il n' a rien a faire sur la page inscription, il sera rediriger vers la page profil
 if(connect())
 {
     header('location: profil.php');
 }

// si l' indice 'action' en definit dans l' URL et qu' il a pour valeur 'deconnexion', cela veut dire que l'internaute a cliqué sur le lien de deconnexion et par consequent qu'il a envoyé dasn l'url 'action=deconnexion'
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    // on vide le tableau ['user'] dans la session lorsque l'internaute clique sur le lien deconnexion
    unset($_SESSION['user']);
}

//echo '<pre>'; print_r($_SESSION); echo '</pre>';

if(isset($_POST['pseudo_email'], $_POST['password']))
{
    // Afin de controler si le pseudo ou email est connu en BDD nous executons une requete de selection 
    // SELECTIONNE TOUT EN BDD A CONDITION QUE le pseudo OU l' emeil SOIT EGAL au colonnes pseudo/email de la BDD
    $veriCredentials = $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo OR email = :email");
    $veriCredentials->bindValue(':pseudo',$_POST['pseudo_email'], PDO::PARAM_STR);
    $veriCredentials->bindValue(':email',$_POST['pseudo_email'], PDO::PARAM_STR);
    $veriCredentials->execute();

    // rowCount() : retourne le nombre de resultat suite à la requete SQL
    // Si la condition IF retourne TRUE, cela veut dire que le pseudo/email saisi dans le formulaire correspond à une ligne de résultat de la BDD, alors on entre dans le IF
    if($veriCredentials->rowCount())
    {
        // On entre dans la condition IF seulement dans le cas ou l'internaute a saisi un email/pseudo connu en BDD        
        //echo 'Pseudo / email existant en BDD';

        // On execute fecth() sur 'lobjet PDOStatement afin de recuperer sous forme de tableau les information en BDD de l'utilisateur
        $user = $veriCredentials->fetch(PDO::FETCH_ASSOC);
        //echo '<pre>'; print_r($user); echo '</pre>';

            // comparaison des mots de passe en clair :
            //if($_POST['password'] == $user['password'])

            // Si le mot de passe sont crypté en BDD, nous pouvons les verifier avec la fonction: 
            // password_verify() : fonction predefinies permettant de comparer une clé de hachage (mot de passe crypté en BDD) à une chaine de caracteres
            // i le mot de passe saisi dans le formulaire correspond a celui stocké dans la BDD , on entre dans la condition IF
            if(password_verify($_POST['password'], $user['password']))
            {
                // On entre dans la condition seulement dans le cas ou l'internaute a saisi le bon email/pseudo et le bon mot de passe, donc il a saisi les bon identifiants d' authentification
                //echo 'mot de passe valide';

                foreach($user as $key => $value)
                {
                    if($key != 'password')
                        $_SESSION['user'][$key] = $value;
                }
                // Une fois l'internaute authentifié et ses données dans son fichier de session, on le redirige vers sa page profil
                header('location: profil.php');
            }
            else
            {
                $error =  "Identifiant invalide.";
            }

    }
    else// Sinon le pseud-email saisi dans le formulaire ne retourne aucun resultat de la BDD, on entre dans la condition ELSE
    {       

        $error =  "Identifiant invalide.";
    }
}

require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';

?>

    <?php if(isset($_SESSION['validation_inscription'])): ?>
        <p class="bg-success col-md-3 mx-auto p-3 text-center text-white mt-3"><?= $_SESSION['validation_inscription']; ?></p>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <p class="bg-danger col-md-5 mx-auto p-3 text-center text-white mt-3"><?= $error; ?></p>
    <?php endif; ?>

            <h1 class="text-center my-5">Identifiez-vous</h1>

            <form action="" method="post" class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4 mx-auto">
                <div class="mb-3">
                    <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
                    <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
                </div>
                <div>
                    <p class="text-end mb-0"><a href="inscription.php" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
                    <p class="text-end m-0 p-0"><a href="" class="alert-link text-dark">Mot de passe oublié ?</a></p>
                </div>
                <input type="submit" name="submit" value="Continuer" class="btn btn-dark">
            </form>

 <?php
// On supprimme l'indice 'validation_inscription' dans la session juste après l' avoir afficher sur la page web
unset($_SESSION['validation_inscription']);
require_once 'inc/footer.inc.php';