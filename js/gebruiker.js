function deleteGebruiker(button) {
    var row = button.closest('tr');
    var gebruikersnaam = row.find('td:nth-child(3)').text();
    var id = row.find("td[name='id']").text();;

    openModalOkCancel(
        'Gebruiker Verwijderen', 
        "Wilt u de gebruiker met gebruikersnaam '" + gebruikersnaam + "' verwijderen?",
        "Verwijderen", "Annuleren", 'deleteGebruikerConfirmed(' + id + ')')
}

function deleteGebruikerConfirmed(id) {
    var title = 'Verwijderen Gebruiker';
    var data = {};
    data.id = id;

    $.ajax( {
        type: 'post',
        url: createUrl('gebruiker/delete'),
        data: {data: JSON.stringify(data)},
        success: function(result) {
            if (result.status == 'ok') {
                updateGridView();
                openModalMessage(title, 'De gebruiker is verwijderd');
            } else {
                openModalError(title, '<p>De gebruiker kon niet verwijderd worden.<p><i>' + result.message + '</i>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var message = '<p>De gebruiker kon niet verwijderd worden.<p>';
            message += '<p><i>Status: ' + textStatus + '</i><p>';
            message += '<p><i>Error: ' + errorThrown + '</i></p>';
            openModalError(title, message);                
        },
        dataType: 'json'
    });
}

$(function() {
        $("button[role='maaltijd-add']").click(function() {
        var init = {};
        init['init'] = {};
        init['init']['title'] = 'Nieuwe Maaltijd';
        openDialog('dialog-template-maaltijd', init);
    });
});