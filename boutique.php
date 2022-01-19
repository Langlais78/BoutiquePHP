<?php
require_once 'inc/init.inc.php';
// Si l'indice 'cat' est definit dans l'URL et que sa valeur est differente de vide
// On entre  dans le IF si l'internaute click sur un lien de categorie
if(isset($_GET['cat']) && !empty($_GET['cat']))
{
    $r = $bdd->prepare("SELECT * FROM article WHERE categorie = :categorie");
    $r->bindValue(':categorie', $_GET['cat'], PDO::PARAM_STR);
    $r->execute();

    // Si la requete de selection ne retourne aucun resultat, cela veut dire que la categorie passer dans l'URL n'existe pas en BDD, on redirige l'internaute vers la page boutique.php
    if(!$r->rowCount())
    {
        header('location: boutique.php');
    }
}
else // Sinon si il n'y a pas de categorie dans l'URL, on selectionne l'enssemble de la table article
{
    $r = $bdd->query("SELECT * FROM article");
}


require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';
?>

            <h1 class="text-center my-5">Shopping</h1>

            <p class="my-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, labore. Dolor voluptatem nobis ea deleniti, sit possimus eligendi iure recusandae rem eius. Doloribus delectus quas, tempore rem laboriosam nesciunt pariatur velit, illum sint, necessitatibus ea eaque provident. Cupiditate alias repellat aliquid veniam quibusdam corrupti, non odit asperiores illo eligendi necessitatibus! Fugiat quo in provident minus ullam praesentium natus amet sequi delectus quia incidunt beatae rem, labore quisquam pariatur accusantium exercitationem enim suscipit consequatur dolorum animi commodi saepe? Eos quas, aliquid blanditiis officia ipsum natus ea. Porro officiis qui totam unde dignissimos nesciunt repudiandae possimus numquam pariatur placeat! Magnam et aperiam hic officiis? Veniam, laborum voluptate nemo, qui tempore voluptates sed at, suscipit facere sint totam eos beatae nam aperiam molestiae! Asperiores non officia cupiditate itaque sapiente fuga earum illo quibusdam? Adipisci quia aliquid laboriosam saepe, dignissimos eos expedita molestiae quaerat nisi quae ratione provident, optio ad. Recusandae iure hic culpa!</p>

            <!-- 
                Exo :
                1. Réaliser le traitement php + sql permettant de selectionner les catégories d'article distincte dans la BDD
                2. Afficher dynamiquement les catégories dans l'accordéon ci-dessous (boucle + fetch)
                3. Faites en sorte d'envoyer le nom de catégorie dans l'URL lorsque l'on clique sur le lien
            -->
           

            <div class="accordion col-12 col-sm-10 col-md-4 col-lg-3 col-xl-3 mx-auto my-5" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Catégories
                        </button>
                    </h2>

                    <?php
                        $data = $bdd->query("SELECT DISTINCT categorie FROM article");
                    ?>
                

                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                        <?php while($cat = $data->fetch(PDO::FETCH_ASSOC)): ?>

                        <p><a href="?cat=<?= $cat['categorie'] ?>" class="alert-link text-dark"><?= ucfirst($cat['categorie']); ?></a></p>

                        <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">

                <?php while($produit = $r->fetch(PDO::FETCH_ASSOC)): 
                //echo '<pre>'; print_r($produit); echo '</pre>';  
                ?>

                    <div class="col">
                        <div class="card shadow-sm rounded">
                            <a href="fiche_produit.php?id_article=<?= $produit['id_article'] ?>"><img src="<?= $produit['photo'] ?>" class="card-img-top" alt="..."></a>
                            <div class="card-body">
                                <h5 class="card-title text-center"><a href="fiche_produit.php?id_article=<?= $produit['id_article'] ?>" class="alert-link text-dark titre-produit-boutique"><?= $produit['titre'] ?></a></h5>
                                <p class="card-text"><?= substr($produit['description'], 0, 100); ?>[...]</p>
                                <p class="card-text fw-bold"><?= $produit['prix'] ?> €</p>
                                <p class="card-text text-center"><a href="fiche_produit.php?id_article=<?= $produit['id_article'] ?>" class="btn btn-outline-dark">En savoir plus</a></p>
                            </div>
                        </div>
                    </div>        

                <?php endwhile; ?>

            </div>
               

<?php
require_once 'inc/footer.inc.php';