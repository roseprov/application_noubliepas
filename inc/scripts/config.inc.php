<?php

// Verifier si l'exécution se fait sur le serveur de développement (local) ou celui de la production:
if (stristr($_SERVER['HTTP_HOST'], 'local') || (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168')) {
    $blnLocal = TRUE;
} else {
    $blnLocal = FALSE;
}

// Selon l'environnement d'exécution (développement ou production)
if ($blnLocal) {
    $strHost = 'localhost';
    $strBD = '17_pni1_tofu';       // À CHANGER
    $strUser = 'root';
    $strPassword = 'root';
    $strDsn = 'mysql:dbname=' . $strBD . ';host=' . $strHost;
    error_reporting(E_ALL);
} else {
    $strHost = 'timunix2.cegep-ste-foy.qc.ca';        // CONFIG pour timunix2
    $strBD = '17_pni1_tofuTina';       // À CHANGER
    $strUser = '17_pni1_tofuTina'; // À CHANGER
    $strPassword = '17_pni1_tofuTina';         // À CHANGER
    $strDsn = 'mysql:dbname=' . $strBD . ';host=' . $strHost;
    error_reporting(E_ALL & ~E_NOTICE);
}

//Data Source Name pour l'objet PDO
try {
//Tentative de connexion
    $pdoConnexion = new PDO($strDsn, $strUser, $strPassword);
//Changement d'encodage de l'ensemble des caractères pour UTF-8
    $pdoConnexion->exec("SET CHARACTER SET utf8");
//Pour obtenir des rapports d'erreurs et d'exception avec errorInfo()
    $pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
} catch (PDOException $e) {
    echo $e->getMessage();
}