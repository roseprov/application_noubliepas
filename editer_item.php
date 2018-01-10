<?php

//Définition de la variable de niveau
$niveau='./';

//Inclusion du fichier de configuration
include($niveau . 'inc/scripts/config.inc.php');

//Date du jour
$dateAujourdhui = new DateTime();

//Tableaux utilitaires
$arrMois=array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');

//Messages d'erreur et validation
$strFichierTexte=file_get_contents($niveau.'js/objJSONMessages.json');
$jsonMessagesErreur=json_decode($strFichierTexte);

$arrChampErreur = array();
$arrMessagesErreur = array();
$arrMessagesErreur['nomItem']='';
$arrMessagesErreur['echeance']='';

if(isset($_GET['id_liste']))
{
    $id_liste = $_GET['id_liste'];
}

//Variables d'opérations et d'erreurs
$strCodeOperation = '';
$intCodeErreur=00000;
$strMessage='';

if(isset($_GET['id_item']))
{
    $id_item = $_GET['id_item'];
}

if(isset($_GET['btn_enregistrer']))
{
    $strCodeOperation = 'modification';
}

$arrItem = array();
$typeErreur = array();

if($strCodeOperation == 'modification')
{
    //Récupération de la querystring
    $arrItem['nom_item'] = $_GET['nom'];
    $arrItem['jour']=$_GET['jour'];
    $arrItem['mois']=$_GET['mois'];
    $arrItem['annee']=$_GET['annee'];

    //Validations
    //Nom
    if($arrItem['nom_item'] != '')
    {
        $valeurNom=$arrItem['nom_item'];
        $motifNom="/^[a-zA-ZÀ-ÿ0-9 #?!'.\-#]{0,55}$/";
        if(!preg_match($motifNom, $valeurNom))
        {
            $intCodeErreur=-1;
            array_push($typeErreur, 'motif');
            array_push($arrChampErreur, 'nomItem');
        }
    }
    else
    {
        $intCodeErreur=-1;
        array_push($typeErreur, 'vide');
        array_push($arrChampErreur, 'nomItem');
    }

    //Échéance
    if(isset($_GET['jour']))
    {
        if($arrItem['jour'] != 0 && $arrItem['mois'] != 0 && $arrItem['annee'] != 0)
        {
            if(checkdate(intval($arrItem['mois']), intval($arrItem['jour']), intval($arrItem['annee'])))
            {
                $echeanceValide = $arrItem['annee'].'-'.$arrItem['mois'].'-'.$arrItem['jour'];
                $dateTemp = new DateTime($echeanceValide);
                if($dateTemp < $dateAujourdhui)
                {
                    $echeanceValide = NULL;
                    $intCodeErreur = -1;
                    array_push($typeErreur, 'motif');
                    array_push($arrChampErreur, 'echeance');
                }
            }
            else
            {
                $echeanceValide = NULL;
                $intCodeErreur = -1;
                array_push($typeErreur, 'motif');
                array_push($arrChampErreur, 'echeance');
            }
        }
        else
        {
            $echeanceValide = NULL;
        }
    }
    else
    {
        $echeanceValide = NULL;
    }

    //Update
    if($intCodeErreur == 00000 && $strCodeOperation == 'modification')
    {
        $strRequete = "UPDATE t_item SET item=:nom_item, echeance=:echeance
                            WHERE id_item=:id_item";

        //Initialisation de l'objet PDOStatement et exécution de la requête
        $pdosResultat = $pdoConnexion->prepare($strRequete);

        $pdosResultat->bindParam(':id_item', $id_item);
        $pdosResultat->bindParam(':nom_item', $arrItem["nom_item"]);
        $pdosResultat->bindParam(':echeance', $echeanceValide);

        $pdosResultat->execute();

        //Récupération ducode d'erreur s'il y a lieu
        $intCodeErreur = $pdosResultat->errorCode();

        //Libération des données
        $pdosResultat->closeCursor();
    }
}

$strRequete = 'SELECT item, DAYOFMONTH(echeance) AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee
            FROM t_item 
            WHERE id_item =:id_item';

//Initialisation de l'objet PDOStatement et exécution de la requête
$pdosResultat = $pdoConnexion->prepare($strRequete);

//Extraction de l'enregistrement de la BD
$pdosResultat->bindParam(':id_item', $id_item);
$pdosResultat->execute();

$ligne=$pdosResultat->fetch();

$arrItem['nom_item']=$ligne['item'];
$arrItem['jour']=$ligne['jour'];
$arrItem['mois']=$ligne['mois'];
$arrItem['annee']=$ligne['annee'];

//Libération des données
$pdosResultat->closeCursor();

//Petite requête pour récupérer le nom de la liste et sa couleur
$strRequete = 'SELECT nom_liste, hexadecimale
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

$arrListe['nom_liste'] = $ligne['nom_liste'];
$arrListe['hexa_couleur'] = $ligne['hexadecimale'];

$pdosResultat->closeCursor();

//Capture d'une erreur
if ($intCodeErreur != '00000') {
    $strMessage = "Une erreur est survenue";
    for ($intCpt = 0; $intCpt < count($arrChampErreur); $intCpt++) {
        $champ = $arrChampErreur[$intCpt];
        $arrMessagesErreur[$champ] = $jsonMessagesErreur->{$champ}->{'erreurs'}->{$typeErreur[$intCpt]};
    }
}
else if($strCodeOperation != ''){
    header("Location:" .$niveau. "consulter_liste.php?strCodeOperation=".$strCodeOperation."&id_liste=".$id_liste);
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Éditer item</title>
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
    <div class="contenu editerAjouterItem">
        <div class="titre">
            <h1 id="contenu">ÉDITER L'ITEM<br><span>de la liste </span><?php echo $arrListe['nom_liste']; ?></h1>
        </div>
        <form action="editer_item.php" method="get">
            <input type="hidden" name="id_liste" value="<?php echo $id_liste;?>">
            <input type="hidden" name="id_item" value="<?php echo $id_item;?>">
            <div class="conteneurElementAValider">
                <div>
                    <label for="nom_item">Nom de l'item</label>
                    <input type="text" id="nom_item" name="nom" value="<?php echo $arrItem['nom_item'];?>" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" required>
                </div>
                <span class="erreur"><?php echo $arrMessagesErreur['nomItem'];?></span>
            </div>
            <?php if($arrItem['jour'] != 0){?>
            <div class="optionEcheance optionEcheance--isExpanded datePresente">
                <?php }
                else{ ?>
                    <div class="optionEcheance">
                <?php }?>
                <fieldset class="conteneurElementAValider optionEcheance__bloc">
                    <legend class="visuallyhidden">Ajouter une échéance</legend>
                    <p>
                        <label for="jour" class="visuallyhidden">Jour</label>
                        <select name="jour" id="jour">
                            <option value="0">Jour</option>
                            <?php for($intCpt=1;$intCpt<=31;$intCpt++){?>
                                <option value="<?php echo $intCpt; ?>" <?php if($arrItem['jour']==$intCpt){echo 'selected="selected"';}?>><?php echo $intCpt; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label for="mois" class="visuallyhidden">Mois</label>
                        <select name="mois" id="mois">
                            <option value="0">Mois</option>
                            <?php for($intCpt=1;$intCpt<=count($arrMois);$intCpt++){?>
                                <option value="<?php echo $intCpt; ?>" <?php if($arrItem['mois']==$intCpt){echo 'selected="selected"';}?>><?php echo $arrMois[$intCpt - 1]; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label for="annee" class="visuallyhidden">Année</label>
                        <select name="annee" id="annee">
                            <option value="0">Année</option>
                            <?php for($intCpt=$dateAujourdhui->format('Y');$intCpt<intval($dateAujourdhui->format('Y'))+100;$intCpt++){ ?>
                                <option value="<?php echo $intCpt; ?>" <?php if($arrItem['annee']==$intCpt){echo 'selected="selected"';}?>><?php echo $intCpt; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </p>
                </fieldset>
                <span class="erreur"><?php echo $arrMessagesErreur['echeance'];?></span>
            </div>
            <div class="boutons">
                <button name="btn_enregistrer" value="modifier" class="boutonPrincipal">Enregistrer</button>
                <a href="consulter_liste.php?id_liste=<?php echo $id_liste; ?>" class="boutonAnnuler">Annuler</a>
            </div>
        </form>
    </div>
</main>
<?php include('inc/fragments/footer.inc.html');
include('inc/scripts/scripts.inc.php'); ?>
<!-- Scripts de validation mandat B -->
<script src="js/validationsMandatB.js"></script>
<script>
    $(document).ready(
        validationsMandatB.initialiser.bind(validationsMandatB)
    );
</script>
<script src="js/_ajouterEcheance.js"></script>
<script>
    $(document).ready(
        optionEcheance.initialiser.bind(optionEcheance)
    );
</script>
</body>
</html>
