<?php
    require_once 'inc/init.inc.php';

//echo '<pre>'; print_r($_POST); echo '</pre>';

// On entre dans la condition IF seulement quand l'internaute a ajouté un article au panier
if(isset($_POST['id_article'], $_POST['quantite']))
{
    // On selectionne les données de l'article ajouté au panier en BDD
    $data = $bdd->prepare("SELECT * FROM article WHERE id_article = :id_article");
    $data->bindValue(':id_article', $_POST['id_article'], PDO::PARAM_INT);
    $data->execute();

    $produit = $data->fetch(PDO::FETCH_ASSOC);
    //echo '<pre>'; print_r($produit); echo '</pre>';

    addPanier($produit['id_article'], $produit['photo'], $produit['titre'], $_POST['quantite'], $produit['stock'], $produit['prix']);

    
    echo '<pre>'; print_r($nbProd); echo '</pre>';

    header('location: boutique.php');

    echo '<pre>'; print_r($_SESSION); echo '</pre>';    
}

if(isset($_POST['payer']))
    {
        for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
        {
            $r = $bdd->query("SELECT * FROM article WHERE id_article = " . $_SESSION['panier']['id_article'][$i]);
            $produit = $r->fetch(PDO::FETCH_ASSOC);

            // si le stock de l'article en BDD est strictement inferieur à la quantité demandée, on entre dans la condition IF
            if($produit['stock'] < $_SESSION['panier']['quantite'][$i])
            {
                echo "stock en BDD : <span class='badge bg-success'>$produit[stock]</span><br>";
                echo "Quantité demandée : <span class='badge bg-success'>" . $_SESSION['panier']['quantite'][$i] . "</span>";

                // Si le stock de la BDD est supperieur à 0 mais inferieur à la quantité demandée dans le panier, alors on entre dans le IF
                if($produit['stock'] > 0)
                {
                    $_SESSION['panier']['quantite'][$i] = $produit['stock'];

                    $msg = "La quantité de l'article <strong> " . $_SESSION['panier']['titre'][$i] . "</strong> a été réduite car notre stock est insuffisant. Veuillez vérifier vos achat.";

                }
                else//  Sinon le stock est a 0, rupture de stock
                {
                    $msg = "L'article <strong> " . $_SESSION['panier']['titre'][$i] . "</strong> a été supprimer car nous sommes en rupture de stock.";
                    //on supprime de la session panier, l'article ayant un stock de 0
                    deletePanier($_SESSION['panier']['id_article'][$i]);
                    $i--;// on fait un tour en arriere de bouycle afin de controler l'article qui est rennomer d'1 indice dans le tableau de la session apres l'execussion du array _splice()
                }
                
                $error = true;
            }
           
        }

        if(!isset($error))
        {
            $bdd->query("INSERT INTO commande (user_id, montant, date) VALUES (" . $_SESSION['user']['id_user'] . "," . montantTotal() . ", NOW())");

            // lastInsertId() : permet de récupérer la dernière clé primaire inserée dans la BDD, ici le dernier id_commande afin de pouvoir l'inserer dans la table 'detail_commande' et de pouvoir relier chaque produit à la bonne commande
            $idCommande = $bdd->lastInsertId();

            for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
            {
                $bdd->query("INSERT INTO details_commande (commande_id, article_id, quantite, prix) VALUES ($idCommande, " . $_SESSION['panier']['id_article'][$i] . "," . $_SESSION['panier']['quantite'][$i] . "," . $_SESSION['panier']['prix'][$i] . ")");

                $bdd->query("UPDATE article SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . "WHERE id_article = " . $_SESSION['panier']['id_article'][$i]);
            }

            // On stock dans la session l'id_commande qui nous servira de numero de commande pour l' internaute
            $_SESSION['num_commande'] = $idCommande;

            // On vide le panier dans la session apres la validation de la commande
            unset($_SESSION['panier']);

            // une fois la commande validée, on redirige l'internaute vers la page validationj.php
            header('location: validation.php');
        }
    }

    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';    
?>

            <h1 class="text-center my-5">Votre panier</h1>

            <?php if(isset($msg)): ?>
                <p class="bg-success col-md-6 mx-auto p-3 tetx-center text-white my-3"><?= $msg; ?>
            <?php endif; ?>

            <!-- Si le tableau ARRAY ['id_article'] dans la session n'est pas vide, cela veut dire -->
            <?php if(!empty($_SESSION['panier']['id_article'])): ?> 

            <?php for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++): ?>

            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mx-auto d-flex justify-content-center shadow-sm px-0">

                <div class="col-md-2 bg-white p-2">
                    <a href="fiche_produit.php?id_article=<?= $_SESSION['panier']['id_article'][$i] ?>"><img src="<?= $_SESSION['panier']['photo'][$i] ?>" alt="produit 1" class="img-panier"></a>
                </div>
                <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-2">
                    <h4><a href="fiche_produit.html" class="alert-link text-dark titre-produit-panier"><?= $_SESSION['panier']['titre'][$i]; ?></a></h4>
                <?php 
                if($_SESSION['panier']['stock'][$i] <= 10)
                {
                    $txt = "Attention ! il ne reste que " . $_SESSION['panier']['stock'][$i] . " exemplaire(s) disponible";
                    $color = "danger";
                }
                else
                {
                    $txt = "En stock";
                    $color = "success";
                }
                ?>
                
                    <p class="text-<?= $color; ?> fw-bold fst-italic"><?= $txt;?></p>

                    <p>Quantité : <?= $_SESSION['panier']['quantite'][$i]; ?></p>

                    <p class="mb-0"><a href="" class="alert-link text-dark liens-supp-produit-panier">Supprimer</a></p>
                </div>

                <div class="col-md-4 bg-white d-flex justify-content-end align-items-center p-2">
                    <p class="fw-bold mb-0"><?= $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i]; ?>€</p>
                </div>
            </div><br>

            <?php endfor; ?>

            
            
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 d-flex justify-content-end align-items-center shadow-sm px-0 py-3 bg-white mt-2 mb-3">
                <h5 class="m-0 px-2 fw-bold">Sous total (<?= array_sum($_SESSION['panier']['quantite']); ?> article) : <?= montantTotal() ?> €</h5>
            </div>
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0 text-end mb-5">

            <?php if(connect()): ?>

            <form action="" method="post">
                <input type="submit" name="payer" class="btn btn-dark" value="FINALISER COMMANDE">
            </form>

            <?php else: ?>

                <a href="<?= URL ?>connexion.php" class="btn btn-dark" >IDENTIFIER-VOUS</a>

            <?php endif; ?>

            </div>

            <div>
                <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
            </div>

            <?php else: // Sinon le tableau['id_article'] dans la session est vide, donc l'internaute n' a pas ajouté d'article, on entre dans le ELSE ?>
                <div class="col-md-4 bg-white mx-auto d-flex justify-content-center align-items-center p-2">
                    <p class="fw-bold mb-0">Votre panier est vide</p>
                </div>

                <div >
                    <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
                </div>

            <?php endif; ?>



<?php
    require_once 'inc/footer.inc.php';
