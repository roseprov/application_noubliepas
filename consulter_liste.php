<?php

//Définition de la variable de niveau
$niveau='./';

//Inclusion du fichier de configuration
include($niveau . 'inc/scripts/config.inc.php');

//Validations et messages
$strFichierTexte = file_get_contents($niveau.'js/objJSONMessages.json');
$jsonMessageErreur = json_decode($strFichierTexte);

if(isset($_GET['id_liste']))
{
    $id_liste = $_GET['id_liste'];
}

//Petite requête pour récupérer le nom de la liste et sa couleur
$strRequete = 'SELECT id_liste, nom_liste, hexadecimale
                FROM t_liste
                INNER JOIN t_couleur ON t_liste.id_couleur = t_couleur.id_couleur
                WHERE id_liste = :id_liste';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);

//Extraction de l'enregistrement de la BD
$pdosResultat->bindParam(':id_liste', $id_liste);
$pdosResultat->execute();

$ligne=$pdosResultat->fetch();
$arrListe = array();

$arrListe['id_liste'] = $ligne['id_liste'];
$arrListe['nom_liste'] = $ligne['nom_liste'];
$arrListe['hexa_couleur'] = $ligne['hexadecimale'];

$pdosResultat->closeCursor();

$id_item = '';
$etat_item = '';
$strCodeOperation = '';

if(isset($_GET['btn_a_completer']))
{
    $etat_item = 1;
    $strCodeOperation = 'modifier';
    $id_item = $_GET['id_item'];
}

if(isset($_GET['btn_complete']))
{
    $etat_item = 0;
    $strCodeOperation = 'modifier';
    $id_item = $_GET['id_item'];
}

if(isset($_GET['btn_supprimer']))
{
    $strCodeOperation = 'supprimer';
    $id_item = $_GET['id_item'];
}

if($strCodeOperation == 'modifier')
{
    $strRequete = 'UPDATE t_item
                    SET est_complete=:est_complete
                    WHERE id_item = :id_item';

    //Initialisation de l'objet PDOStatement et exécution de la requête
    $pdosResultat = $pdoConnexion->prepare($strRequete);

    $pdosResultat->bindParam(':id_item', $id_item);
    $pdosResultat->bindParam(':est_complete', $etat_item);

    $pdosResultat->execute();

    $pdosResultat->closeCursor();
}

if($strCodeOperation == 'supprimer')
{
    $strRequete = 'DELETE FROM t_item WHERE id_item=:id_item';

    //Initialisation de l'objet PDOStatement et exécution de la requête
    $pdosResultat = $pdoConnexion->prepare($strRequete);

    $pdosResultat->bindParam(':id_item', $id_item);

    $pdosResultat->execute();

    $pdosResultat->closeCursor();
}

$strRequete = 'SELECT id_item, item, CAST(echeance AS date) AS echeance, est_complete 
                FROM t_item 
                INNER JOIN t_liste ON t_liste.id_liste = t_item.id_liste
                WHERE t_item.id_liste = :id_liste
                ORDER BY id_item DESC';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);

//Extraction de l'enregistrement de la BD
$pdosResultat->bindParam(':id_liste', $id_liste);
$pdosResultat->execute();

$arrItems = array();
$ligne=$pdosResultat->fetch();

for($intCpt=0;$intCpt<$pdosResultat->rowCount();$intCpt++)
{
    $arrItems[$intCpt]['id_item']=$ligne['id_item'];
    $arrItems[$intCpt]['nom_item']=$ligne['item'];
    $arrItems[$intCpt]['echeance_item']=$ligne['echeance'];
    $arrItems[$intCpt]['item_est_complete']=$ligne['est_complete'];
    $ligne=$pdosResultat->fetch();
}

//Libération des données
$pdosResultat->closeCursor();

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Fiche liste</title>
    <!--URL de base pour la navigation -->
    <base href="<?php echo $niveau ?>" />
    <link rel="stylesheet" href="css/styles_b.css">
</head>
<body>
<noscript>
    <p>Le Javascript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
</noscript>
<a href="#contenu" class="visuallyhidden focusable lienEvitement">Allez au contenu</a>
<header role="banner">
    <?php include('inc/fragments/zone_utilisateur.php'); ?>
</header>
<main class="wrap">
    <?php include('inc/fragments/header.inc.php'); ?>
    <div class="contenu">
        <div class="titre">
            <img class="imgRadius" src="images/couleurs/<?php echo $arrListe['hexa_couleur']; ?>.png" alt="Couleur de la liste">
            <h1 id="contenu"><?php echo $arrListe['nom_liste']; ?></h1>
        </div>
        <?php if(isset($_GET['strCodeOperation']))
        {
            echo $jsonMessageErreur->{'retroactions'}->{'item'}->{$_GET['strCodeOperation']};
        }?>
        <ul class="listeItems">
            <?php for($intCpt=0;$intCpt<count($arrItems);$intCpt++){?>
                <li class="listeItems__item">
                    <a href="editer_item.php?id_item=<?php echo $arrItems[$intCpt]['id_item']; ?>&id_liste=<?php echo $arrListe['id_liste']; ?>" class="listeItems__item--lien">
                        <?php echo $arrItems[$intCpt]['nom_item']; ?>
                        <?php if($arrItems[$intCpt]['echeance_item'] != NULL){?>
                            <time datetime="<?php echo $arrItems[$intCpt]['echeance_item']; ?>"><?php echo $arrItems[$intCpt]['echeance_item']; ?></time>
                        <?php } ?>
                    </a>
                    <div class="listeItems__item--icones">
                        <?php if($arrItems[$intCpt]['item_est_complete'] == 0){?>
                            <form action="consulter_liste.php?strCodeOperation=modification" method="get">
                                <input type="hidden" name="id_item" value="<?php echo $arrItems[$intCpt]['id_item']; ?>">
                                <input type="hidden" name="id_liste" value="<?php echo $id_liste; ?>">
                                <input type="hidden" name="strCodeOperation" value="<?php echo 'modification'; ?>">
                                <button name="btn_a_completer">
                                    <span class="flaticon-stop" aria-hidden="true"></span>
                                    <span class="visuallyhidden">Compléter l'item</span>
                                </button>
                            </form>
                        <?php }
                        else{ ?>
                            <form action="consulter_liste.php?strCodeOperation=modification" method="get">
                                <input type="hidden" name="id_item" value="<?php echo $arrItems[$intCpt]['id_item']; ?>">
                                <input type="hidden" name="id_liste" value="<?php echo $id_liste; ?>">
                                <input type="hidden" name="strCodeOperation" value="<?php echo 'modification'; ?>">
                                <button name="btn_complete">
                                    <span class="flaticon-checked" aria-hidden="true"></span>
                                    <span class="visuallyhidden">Remettre l'item à compléter</span>
                                </button>
                            </form>
                        <?php } ?>
                        <a href="editer_item.php?id_item=<?php echo $arrItems[$intCpt]['id_item']; ?>&id_liste=<?php echo $arrListe['id_liste']; ?>">
                            <span class="flaticon-edit" aria-hidden="true"></span>
                            <span class="visuallyhidden">Éditer l'item</span>
                        </a>
                        <form action="consulter_liste.php" method="get">
                            <input type="hidden" name="id_item" value="<?php echo $arrItems[$intCpt]['id_item']; ?>">
                            <input type="hidden" name="id_liste" value="<?php echo $id_liste; ?>">
                            <input type="hidden" name="strCodeOperation" value="<?php echo 'suppression'; ?>">
                            <button name="btn_supprimer" value="<?php echo $id_liste; ?>" >
                                <span class="flaticon-error" aria-hidden="true"></span>
                                <span class="visuallyhidden">Supprimer l'item</span>
                            </button>
                        </form>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <a href="ajouter_item.php?id_liste=<?php echo $id_liste; ?>" class="boutonPrincipal">Ajouter un item</a>
    </div>
</main>
<?php include('inc/fragments/footer.inc.html');
include('inc/scripts/scripts.inc.php'); ?>
</body>
</html>
