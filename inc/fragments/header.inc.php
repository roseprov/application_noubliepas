<?php

//Inclusion du fichier de configuration
include('inc/scripts/config.inc.php');

//Petite requête pour récupérer le nom de la liste et sa couleur
$strRequete = 'SELECT id_liste, nom_liste, hexadecimale
                FROM t_liste
                INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);

//Extraction de l'enregistrement de la BD
$pdosResultat->execute();

$ligne=$pdosResultat->fetch();
$arrListesMenu = array();

for($intCpt=0;$intCpt<$pdosResultat->rowCount();$intCpt++)
{
    $arrListesMenu[$intCpt]['id_liste'] = $ligne['id_liste'];
    $arrListesMenu[$intCpt]['nom_liste'] = $ligne['nom_liste'];
    $arrListesMenu[$intCpt]['hexa_couleur'] = $ligne['hexadecimale'];
    $ligne=$pdosResultat->fetch();
}

$pdosResultat->closeCursor();

//Petite requête pour récupérer le prénom de l'utilisateur
$strRequete = 'SELECT prenom
                FROM t_utilisateur';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);

//Extraction de l'enregistrement de la BD
$pdosResultat->execute();

$ligne=$pdosResultat->fetch();

$prenomUtilisateur = $ligne['prenom'];

$pdosResultat->closeCursor();

?>
<nav class="menu interactivite">
    <ul class="menu__liste menu__liste--fermer">
        <li class="menu__listeItem"><p>MENU</p></li>
        <li class="menu__listeItem recherche">
            <form action="#" method="get" class="mobile">
                <label for="recherche" class="visuallyhidden">Rechercher</label>
                <input type="text" id="recherche" placeholder="Rechercher">
                <button>
                    <span class="flaticon-search" aria-hidden="true"></span>
                    <span class="visuallyhidden">Rechercher</span>
                </button>
            </form>
        </li>
        <li class="menu__listeItem">
            <div class="userzone">
                <span class="user_icon"></span> Bonjour <?php echo $prenomUtilisateur;?>
                <ul class="menu__userzone">
                    <li class="menu__listeItem"><a href="#" class="menu__lien mobile"><span class="flaticon-user-3" aria-hidden="true"></span>Mon compte</a></li>
                    <li class="menu__listeItem"><a href="#" class="menu__lien mobile"><span class="flaticon-login" aria-hidden="true"></span>Déconnexion</a></li>
                </ul>
            </div>
        </li>
        <li class="menu__listeItem"><a href="index.php" class="menu__lien flaticon"><span class="flaticon-home" aria-hidden="true"></span>Accueil</a></li>
        <li class="menu__listeItem"><a href="ajouter_liste.php" class="menu__lien flaticon"><span class="flaticon-plus" aria-hidden="true"></span>Ajouter une liste</a></li>
        <li class="menu__listeItem">
            Listes
            <ul class="menu__sousListe">
                <?php for($intCpt=0;$intCpt<count($arrListesMenu);$intCpt++){?>
                    <li class="menu__sousListeItem">
                        <a href="consulter_liste.php?id_liste=<?php echo $arrListesMenu[$intCpt]['id_liste'];?>" class="menu__lien">
                            <img class="imgRadius" src="images/couleurs/<?php echo $arrListesMenu[$intCpt]['hexa_couleur'];?>.png" alt="" class="menu__sousListeItem__couleur">
                            <span><?php echo $arrListesMenu[$intCpt]['nom_liste']; ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    </ul>
</nav>