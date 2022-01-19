<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0" aria-label="Eighth navbar example">
                <div class="container">
                    <a class="navbar-brand" href="-index.php"><img src="<?= URL ?>assets/img/boutique.gif" alt="logo-gif" class="logo-gif"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
            
                    <div class="collapse navbar-collapse" id="navbarsExample07">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= URL ?>-index.php"><i class="bi bi-house-fill"></i></a>
                            </li>

                            <?php if(connect()):// lien accorder a l'utilisateur connecte ?>

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= URL ?>profil.php">Mon compte</a>
                            </li>

                            <?php else:// liens accorde a l'utilisateur lambda NON AUTENTIFIE ?>

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= URL ?>inscription.php">Créer votre compte</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= URL ?>connexion.php">Identifiez-vous</a>
                            </li>

                            <?php endif;// lien commun accordé a tout utilisateur ?>

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= URL ?>boutique.php">Boutique</a>
                            </li>

                            <?php
                            if(isset($_SESSION['panier']))
                            $calc =  array_sum($_SESSION['panier']['quantite']);
                            else
                            $calc = 0;
                            ?>

                            <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?= URL ?>panier.php">Panier<span class='badge bg-success'><?= $calc ?></span></a>
                                
                            </li>

                            <?php if(adminConnect()):// lien accorde a l'adminstrateur du site, statut 'admin' dans la BDD donc dans la session ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown07" data-bs-toggle="dropdown" aria-expanded="false">BACKOFFICE</a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown07">
                                <li><a class="dropdown-item" href="admin/gestion_boutique.php">Gestion boutique</a></li>
                                <li><a class="dropdown-item" href="admin/gestion_user.php">Gestion user</a></li>
                                <li><a class="dropdown-item" href="admin/gestion_commande.php">Gestion commande</a></li>
                                </ul>
                            </li>

                            <?php endif; ?>
                        </ul>

                        <?php if(connect()): ?>

                        <span class="d-flex flex-column justify-content-center align-items-center my-1">

                        <h5><span class="fst-italic text-white mb-1">Bonjour <?php echo $_SESSION['user']['pseudo']; ?> !</span></h5>                     

                        <a href="<?= URL ?>connexion.php?action=deconnexion" class="btn btn-danger text-white">Deconnexion</a>
                        
                        </span>

                        <?php endif; ?>

                        <!-- <form>
                        <input class="form-control" type="text" placeholder="Rechercher" aria-label="Search">
                        </form> -->
                    </div>
                </div>
            </nav>

            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= URL ?>assets/img/slider3.jpg" class="d-block w-100" alt="slider 3">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= URL ?>assets/img/slider4.jpg" class="d-block w-100" alt="slider 4">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= URL ?>assets/img/slider1.jpg" class="d-block w-100" alt="slider 1">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </header>

        <main class="container zone-main">