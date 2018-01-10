/**
 * @file    Un menu hamburger mobile
 * @author @evefevrier <eve.fevrier@cegep-ste-foy.qc.ca>
 * @version 1.3 version jquery avec icones svg
 *
 * le button est ajouté au nav.menu
 * le modificateur --fermer est ajouté au ul.menu__liste du menu
 */

//*******************
// Déclaration d'objet(s)
//*******************

var menu = {
    lblMenuFerme: 'Menu',
    lblMenuOuvert: 'Fermer',
    svgHamburger: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icone icone--hamburger"><path d="M2 6h20v3H2zm0 5h20v3H2zm0 5h20v3H2z"/></svg>',
    svgX: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" class="icone icone--x"> <path d="M22.3 81.9C6.1 65.8 6 39 22.5 22.5c16.6-16.6 43.1-16.6 59.4-.2l418.1 418 418.1-418c16.1-16.1 43-16.3 59.5.2 16.6 16.6 16.6 43.1.2 59.5L559.7 500l418.1 418.1c16.1 16.1 16.3 43-.2 59.5-16.6 16.6-43.1 16.6-59.5.2L500 559.7l-418.1 418c-16.1 16.1-43 16.3-59.5-.2-16.6-16.6-16.6-43.1-.2-59.5l418.1-418-418-418.1z"/></svg>',

    configurerNav: function ()
    {
        //********** Création du bouton du menu mobile

        // On crée un bouton et son libellé avec les bonnes classes avant le menu
        $(".menu").prepend('<button class="menu__controle"> ' +
            '<span class="menu__icone">'+this.svgHamburger+'</span> ' +
            '<span class="menu__libelle">'+this.lblMenuFerme+'</span></button>');

        // Ajout de l'écouteur d'événement sur le bouton du menu
        $(".menu__controle").on('click', menu.ouvrirFermerNav.bind(menu));
    },

    /**
     * Selon l'état du menu --fermer ou --ouvert
     * On change le libellé du bouton et l'icône SVG
     */
    ouvrirFermerNav: function ()
    {
        if($(".menu__liste").hasClass("menu__liste--ouvert"))
        {
            $(".menu__liste").removeClass().addClass("menu__liste menu__liste--fermer");
            $(".menu__libelle").html(this.lblMenuFerme);
            $(".menu__icone").html(this.svgHamburger);
        }
        else
        {
            $(".menu__liste").removeClass().addClass("menu__liste menu__liste--ouvert");
            $(".menu__libelle").html(this.lblMenuOuvert);
            $(".menu__icone").html(this.svgX);
        }

    }
};