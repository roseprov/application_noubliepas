<?php
/**
 * Created by PhpStorm.
 * User: RoseRP
 * Date: 2017-11-21
 * Time: 18:14
 */
//Inclusion du fichier de configuration
include('inc/scripts/config.inc.php');

//variable utilisateur
$utilisateur="";

$strRequete= "SELECT prenom FROM t_utilisateur";
$pdosResultat=$pdoConnexion->prepare($strRequete);
$pdosResultat->execute();

$ligne=$pdosResultat->fetch();

$utilisateur=$ligne['prenom'];


?>
<div class="div_logo wrap">
    <span class="logo">
        <img src="images/logo.png" alt="Logo">
    </span>
</div>

<div class="zone_utilisateur wrap">
    <div class="usersearch">
        <form action="#" method="get" class="mobile">
            <label for="recherche" class="visuallyhidden">Rechercher</label>
            <input type="text" id="recherche" placeholder="Rechercher">
            <button class="focusable btn_recherche">
                <span class="flaticon-search" aria-hidden="true"></span>
                <span class="visuallyhidden">Rechercher</span>
            </button>
        </form>
    </div>
    <div class="user">
        <button class="user__controle focusable btn_user">
            <span class="flaticon-user-3 ico_user"></span>
        </button>

        <ul class="user__menu user__menu--fermer">
            <li class="user__menuItem usernow">Bonjour <?php echo $utilisateur ?></li>
            <li class="user__menuItem"><a href="" class="user__menuLien"><span class="flaticon-user-3" aria-hidden="true"></span> Mon compte</a></li>
            <li class="user__menuItem"><a href="" class="user__menuLien"><span class="flaticon-login" aria-hidden="true"></span> Déconnexion</a></li>
        </ul>
    </div>
</div>
