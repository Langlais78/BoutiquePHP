<?php
// FONCTION INTERNAUTE CONNECTE
// Fonction permettant de savoir si l'internaute est identifié sur le site
function connect()
{
    // Si l'indice 'user' N EST PAS DEFINIT dans la session, cela veur dire l' internaute n' est pas passé par la page connexion, donc qu'il n' est pas authentifié sur le site
    if(!isset($_SESSION['user']))
        return false;
    else
        return true;
}

// FONCTION ADMINISTRATEUR CONNECTE
// fonction permettant de savoir si l'utilisateur est connecte et si son statut dans la BDD est bien 'admin'
function adminConnect()
{
    // Si l'indice 'user' EST DEFINIT dans la session et que l'indice 'statut' a pour valeur 'admin' dans la BDD
    if(connect() && $_SESSION['user']['statut'] == 'admin')
        return true;
    else
        return false;
}

// FONCTION DE CREATION PANIER DANS LA SESSION
function creationPanier()
{
    // Si l'indice 'panier' dans la session N'EST PAS definit, alors on entre dans le IF et on crée les different tableaux dans le fichier de session de l'utilisateur
    if(!isset($_SESSION['panier']))
    {
        // Ces tableaux permettent de stocker les données d'un produit ajouté au panier
        $_SESSION['panier'] = [];
        $_SESSION['panier']['id_article'] = [];
        $_SESSION['panier']['photo'] = [];
        $_SESSION['panier']['titre'] = [];
        $_SESSION['panier']['quantite'] = [];
        $_SESSION['panier']['stock'] = [];
        $_SESSION['panier']['prix'] = [];
    }
}

// FONCTION AJOUT PRODUIT DANS LA SESSION 
function addPanier($id_article, $photo, $titre, $quantite, $stock, $prix)
{
    // On verifie si l'indice 'panier' est crée dans la session ou pas 
    creationPanier();

    // array_search() =: fonction predefinit permettant de savoir à quel indice se trouve un eelement dans un tableau array
    // On tente de trouver si le produit que l'on ajout au produit au panier est deja existant dans le tableau array $_SESSION['panier']['id_article'] dans la session
    $positionProduit = array_search($id_article, $_SESSION['panier']['id_article']);

    // Si la valeurde $posotionProduit est differente de false, cela veut dire que l'id_article est present dasns le panier de la session, alors on entre dans le IF et on modifit seulement la quantite du produit a l'indice correspondant
    if($positionProduit !== false)
    {
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
    }
    else // Sinon, l'id_produit n'existe pas dans la session $_SESSION['panier']['id_article'], on ajoute l'article normalement dans le panier
    {
        // On stock les données selectionnés en BDD du produit ajouter au panier dans les différents tableaux du panier dans la session
        $_SESSION['panier']['id_article'][] = $id_article;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['stock'][] = $stock;
        $_SESSION['panier']['prix'][] = $prix;
    }
}

// FONCTION CALCUL PANIER

function montantTotal()
{
    $total = 0;

    // La boucle FOR tourne autant de fois qu'il y a d'id_article dans la session du panier, en gros elle tourne autant qu' il y a d'articles dans le panier
    for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i];
    }
    return round($total,2);
}

function deletePanier($idArticle)
{
    // On cherche a quel indice du tableau array ['id_article'] se trouve l'article a supprimer dans le panier
    $positionProduit = array_search($idArticle, $_SESSION['panier']['id_article']);

    // Si $positionProduit retourne un indice, on entre dans la condition
    if($positionProduit !== false)
    {
        // array_splice() supprime les elements d'un tableau à l'indice correspondant mais il va re-organiser le tableau ARRAY , tout les element stocker au indice inferieur vont remonter au indice superieur
        // ex : l'article stocké a l'indice [2] de l' ARRAY v aremonter a l'indice [1] pour eviter d'avoir des indice vide dans l'ARRAY
        array_splice($_SESSION['panier']['id_article'], $positionProduit, 1);
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1);
        array_splice($_SESSION['panier']['stock'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
    }
}