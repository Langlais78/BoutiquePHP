<?php
require_once 'inc/init.inc.php';

// Si l'indice 'id_article' est definit dans l'URL et que sa valeur est differente de vide, on entre dans le IF et l'on execute la requete de selection
if(isset($_GET['id_article']) && !empty($_GET['id_article']))
{
    $data = $bdd->prepare("SELECT * FROM article WHERE id_article = :id_article");
    $data->bindValue(':id_article', $_GET['id_article'], PDO::PARAM_INT);
    $data->execute();

    // Si la requete de selection retourne 1  resultat, cela veut dire que l' article est connu en BDD
    if($data->rowCount())
    {
        $produit = $data->fetch(PDO::FETCH_ASSOC);
        
        //echo '<pre>'; print_r($produit); echo '</pre>';
        //echo "article connu";
    }
    else// Sinon l'article ne retourne aucun resultat, l'article n'existe pas en BDD
    {
        // On redirige l'internaute vers la boutique si l'id_article passé dasns l'URL n'existe pas 
        header('location : boutique.php');
        //echo "article inconnu";
    }
}
else// Sinon l'indice article n'est pas definit ou n'a pas de valeur, l'internaute a modifier les parametre de l'URL, on le redirige vers la page boutique
{
    header('location : boutique.php');
}

require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';
?>

            <h1 class="text-center my-5">Détails de l'article</h1>

            <div class="row mb-5">
                <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

                    <a href="<?= $produit['photo'] ?>" data-lightbox="image" data-title="<?= $produit['titre'] ?>" data-alt="<?= $produit['titre'] ?>" class="d-flex align-items-center"><img src="<?= $produit['photo'] ?>" class="img-produit-fiche" alt="<?= $produit['titre'] ?>"></a>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
                        <h5 class="card-title text-center fw-bold my-3"><?= ucfirst($produit['titre']) ?></h5>
                        <p class="card-text"><?= $produit['description'] ?></p>
                        <p class="card-text fw-bold">Taille : <?= ucfirst($produit['taille']) ?></p>
                        <p class="card-text fw-bold">Couleur : <?= $produit['couleur'] ?></p>
                        <p class="card-text fw-bold"><?= $produit['prix'] ?> €</p>
                        <p class="card-text">
                        <?php if($produit['stock'] > 0): ?>

                            <?php if($produit['stock'] <= 10): ?>
                            <p class="text-success fs-5 fst-italic fw-bold"> ' Dépechez-vous il ne reste plus que <?= $produit['stock'] ?> articles '</p>
                            <?php else: ?>
                                <p class="text-success fs-5 fst-italic fw-bold"> ' En stock ! '</p>
                            <?php endif; ?>

                            <form method="post" action="panier.php" class="row g-3">
                            <!-- A la validation du formulaire, on redirige l'internaute vers la page panier (attribut 'action') et les données saisies dans le formulaire seront accessible sur la page panier.php (quantité + id_article) -->
                            <input type="hidden" id="id_article" name="id_article" value="<?= $produit['id_article']; ?>">
                                <div class="col-12 col-sm-7 col-md-4 col-lg-3 col-xl-3">
                                    <label class="visually-hidden" for="quantite">Quantité</label>
                                    <select class="form-select" id="quantite" name="quantite">
                                        <option selected>Choisir une quantité...</option>

                                        <?php for($i = 1; $i <= $produit['stock']; $i++): ?>

                                        <option value="<?= $i ?>"><?= $i ?></option>

                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="col-sm">
                                    <input type="submit" class="btn btn-dark" value="Ajouter au panier">
                                </div>                                

                                <?php else: ?>

                                <p class="text-danger fs-5"> ' Rupture de stock ' </p>

                                </form>                           

                        <?php endif; ?>

                            
                        </p>
                    </div>
                </div>
                <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
            </div>
<?php
require_once 'inc/footer.inc.php';
