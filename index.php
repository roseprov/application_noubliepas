<?php
/**
 * Created by PhpStorm.
 * User: RoseRP
 * Date: 2017-11-15
 * Time: 15:39
 */

$niveau="./";

//Inclusion du fichier de connexion à la BD
include($niveau."inc/scripts/config.inc.php");
$strFichierTexte=file_get_contents($niveau.'js/objJSONMessages.json');
$jsonMessagesErreur=json_decode($strFichierTexte);

//Variable de l'utilisateur
$utilisateur=1;
//Variable des messages d'erreur
$strMessage="";
//Variable code erreur
$intCodeErreur='00000';
//Liste à supprimer
$strListeDelete="";
//Code présence en querystring
$strCodeOperation="";

if(isset($_GET['btn_supprimer'])){
    $strCodeOperation="supprimer";
    $strListeDelete=$_GET['id_liste'];
}

//Supprimer une liste
if($strCodeOperation=="supprimer" && $intCodeErreur=='00000'){
    $strRequete="DELETE FROM t_liste WHERE id_liste=:id_liste";
    //$strEntete='Supression du participant '.$arrListes['prenom_participant'].' '.$arrInfoParticipant['nom_participant'];
    $strMessage=$jsonMessagesErreur->{'retroactions'}->{'liste'}->{'suppression'};

    $pdosResultat=$pdoConnexion->prepare($strRequete);
    $pdosResultat->bindParam(':id_liste', $strListeDelete);
    $pdosResultat->execute();
    //Récupération du CodeErreur
    $intCodeErreur=$pdosResultat->errorCode();
    $pdosResultat->closeCursor();
}


//Items urgents
$strRequete="SELECT t_item.id_item, item, CAST(echeance AS date) AS echeance, hexadecimale, t_item.id_liste 
FROM t_item 
INNER JOIN t_liste ON t_item.id_liste=t_liste.id_liste 
INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur 
WHERE echeance IS NOT NULL 
ORDER BY echeance 
LIMIT 3";
$pdosResultat=$pdoConnexion->prepare($strRequete);
$pdosResultat->execute();

$arrItemsUrgents=[];
$ligneUrgent=$pdosResultat->fetch();
for($cpt=0; $cpt<$pdosResultat->rowCount();$cpt++){
    $arrItemsUrgents[$cpt]['id_liste']=$ligneUrgent['id_liste'];
    $arrItemsUrgents[$cpt]['item']=$ligneUrgent['item'];
    $arrItemsUrgents[$cpt]['echeance']=$ligneUrgent['echeance'];
    $arrItemsUrgents[$cpt]['hexadecimale']=$ligneUrgent['hexadecimale'];

    $ligneUrgent=$pdosResultat->fetch();
}
$pdosResultat->closeCursor();

//Informations liste
$strRequete="SELECT t_liste.id_liste, nom_liste, hexadecimale FROM t_liste 
 INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur WHERE id_utilisateur=:id_utilisateur 
 ORDER BY nom_liste";

$pdosResultat=$pdoConnexion->prepare($strRequete);
$pdosResultat->bindParam(':id_utilisateur', $utilisateur);
$pdosResultat->execute();

$arrListes=[];
$ligne=$pdosResultat->fetch();
for($cpt=0; $cpt<$pdosResultat->rowCount();$cpt++){
    $arrListes[$cpt]['id_liste']=$ligne['id_liste'];
    $arrListes[$cpt]['nom_liste']=$ligne['nom_liste'];
    $arrListes[$cpt]['hexadecimale']=$ligne['hexadecimale'];

    //Calculer le nombre d'items par liste (Sous-requête)
    $strSousRequete="SELECT t_item.id_item FROM t_item 
INNER JOIN t_liste ON t_item.id_liste=t_liste.id_liste 
WHERE t_item.id_liste=:id_liste";

    $pdosSousResultat=$pdoConnexion->prepare($strSousRequete);
    $pdosSousResultat->bindParam(':id_liste', $ligne['id_liste']);
    $pdosSousResultat->execute();

    $arrListes[$cpt]['nb_items']=$pdosSousResultat->rowCount();
    $pdosSousResultat->closeCursor();

    $ligne=$pdosResultat->fetch();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>N'oublie pas!</title>
    <!--URL de base pour la navigation -->
    <base href="<?php echo $niveau?>"/>
    <link rel="stylesheet" href="css/styles_a.css">
</head>
<body>
<!--http://webaim.org/techniques/skipnav/-->
<a href="#contenu" class="lienEvitement focusable visuallyhidden">Allez au contenu</a>
<header role="banner">
    <?php include('inc/fragments/zone_utilisateur.php'); ?>
</header>
<main class="wrap accueil">
    <?php include('inc/fragments/header.inc.php'); ?>
    <h1 id="contenu" class="visuallyhidden">Accueil</h1>
    <section class="section_aidememoire">
        <h2>Aide-mémoire</h2>
        <ul class="ul_aidemesasmoire">
            <?php for($cpt=0;$cpt<count($arrItemsUrgents);$cpt++){ ?>
                <li>
                    <div class="conteneur-flex">
                        <img src="images/couleurs/<?php echo $arrItemsUrgents[$cpt]['hexadecimale']; ?>.png" alt="" class="imgRadius">
                        <div class="rangee">
                            <div class="echeance">
                                <span class="flaticon-alarm" aria-hidden="true"></span>
                                <time datetime="<?php echo $arrItemsUrgents[$cpt]['echeance']; ?>"><?php echo $arrItemsUrgents[$cpt]['echeance']; ?></time>
                            </div>
                            <a href="consulter_liste.php?id_liste=<?php echo $arrItemsUrgents[$cpt]['id_liste'] ?>" class="echeance_nom"><?php echo $arrItemsUrgents[$cpt]['item']; ?></a>
                        </div>
                    </div>

                </li>
            <?php } ?>
        </ul>
    </section>
    <span class="separateur"></span>
    <section class="section_listes">
        <h2>Mes listes</h2>
        <?php if(isset($_GET['strCodeOperation'])){
            if($_GET['strCodeOperation']=="modifier"){?>
                <span><?php echo $jsonMessagesErreur->{"retroactions"}->{"liste"}->{"modification"}; ?></span>
            <?php }else if($_GET['strCodeOperation']=="ajouter"){ ?>
                <span><?php echo $jsonMessagesErreur->{"retroactions"}->{"liste"}->{"ajout"}; ?></span>
            <?php }
        }?>
        <span><?php echo $strMessage ?></span>
        <div class="ajouter_liste">
            <a class="lien_ajouter" href="ajouter_liste.php">
                <span class="flaticon-plus" aria-hidden="true"></span>
                Ajouter une liste
            </a>
        </div>
        <ul class="ul_liste">
            <?php for($cpt=0;$cpt<count($arrListes); $cpt++){ ?>
                <li>
                    <form action="index.php" method="get" class="form_liste">
                        <div class="conteneur-flex">
                            <img src="images/couleurs/<?php echo $arrListes[$cpt]['hexadecimale']; ?>.png" alt="" class="imgRadius couleur_liste">
                            <h3>
                                <a href="consulter_liste.php?id_liste=<?php echo $arrListes[$cpt]['id_liste']; ?>">
                                    <?php echo $arrListes[$cpt]['nom_liste']; ?>
                                </a>
                            </h3>
                        </div>
                        <span class="nb_items">Nombre d'items: <?php echo $arrListes[$cpt]['nb_items']; ?></span>
                        <div class="liens_actions">
                            <a href="editer_liste.php?id_liste=<?php echo $arrListes[$cpt]['id_liste']; ?>" class="lien_editer">
                                <span class="flaticon-edit" aria-hidden="true"></span>
                                <span class="visuallyhidden">Éditer la liste</span>
                            </a>
                            <input type="hidden" name="id_liste" value="<?php echo $arrListes[$cpt]['id_liste']; ?>">
                            <a href="index.php#modale<?php echo $arrListes[$cpt]['id_liste']; ?>">
                                <span class="flaticon-error" aria-hidden="true"></span>
                                <span class="visuallyhidden">Supprimer la liste</span>
                            </a>
                        </div>
                        <div id="modale<?php echo $arrListes[$cpt]['id_liste']; ?>" class="modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <header>
                                        <p>Supprimer la liste</p>
                                    </header>
                                    <div class="container">
                                        <p>Voulez vous vraiment supprimer la liste <?php echo $arrListes[$cpt]['nom_liste'] ?> ?</p>
                                    </div>
                                    <footer class="container">
                                        <a href="index.php#" class="btn_annuler">Annuler</a>
                                        <button name="btn_supprimer" class="btn_supprimer" id="btn_supprimer" type="submit">Supprimer la liste</button>
                                    </footer>
                                </div>
                            </div>
                        </div>
                    </form>
                </li>
            <?php } ?>
        </ul>
    </section>
</main>


<?php
include ('inc/fragments/footer.inc.html');
include('inc/scripts/scripts.inc.php'); ?>

</body>
</html>

