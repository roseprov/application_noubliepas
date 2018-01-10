/**
 * @file Objet de validations avec jQuery
 * @author Nom étudiant.e <courriel etudiant.e>
 */

var validationsMandatA = {

    objJSONMessages : {
        "nomListe": {
            "erreurs": {
                "vide": "Entrez un nom de liste!",
                "motif": "Minuscules, majuscules, caractères accentués, espace, guillemet simple, trait d'union et #. Maximum 55 caractères."
            }
        },
        "echeance": {
            "erreurs": {
                "vide": "Veuillez enter une date d'échéance complète",
                "motif": "Entrez une date d'échéance valide!"
            }
        },
        "couleur": {
            "erreurs": {
                "vide": "SVP associez une couleur à cette liste!"
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
        console.log('dans verifierPattern');
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
     * @todo pour réaliser le todo précédent il faudra remplacer la méthode .text() par la méthode .html()
     * @param $objJQueryDOM
     * @param message
     */
    afficherErreur : function($objJQueryDOM, message){
        // console.log('dans afficherErreur');

        // on remonte au conteneur parent puis et on cherche à l'intérieur le conteneur pour l'erreur
        $objJQueryDOM.closest('.conteneurElementAValider').find('.erreur').text('⚠ ' + message);

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
            $legende.append('<span class="OK flaticon-success" style="color:green" aria-hidden="true"></span>')
            // Sinon on travaille directement sur l'élément de formulaire
        }else{
            $objJQueryDOM.after('<span class="OK flaticon-success" style="color:green" aria-hidden="true"></span>')
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
    },


    /***************
     * constructeur
     ***************/
    /**
     * dans la méthode initialiser on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     * @param evenement {Objet Event 'ready'}
     */
    initialiser : function(evenement){
        console.log('dans initialiser');

        // pour les champs de saisie, on peut se servir du id #
        $('#nom_liste').on('blur', this.validerTache.bind(this));

        // sur les boutons radio on se sert du name qui est commun
        $('[name=id_couleur]').on('blur', this.validerCours.bind(this));

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
        console.log('dans validerTache');

        var $objCible = $(evenement.currentTarget);
        console.log($objCible);
        this.effacerRetro($objCible);

        if (this.verifierSiVide($objCible) == true){
            this.afficherErreur($objCible, this.objJSONMessages.nomListe.erreurs.vide)
        }
        else{
            if (this.verifierPattern($objCible) == true){
                this.ajouterEncouragement($objCible);
            }
            else{
                this.afficherErreur($objCible, this.objJSONMessages.nomListe.erreurs.motif);
            }
        }
    },

    /**
     * Méthode pour valider des boutons radio
     * @param evenement {Objet Event 'blur'}
     */
    validerCours : function(evenement){
        console.log('dans validerCours');

        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        console.log("$objCible.prop('checked'): " + $objCible.prop('checked'));

        if ($objCible.prop('checked') == false){
            this.afficherErreur($objCible, this.objJSONMessages.couleur.erreurs.vide);
        }
        else{
            this.ajouterEncouragement($objCible);
        }
    }

};