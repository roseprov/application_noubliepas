/**
 * @file Objet de validations avec jQuery
 * @author Nom étudiant.e <courriel etudiant.e>
 */

var validationsMandatB = {

    objJSONMessages : {
        "nomItem": {
            "erreurs": {
                "vide": "Entrez un nom d'item",
                "motif": "Minuscules, majuscules, caractères accentués, espace, guillemet simple, trait d'union et #. Maximum 55 caractères."
            }
        },
        "echeance": {
            "erreurs": {
                "vide": "Veuillez enter une date d'échéance complète",
                "motif": "Entrez une date d'échéance valide!"
            }
        }
    },

    /**********************
     * méthodes utilitaires
     **********************/
    /**
     * verifierSiVide reçoit un élément de formulaire et retourne true ou false
     * @param $objJQueryDOM
     * @return {boolean}
     */
    verifierSiVide : function($objJQueryDOM){
        //console.log('dans verifierSiVide');
        console.log('le value du champ est: ' + $objJQueryDOM.val());

        if($objJQueryDOM.val() == ""){
             return true;
         }
        else {
            return false;
        }
    },

    /**
     * verifierPattern reçoit un élément de formulaire et retourne true ou false
     * @param $objJQueryDOM
     * @return {boolean}
     */
    verifierPattern : function($objJQueryDOM){
        //console.log('dans verifierPattern');
        var strMotif = $objJQueryDOM.attr('pattern');
        strMotif = "^" + strMotif + "$";
        console.log('Le pattern à vérifier est: ' + strMotif );

        var regex = new RegExp(strMotif);
        return regex.test($objJQueryDOM.val());
        console.log('La chaine tester est: ' + $objJQueryDOM.val());

    },

    /**
     * afficherErreur reçoit un élément de formulaire et un texte de message d'erreur
     * @todo remplacer le caractère utf8 ⚠ par un balisage accessible d'une icône provenant d'une police d'icône
     * @param $objJQueryDOM
     * @param message
     */
    afficherErreur : function($objJQueryDOM, message){
        // console.log('dans afficherErreur');

        // on remonte au conteneur parent puis et on cherche à l'intérieur le conteneur pour l'erreur
        $objJQueryDOM.closest('.conteneurElementAValider').find('.erreur').text(message).addClass('flaticon-warning');
        $objJQueryDOM.closest('.conteneurElementAValider').find('input').addClass('inputErreur');

        // On vérifie si le parent a une balise legend
        $parent = $objJQueryDOM.closest('.conteneurElementAValider');
        $legende = $parent.find('legend');
        if($legende.length){
            $parent.addClass('erreurElement');
            // Sinon on travaille directement sur l'élément de formulaire
        }else{
            $objJQueryDOM.addClass('erreurElement');
        }
    },

    /**
     * ajouterEncouragement reçoit un élément de formulaire
     * @todo adapter le html de l'encouragement
     * @param $objJQueryDOM
     */
    ajouterEncouragement : function ($objJQueryDOM){
        // On vérifie si le parent a une balise legend
        $legende = $objJQueryDOM.closest('.conteneurElementAValider').find('legend');
        if($legende.length){
            $legende.append('<span class="OK flaticon-success" aria-hidden="true"></span>')
        // Sinon on travaille directement sur l'élément de formulaire
        }else{
            $objJQueryDOM.after('<span class="OK flaticon-success" aria-hidden="true"></span>')
        }
    },

    /**
     * effacerRetro reçoit un élément de formulaire
     * @param $objJQueryDOM
     */
    effacerRetro : function ($objJQueryDOM){
        $parent = $objJQueryDOM.closest('.conteneurElementAValider');
        $legende = $parent.find('legend');
        if($legende.length){
            $parent.removeClass('erreurElement');
        }else{
            $objJQueryDOM.removeClass('erreurElement');
        }
        $objJQueryDOM.closest('.conteneurElementAValider').find('.erreur').text('');
        $objJQueryDOM.closest('.conteneurElementAValider').find('.OK').remove();
        $objJQueryDOM.closest('.conteneurElementAValider').find('.inputErreur').removeClass();
        $objJQueryDOM.closest('.conteneurElementAValider').find('.flaticon-warning').removeClass();
    },


    /***************
     * constructeur
     ***************/
    /**
     * dans la méthode initialiser on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     * @param evenement {Objet Event 'ready'}
     */
    initialiser : function(evenement){
        //console.log('dans initialiser');

        // pour les champs de saisie, on peut se servir du id #
        $('#nom_item').on('blur', this.validerTache.bind(this));

        // sur la date d'échéance, on validera seulement au sortir du dernier select : l'année
        $('#annee').on('blur', this.validerAnnee.bind(this));
    },

    /******************************************************************************************
     * méthodes spécifiques
     * On ajoute une méthode validerQuelqueChose pour chaque élément de formulaire à valider
     ******************************************************************************************/
    /**
     * Méthode pour valider les input dont le type supporte l'attribut pattern
     * text, date, search, url, tel, email, password
     * @param evenement {Objet Event 'blur'}
     */
    validerTache : function(evenement){
        //console.log('dans validerTache');

        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        if (this.verifierSiVide($objCible) == true){
            this.afficherErreur($objCible, this.objJSONMessages.nomItem.erreurs.vide)
        }
        else{
            if (this.verifierPattern($objCible) == true){
                this.ajouterEncouragement($objCible);
            }
            else{
                this.afficherErreur($objCible, this.objJSONMessages.nomItem.erreurs.motif);
            }
        }
    },

    /**
     * Méthode pour valider des boutons radio
     * @param evenement {Objet Event 'blur'}
     */
    validerCours : function(evenement){
        //console.log('dans validerCours');

        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        console.log("$objCible.prop('checked'): " + $objCible.prop('checked'));

        if ($objCible.prop('checked') == false){
            this.afficherErreur($objCible, this.objJSONMessages.cours.erreurs.vide);
        }
        else{
            this.ajouterEncouragement($objCible);
        }
    },

    /**
     * Méthode pour valider une date à partir de trois select:
     * #jour - #mois - #annee
     * @param evenement {Objet Event 'blur'}
     */
    validerAnnee : function(evenement){
        //console.log('dans validerAnnee');

        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        // L'échéance est facultative mais si l'utilisateur entre une date incomplète il faut afficher une erreur
        // donc on vérifie si l'un des select n'est pas null
        if ($objCible.val() !== 'null' || $('#mois').val() !== 'null' || $('#jour').val() !== 'null'){
            // si oui on vérifie que TOUS les select de date sont complétés
            if ($objCible.val() !== 'null' && $('#mois').val() !== 'null' && $('#jour').val() !== 'null'){
                // ici on pourrait ajouter d'autres validations comme de vérifier s'il s'agit d'une date valide: pas un 30 février par exemple!
                this.ajouterEncouragement($objCible);
            }
            else{
                this.afficherErreur($objCible, this.objJSONMessages.echeance.erreurs.vide);
            }
        }
        else {
            // on ne fait rien puisque la date est facultative
        }
    }

};