<?php
    require_once 'inc/init.inc.php';

    // SELECTION DES 6 DERNIERS ARTILES ENREGISTRES EN BDD
    $data = $bdd->query("SELECT * FROM article ORDER BY id_article DESC LIMIT 6");

    // SELECTION DES 3 DERNIERS ARTICLES CONSULTES PAR L'INTERNAUTE (COOKIE)
    // echo '<pre>'; print_r($_COOKIE); echo '</pre>';  
    if(isset($_COOKIE['derniers_articles']))
    {
        $jsonDecode = json_decode($_COOKIE['derniers_articles']);
        // echo '<pre>'; print_r($jsonDecode); echo '</pre>';  
        $arraySlice = array_slice($jsonDecode, -3, 3);
        $idArticles = implode(',', $arraySlice);
        //echo '<pre>'; print_r($idArticles); echo '</pre>';  

        $data2 = $bdd->query("SELECT * FROM article WHERE id_article IN($idArticles)");
    }

    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>
            <h1 class="text-center my-5">Site demo Ecommerce</h1>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore asperiores dicta nobis eos sapiente cupiditate eaque voluptatum praesentium doloremque tempora! Blanditiis pariatur esse voluptas nam harum id cum laboriosam asperiores reprehenderit atque aliquam, natus maxime, impedit dolor ab suscipit, vel repellendus quidem perferendis. Quod laboriosam fugit iusto animi ducimus, error ipsam expedita. Ipsa doloremque deleniti in illo accusamus doloribus unde perferendis, magnam ipsum itaque, culpa iure tempora facilis? Deserunt magnam corporis inventore dolor culpa exercitationem facilis magni consequatur? Eveniet ea ad dolor explicabo perferendis, saepe illum architecto natus voluptates veritatis, sint delectus placeat quibusdam asperiores doloremque doloribus voluptas assumenda deserunt.</p>

            <!-- 
                Exo : Faites en sorte d'afficher les 6 derniers articles qui ont été enregistrés dans la boutique
                Dans la requete SELECT
                - ordonner les articles en ordre décroissant en fonction de l'id_produit (OERDER BY)
                - selectionner seulement 6 articles

                query + fetch + boucle

                Si on clique sur 1 article nous somme redirigés vers sa fiche produit

            // $data = $bdd->query("SELECT * FROM article ORDER BY id_article DESC LIMIT 6");
            // $t = $data->fetchAll(PDO::FETCH_ASSOC);
            // //echo '<pre>'; print_r($t); echo '</pre>';

            // foreach($t as $key => $value)
            // {               
            //         //echo '<pre>'; print_r($t); echo '</pre>';
            // } 
-->

            <?php $data = $bdd->query("SELECT * FROM article ORDER BY id_article DESC LIMIT 6");
            ?>
            <h2 class="text-center my-5">Nouveautés</h2>

            <div class="container d-flex flex-wrap justify-content-around my-5">

            <?php while($t = $data->fetch(PDO::FETCH_ASSOC)): ?>

            <a href="fiche_produit.php?id_article=<?= $t['id_article'] ?>" class="liens-nouveautes m-2"><img src="<?= $t['photo'] ?>" class="img-nouveautes rounded shadow-sm" alt="tee-shirt 1"></a>

            <?php endwhile; ?>



            </div>

            <h2 class="text-center my-5">Derniers articles consultés</h2>

            <div class="container d-flex flex-wrap justify-content-around my-5">
                <a href="" class="liens-nouveautes m-2"><img src="assets/img/tee-shirt1.jpg" class="img-nouveautes rounded shadow-sm" alt="tee-shirt 1"></a>
                <a href="" class="liens-nouveautes m-2"><img src="assets/img/tee-shirt2.jpg" class="img-nouveautes rounded shadow-sm" alt="tee-shirt 2"></a>
                <a href="" class="liens-nouveautes m-2"><img src="assets/img/tee-shirt3.jpg" class="img-nouveautes rounded shadow-sm" alt="tee-shirt 3"></a>
            </div>

<?php
    require_once 'inc/footer.inc.php';

        