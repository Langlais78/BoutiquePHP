<?php
require_once '../inc/init.inc.php';

// Si l'internaute N'EST PAS admin ou n'est peut etre meme pas authentifié, il n'a rien a faire sur cette page, on le redirige vers la page connexion
if(!adminConnect())
{
  header('location: ' .URL . 'connexion.php');
}

// SUPPRESSION PRODUIT
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
  // si l'indice 'action' est definit et que sa valeur est differente de vide, alors on entre dans la condition IF et on execute la requete de suppression
  if(isset($_GET['id_article']) && !empty($_GET['id_article']))
  {
    $deleteProduct = $bdd->prepare("DELETE FROM article WHERE id_article = :id_article");
    $deleteProduct->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_INT);
    $deleteProduct->execute();

    // on redefinit la valeur de l' action URL afin d'entrée dans la condition IF de l'affichage des articles
    $_GET['action'] = 'affichage';

    $msg = "L'article n° <strong>$_GET[id_article]</strong> a été supprimé avec succès";
  }
  else // sinon on redirige l'internaute vers l'affichage des article
  {
    header('location:' . URL . 'admin/gestion_boutique.php?action=affichage');
  }
  // $delete = $bdd->prepare("DELETE FROM article WHERE id_article = :id_article");
  // $delete->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_STR);
  // $delete->execute();
}

// SELECTION ARTICLE POUR MODIFICATION EN BDD
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
  if(isset($_GET['id_article']) && !empty($_GET['id_article']))
  {
    $produitModif = $bdd->prepare("SELECT * FROM article WHERE id_article = :id_article");
    $produitModif->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_STR);
    $produitModif->execute();

    // si la requete retourne au moins 1 resultat, cela veut dire que l' id du produit est connu en BDD, on entre dans le IF
    if($produitModif->rowCount())
    {
      // On recupere sous forme de tableau ARRAY toute les donnée du produit a modifier
      $produit = $produitModif->fetch(PDO::FETCH_ASSOC);
      //echo '<pre style="margin-left: 320px;">'; print_r($produit); echo '</pre>';

      // On stock chaque donnée du produit a modifier dans une variable 
      $id_produit = (isset($produit['id_article'])) ? $produit['id_article'] : '';
      $reference = (isset($produit['reference'])) ? $produit['reference'] : '';
      $categorie = (isset($produit['categorie'])) ? $produit['categorie'] : '';
      $titre = (isset($produit['titre'])) ? $produit['titre'] : '';
      $description = (isset($produit['description'])) ? $produit['description'] : '';
      $couleur = (isset($produit['couleur'])) ? $produit['couleur'] : '';
      $taille = (isset($produit['taille'])) ? $produit['taille'] : '';
      $sexe = (isset($produit['sexe'])) ? $produit['sexe'] : '';
      $photo = (isset($produit['photo'])) ? $produit['photo'] : '';
      $prix = (isset($produit['prix'])) ? $produit['prix'] : '';
      $stock = (isset($produit['stock'])) ? $produit['stock'] : '';

      // echo '<pre style="margin-left: 320px;">'; print_r($id_produit); echo '</pre>';
    }
    else // Si l'id dans l'URL n'est pas connu en BDD, on est redirigé vers l'affichage article
    {
      header('location:' . URL . 'admin/gestion_boutique.php?action=affichage');
    }
  }
  else
  {
    header('location:' . URL . 'admin/gestion_boutique.php?action=affichage');
  }
}

if(isset($_POST['reference'], $_POST['categorie'], $_POST['titre'], $_POST['description'], $_POST['couleur'], $_POST['taille'], $_POST['sexe'], $_POST['prix'], $_POST['stock']))
{
  // Traitement de fichier uplodé
  $photoBdd = '';

  if(isset($_GET['action']) && $_GET['action'] == 'modification')
  {
    // En cas de modification, si nous changeons pas l'image, le champ type 'file' n'a pas d'attribut 'value', donc nous aurons un champ vide dans la BDD
    // Si nous ne changeons pas l'image, nous recupererons l'URL de l'image existante de l'article en BDD afin de l'affécté a la variable $photoBdd et de la reé-insérer en BDD
    $photoBdd = $_POST['photo_actuelle'];
  }

  if(!empty($_FILES['photo']['name']))
  {
    $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name'];

    // On definit l'URL de l'image qui sera stockée en bdd
    $photoBdd = URL . "assets/img/$nomPhoto";

    // On definit le chemin physique de l'image qui sera copiée dans le dossier
    $photoDossier = RACINE_SITE . "assets/img/$nomPhoto"; 

    //copy() fonction predefinit permetttant de copier un fichier uploadé dans un dossier
    // arguments :
    // 1. le nom temporaire de l'imagepiochée dans $_FILES
    // 2. le chemin physique de l' image sur le serveur
    copy($_FILES['photo']['tmp_name'], $photoDossier);
  }

if(isset($_GET['action']) && $_GET['action'] == 'ajout')
{
  $data = $bdd->prepare("INSERT INTO article (reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock) VALUES ( :reference, :categorie, :titre, :description, :couleur, :taille, :sexe, :photo, :prix, :stock)");

  // Apres l'insertion, on redirige l'internaute vers l'affichage des produit en modifiant la valeur de l'indice dans l'URL
  $_GET['action'] = 'affichage';

  $msg = "L'article <strong>$_POST[titre]</strong> réfèrence <strong>$_POST[reference]</strong> a bien été enregistré.";
}
else
{
  // Requete SQL de modification en fonction de l'id_article transmit dans l'URL
  $data = $bdd->prepare("UPDATE article SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, sexe = :sexe, photo = :photo, prix = :prix, stock = :stock WHERE id_article = :id_article");

  $data->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_INT);

  $_GET['action'] = 'affichage';

  $msg = "L'article <strong>$_POST[titre]</strong> réfèrence <strong>$_POST[reference]</strong> a bien été modifié.";
}

  $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
  $data->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
  $data->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
  $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
  $data->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
  $data->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
  $data->bindValue(':sexe', $_POST['sexe'], PDO::PARAM_STR);
  $data->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
  $data->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
  $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_STR);
  $data->execute();

}

require_once '../inc/admin_inc/header.inc.php';
require_once '../inc/admin_inc/nav.inc.php';
// // TEST IMAGE
// // ref-nom_de_l'image.jpg
// echo $nomPhoto . '<br>';
// // http://localhost/PHP/10-boutique/assets/img/gnfgf-photo.jpg
// echo $photoBdd . '<br>';
// // C:/xampp/htdocs/PHP/10-boutique/assets/img/gnfgf-photo.jpg
// echo $photoDossier . '<br>';

?>

<!-- 
    Exo :
    1. Réaliser le traitement PHP + SQL permettant d'afficher l'ensemble de la table article sous forme de tableau HTML (query + select + fetch)
    2. Prévoir un lien modification / suppression pour chaque produit
    3. Faites en sorte d'afficher une partie de la description (50 caractères) si la taille de la description est supérieur a 50 caractères
    4. l'image doit apparaitre dans le tableau et non l'url de l'image
    5. Au dessus du tableau, afficher le nombre d'articles enregistrés en BDD
 -->

<?php

// $table = $bdd->query("SELECT * FROM article");

// echo "<table class='table table-bordered text-center'><tr>";
// for($i =0; $i < $table->columnCount(); $i++)
// {
//     $colonne = $table->getColumnMeta($i);

//     echo "<th>$colonne[name]</th>";
// }

// echo'</tr>';

// while($article = $table->fetch(PDO::FETCH_ASSOC))
// {
//     //echo '<pre>'; print_r($article); echo '</pre>';

//     echo '<tr>';
//         foreach($article as $value)
//         {
//             if($article != $_FILES['photo'])
//             {
//               echo "<td>$value</td>";
//             }
//             else
//             {
//               echo "<td><img src=''></td>";
//             }           
//         }
//     echo '</tr>';
// }
// echo '</table>';

?>

<div class="d-flex flex-row col-md-12 justify-content-between mx-auto my-5">
  <a href="?action=affichage" class="btn btn-outline-primary col-md-3 mb-2">AFFICHAGE DES ARTICLES</a>
  <a href="?action=ajout" class="btn btn-outline-info col-md-3 mb-2">AJOUTER UN ARTICLE</a>
  <a href="" class="btn btn-outline-success col-md-3 mb-2">STATISTIQUES</a>
</div>

<!-- Si l'indice 'action' est definit dans l'URL et qu'il a pour valeur 'affichage' , cela cela veut dire que l'internaute à cliquer sur le lien 'AFFICHAGE DES ARTICLES' et par consequent transmit dans l' URL 'action=affichage' -->
<?php if(isset($_GET['action'])  && $_GET['action'] == 'affichage'): ?>

<h1 class="text-center my-4">Affichage des  article</h1>

<?php

//echo '<pre>'; print_r($_POST); echo '</pre>';

// les donnees d'un fichier uploader sont accessible en PHP via la superglobale $_FILES
//echo '<pre>'; print_r($_FILES); echo '</pre>';

?>

<?php if(isset($msg)): ?>
        <p class="bg-success col-md-3 mx-auto p-3 text-center text-white mt-3"><?= $msg; ?></p>
<?php endif; ?>

<?php
$data = $bdd->query("SELECT * FROM article");
$articles = $data->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>'; print_r($articles); echo '</pre>';
?>

<p><span class="badge bg-success"><?=$data->rowCount(); ?></span>Articles enregistrés</p>

<div class="table-responsive">
<table id="table-backoffice" class="table table-bordered table-hover text-center">
  <thead>
    <tr class="table table-info">
      <?php foreach($articles[0] as $key => $value): ?>

        <th><?= strtoupper($key); ?></th>
      
      <?php endforeach; ?>
        <td>EDIT</td>
        <td>SUPP</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($articles as $tab): ?>

      <tr>
        <?php foreach($tab as $key => $value): ?>

          <?php if($key == 'photo'): ?>
            <td><img src="<?= $value; ?>" alt="<?= ['titre']; ?>" class="img-articles-bo"></td>
            <?php elseif($key == 'description' && iconv_strlen($value) > 50): ?>
              <td><?=substr($value, 0, 50); ?>[...]</td>
              
          <?php else: ?>
            <td><?= $value ?></td>
          <?php endif; ?>

        <?php endforeach; ?>
              <td><a href="?action=modification&id_article=<?= $tab['id_article'] ?>" class="btn btn-success"><i class="bi bi-pencil-square "></i></a></td>
              <td><a href="?action=suppression&id_article=<?= $tab['id_article'] ?>" class="btn btn-danger" onclick="return(confirm('Voulez-vous réellement supprimer cet article ?'))"><i class="bi bi-x-square"></i></a></td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>
</div>

<?php endif; ?>

<!-- Si l'indice 'ajout' est definit dans l'URL et qu'il a pour valeur 'ajout' , cela cela veut dire que l'internaute à cliquer sur le lien 'AJOUTER UN ARTICLES' et par consequent transmit dans l' URL 'action=ajout' -->
<?php if(isset($_GET['action'])  && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')):?>

  <!-- Exo : faites en sorte d'afficher dans le titre h1 'modification article' lorsque l'on clique sur le lien de modification et 'ajout article' lorsque l'on clique sur le lien pour ajouter un article-->

<?if(isset($_GET['action'])  && $_GET['action'] == 'ajout'):?>

  <h1 class="text-center my-4"><?=ucfirst($_GET['action'])?> d'un article</h1>;

<!-- Faites un formulaire HTML correspondant a la table 'article' (sauf id_article) -->
<!-- enctype="multipart/from-data" : attribut permettant de recuperer les information d'un fichier uploader via un formulaire -->
<form method="post" enctype="multipart/form-data" class="row g-3 mb-3">

  <div class="col-md-12">
      <label for="reference" clas="form-label">Référence :</label>
      <input type="text" id="reference" class="form-control" name="reference" value="<?php if(isset($reference)) echo $reference; ?>">
  </div>
  <div class="col-md-6">
      <label for="categorie" clas="form-label">Categorie :</label>
      <input type="text" id="categorie" class="form-control" name="categorie" value="<?php if(isset($categorie)) echo $categorie; ?>">
  </div>
  <div class="col-md-6">
      <label for="titre" clas="form-label">Titre :</label>
      <input type="text" id="titre" class="form-control" name="titre" value="<?php if(isset($titre)) echo $titre; ?>">
  </div>
  <div class="col-md-12">
      <label for="description" clas="form-label">Description :</label>
      <textarea type="text" id="description" class="form-control" name="description" cols="65" rows="8"> <?php if(isset($description)) echo $description; ?></textarea>
  </div>
  <div class="col-md-6">
      <label for="couleur" clas="form-label">Couleur :</label>
      <input type="text" id="couleur" class="form-control" name="couleur" value="<?php if(isset($couleur)) echo $couleur; ?>">
  </div>
  <div class="col-md-6">
      <label for="taille" clas="form-label">Taille :</label>
      <select name="taille" id="taille" class="form-select">
        <option value="s">S</option>

        <option value="m" <?php if(isset($taille) && $taille == 'm') echo 'selected'; ?>>M</option>

        <option value="l" <?php if(isset($taille) && $taille == 'l') echo 'selected'; ?>>L</option>

        <option value="xl" <?php if(isset($taille) && $taille == 'xl') echo 'selected'; ?>>XL</option>

      </select>
  </div>
  <div class="col-md-6">
      <label for="sexe" clas="form-label">Sexe :</label>
      <select name="sexe" id="sexe" class="form-select">
        <option value="homme">Homme</option>
        <option value="femme" <?php if(isset($sexe) && $sexe == 'femme') echo 'selected'; ?>>Femme</option>
        <option value="mixte" <?php if(isset($sexe) && $sexe == 'mixte') echo 'selected'; ?>>Mixte</option>
      </select>
  </div>
  <div class="col-md-6">
      <label for="photo" clas="form-label">Photo :</label>
      <input type="file" id="photo" class="form-control" name="photo">
  </div>

<?php if(isset($photo) && !empty($photo)): ?>

    <!-- on definit un champ 'caché' permettant de recuperer l'URL de l'image en BDD en cas de modification si nous ne souhaitons pas le modifier -->
    <input type="hidden" id="photo_actuelle" name="photo_actuelle" value="<?= $photo ?>">

  <div class="d-flex flex-column align-items-center">
      <small class='fst-italic'>Vous pouvez uploader une nouvelle image si vous souhaitez la modifier</small> 
      <img src="<?= $photo ?>" alt="" class="img-articles-bo"> 
  </div>
<?php endif; ?>

  <div class="col-md-6">
      <label for="prix" clas="form-label">Prix :</label>
      <input type="text" id="prix" class="form-control" name="prix" value="<?php if(isset($prix)) echo $prix; ?>">
  </div>
  <div class="col-md-6">
      <label for="stock" clas="form-label">Stock :</label>
      <input type="text" id="stock" class="form-control" name="stock" value="<?php if(isset($stock)) echo $stock; ?>">
  </div>
  <div class="col-md-12">
    <button type="submit" class="btn btn-secondary"><?=ucfirst($_GET['action'])?> produit</button>
  </div>
</form>

<?php endif;
require_once '../inc/admin_inc/footer.inc.php';