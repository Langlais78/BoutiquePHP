<?php
    require_once 'inc/init.inc.php';

    // Si l' internaute est authentifié sur le site, il n' a rien a faire sur la page inscription, il sera rediriger vers la page profil
    if(connect())
    {
        header('location: profil.php');
    }
  
    // 1. Controler que l' on receptionne bien toutes les données saisie dans le formulaire
    echo '<pre>'; print_r($_POST); echo '</pre>';

    if(isset($_POST['sexe'], $_POST['pseudo'], $_POST['password'], $_POST['confirm_password'], $_POST['email'], $_POST['telephone'], $_POST['prenom'], $_POST['nom'], $_POST['adresse'], $_POST['ville'], $_POST['code_postal'],))
    {              
        // 2. faites en sorte d'informer l'internaute si le pseudo est deja disponible (deja enregisteé en BDD) SELECT + ROWCOUNT

        //* SELECTIONNE TOUT dans la BDD A CONDITION que la colonne 'pseudo' soit egal au pseudo saisi dans le formulaire
        $verifPseudo = $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
        $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $verifPseudo->execute();

        $border = 'border border-danger';

        if($verifPseudo->rowCount())
        {
            
            $errorPseudo = "Nom d'utilisateur deja existant. Merci d'en saisir un nouveau.";

            $error = true;
        }
        elseif(empty($_POST['pseudo']))
        {
            $errorPseudo = "Merci de saisir un nom d'utilisateur";

            $error = true;
        }

        // 3. faites en sorte d'informer l'internaute si l'email est deja existant en BDD
        // | faites en sorte d'informer l'internaute si le pseudo est indisponible
        $verifMail = $bdd->prepare("SELECT * FROM user WHERE email = :email");
        $verifMail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $verifMail->execute();

        $border = 'border border-danger';

        if($verifMail->rowCount())
        {
            
            $errorMail = "Email deja existant. Merci d'en saisir un nouveau.";
            $error = true;
        }
        elseif(empty($_POST['email']))
        {
            $errorMail = "Merci de saisir un Email";
            $error = true;
        }
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            $errorMail = "Merci de saisir un Email valide ( ex : maurice@gmail.com )";
            $error = true;
        }

       
        // 4. faites en sorte d'informer l'internaute si le mot de passe ne correspont pas
        $verifPassword = $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
        $verifPassword->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $verifPassword->execute();

        $border = 'border border-danger';

        if($_POST['password'] != $_POST['confirm_password'])
        {            
            $errorPassword = "Les mots de passe ne correspond pas";
            $error = true;
        }


        // 5. Faites en sorte d' informer l'internaute si les politiques de confidentialités ne sont pas cochés
        if(empty($_POST['gridCheck']))
        {
            $errorCondi = "Veuillez accepter les politiques de confidentialités";
            $error = true;
        }

        // 6. réaliser le traitement PHP + SQL afin d' insérer un nouveau membre dans la BDD à la validation du formulaire si l'internaute a correctement rempli le formulaire (PREPARE + BINDVALUE + EXECUTE)

        if(!isset($error))
        {

            // On ne conserve jamais le mot de passe en clair dans la BDD
            // password_hash : fonction predefinie permettant de créer un e clé de hashage pour le mot de passe de la BDD
            //arguments : Password_hash( mot de passe, TYPE DE CRYPTAGE )
            $_POST['password'] = password_hash($_POST['password'],PASSWORD_BCRYPT);

            $insert = $bdd->prepare("INSERT INTO user (pseudo, password, nom, prenom, email, telephone, sexe, ville, code_postal, adresse) VALUES ( :pseudo, :password, :nom, :prenom, :email, :telephone, :sexe, :ville, :code_postal, :adresse)");
            $insert->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $insert->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
            $insert->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $insert->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $insert->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $insert->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_STR);
            $insert->bindValue(':sexe', $_POST['sexe'], PDO::PARAM_STR);
            $insert->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $insert->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
            $insert->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            $insert->execute();

            // On stock dans le fichier de session de l'utilisateur un message de validation
            $_SESSION['validation_inscription'] = "Felicitation ! Vous êtes maintenant inscrit ! Vous pouvez dès à present vous connectez !";

            // Après l'insertion, on redirige l'internaute vers la page de connexion
            header('location: connexion.php');

        }
    
    }

require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';
?>

            <h1 class="text-center my-5">Créer votre compte</h1>

            <?php if(isset($errorCondi)): ?>
            <p class="bg-danger col-md-4 mx-auto p-3 text-center text-white"><?= $errorCondi ?></p>
            <?php endif; ?>

            <form method="post" class="row g-3 mb-5">
                <div class="col-md-6">
                    <label for="sexe" class="form-label">Civilité</label>
                    <select class="form-control" id="sexe" name="sexe">
                        <option value="femme">Femme</option>
                        <option value="homme">Homme</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="pseudo" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control <?php if(isset($errorPseudo)) echo $border; ?>" id="pseudo" name="pseudo" value="<?php if(isset($_POST['prenom'])) echo $_POST['pseudo']; ?>">

                    <?php if(isset($errorPseudo)): ?>
                    <small class="fst-italic text-danger"><?=  $errorPseudo ?></small>
                    <?php endif; ?>

                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control <?php if(isset($errorPassword)) echo $border; ?>" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>" >
                    <?php if(isset($errorPassword)): ?>
                    <small class="fst-italic text-danger"><?=  $errorPassword ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
                    <input type="password" class="form-control <?php if(isset($errorPassword)) echo $border; ?>" id="confirm_password" name="confirm_password">
                    <?php if(isset($errorPassword)): ?>
                    <small class="fst-italic text-danger"><?= $errorPassword ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control <?php if(isset($errorMail)) echo $border; ?>" id="email" name="email" placeholder="Saisir votre adresse email">
                    <?php if(isset($errorMail)): ?>
                    <small class="fst-italic text-danger"><?=  $errorMail ?></small>
                    <?php endif; ?>

                </div>
                <div class="col-6">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Saisir votre adresse téléphone">
                </div>
                <div class="col-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir votre prénom">
                </div>
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom">
                </div>
                <div class="col-md-6">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Saisir votre adresse">
                </div>
                <div class="col-md-4">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" placeholder="Saisir votre ville">
                </div>
                <div class="col-md-2">
                    <label for="code_postal" class="form-label">Code postal</label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck" name="gridCheck" >
                        <label class="form-check-label" for="gridCheck" >
                        Accepter les <a href="" class="alert-link text-dark">politiques de confidentialité</a>  
                        </label>                   
                    </div>                   
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Continuer</button>
                </div>
            </form>

<?php 
require_once "inc/footer.inc.php";