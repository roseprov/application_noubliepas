/**
 * @file optionEcheance avec poignee (icone + screen-reader-text)
 *        en respect des principes de l'amélioration progressive
 * @author Krystina Hamel <krystina.hamel@gmail.com>
 * @version version jquery
 *
 */

var optionEcheance = {

    initialiser: function (evenement)
    {
        var strSens = "";
        if(!$('.optionEcheance').hasClass('datePresente'))
        {
            this.resetBlocs();
            strSens = 'down';
        }
        else
        {
            strSens = 'up';
        }
        $(".optionEcheance").prepend('<p>' +
            '<button type="button" class="optionEcheance__controle">' +
                '&nbsp;Ajouter une échéance' +
                '<span class="optionEcheance__controlePoignee">' +
                    '<span class="icone icone--chevron-'+ strSens +'"></span>' +
                    '<span class="visuallyhidden">Fermer</span>' +
                '</span>' +
            '</button></p>')
        $(".optionEcheance__controle").on('click', this.afficherCacherBloc.bind(this));
    },

    resetBlocs: function ()
    {
        $(".optionEcheance")
            .removeClass()
            .addClass("optionEcheance optionEcheance--isCollapsed");
        $(".optionEcheance__controlePoignee span.icone")
            .removeClass()
            .addClass("icone icone--chevron-down");
        $(".optionEcheance__controlePoignee .visuallyhidden").text("Ouvrir");
    },

    afficherCacherBloc: function (evenement)
    {
        if ($(evenement.currentTarget).closest('.optionEcheance').hasClass("optionEcheance--isExpanded"))
        {
            this.resetBlocs();
        }
        else
        {
            this.resetBlocs();
            $(evenement.currentTarget)
                .closest('.optionEcheance')
                .removeClass()
                .addClass("optionEcheance optionEcheance--isExpanded");
            $(evenement.currentTarget).find('span.icone')
                .removeClass()
                .addClass("icone icone--chevron-up");
            $(evenement.currentTarget).find('.visuallyhidden')
                .text("Fermer");
        }
    }

};