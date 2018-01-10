<?php
$niveau = "./";
// Inclusion du fichier de configuration
// include($niveau . 'inc/scripts/config.inc.php');

if (isset($_GET["ajouterEcheance"])) {
    // Pour info seulement!:
    // Lorsque le formulaire est envoyé
    // on fera des validations sur l'ensemble du formulaire
    // et seulement si tout est correct, on ajoute l'occurrence dans la BD.
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width"/>
  <title>Exercice de validations avec jQuery</title>
  <!--URL de base pour la navigation -->
  <base href="<?php echo $niveau; ?>"/>
  <!--
    En attendant les css,
    la balise style et des attributs styles sont utilisés pour les validations
    <link rel="stylesheet" href="css/styles.css">
    -->
  <style>
    .erreurElement{
      border:1px solid red;
    }
  </style>
</head>
<body>
<main>
  <h1 id="contenu">Ajout d'une échéance</h1>
  <h2>Liste de choses à faire pour le cégep</h2>
  <form action="index.php">

    <div class="conteneurElementAValider">
      <p>
        <label for="tache">Tâche </label>
        <input type="text" name="tache" id="tache" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" required>
      </p>
      <p class="erreur" style="color:red"></p>
    </div>

    <fieldset class="conteneurElementAValider">
      <legend>Cours</legend>
      <ul>
        <li><input type="radio" name="cours" id="video" required><label for="video">Vidéo</label></li>
        <li><input type="radio" name="cours" id="conception"><label for="conception">Conception</label></li>
        <li><input type="radio" name="cours" id="realisation"><label for="realisation">Réalisation</label></li>
      </ul>
      <p class="erreur" style="color:red"></p>
    </fieldset>

    <fieldset class="conteneurElementAValider">
      <legend>Date d'échéance (facultatif)</legend>
      <div class="date">
        <label for="jour" class="visuallyhidden">Jour</label>
        <select name="jour" id="jour">
          <option value="null" selected>Jour</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
        <label for="jour" class="visuallyhidden">Mois</label>
        <select name="mois" id="mois">
          <option value="null" selected>Mois</option>
          <option value="1">Janvier</option>
          <option value="2">Février</option>
          <option value="3">Mars</option>
          <option value="4">Avril</option>
          <option value="5">Mai</option>
        </select>
        <label for="jour" class="visuallyhidden">Année</label>
        <select name="annee" id="annee">
          <option value="null" selected>Année</option>
          <option value="2016">2016</option>
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
          <option value="2020">2020</option>
        </select>
      </div>
      <p class="erreur" style="color:red"></p>
    </fieldset>
    <p>
      <button name="ajouterEcheance">Ajouter l'échéance</button>
    </p>
  </form>
</main>
<footer>
  <p>Tout droits réservés, 2017.</p>
</footer>
<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

<script>window.jQuery || document.write('<script src="bower_components/jquery/dist/jquery.min.js">\x3C/script>')</script>
<script src="js/validationsMandatA.js"></script>
<!--<script src="js/validationsMandatB.js"></script>-->
<script>
    $('body').addClass('js');
    /**
     * Initialiser les modules JavaScript ici: menu, accordéon...
     */
    $(document).ready(
        validationsMandatA.initialiser.bind(validationsMandatA)
        //validationsMandatB.initialiser.bind(validationsMandatB)
    );
</script>
</body>
</html>