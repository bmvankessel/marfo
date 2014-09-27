function deleteMaaltijdConfirmed(id) {
    var data = {};
    data.id = id;

    $.ajax( {
        type: 'post',
        url: createUrl('maaltijd/delete'),
        data: {data: JSON.stringify(data)},
        success: function(result) {
            if (result.status == 'ok') {
                updateGridView();
                openModalMessage('Verwijderen Maaltijd', 'De maaltijd is verwijderd');
            } else {
                openModalError('Verwijderen Maaltijd', '<p>De maaltijd kon niet verwijderd worden.<p><i>' + result.message + '</i>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var message = '<p>De maaltijd kon niet verwijderd worden.<p>';
            message += '<p><i>Status: ' + textStatus + '</i><p>';
            message += '<p><i>Error: ' + errorThrown + '</i></p>';
            openModalError('Verwijderen Maaltijd', message);                
        },
        dataType: 'json'
    });
}

function getSelectedId(button) {
    var row = button.closest('tr');
    var code = row.find('td:nth-child(2)').text();
    var id = row.find("td[name='id']").text();;
    return id;
}

function deleteMaaltijd(button) {
    var row = button.closest('tr');
    var code = row.find('td:nth-child(2)').text();
    var id = row.find("td[name='id']").text();;

    openModalOkCancel(
        'Maaltijd Verwijderen', 
        "Wilt u de maaltijd met code '" + code + "' verwijderen?",
        "Verwijderen", "Annuleren", 'deleteMaaltijdConfirmed(' + id + ')')
}


$(function() {

    $("button[role='maaltijd-add']").click(function() {
        var init = {};
        init['init'] = {};
        init['init']['title'] = 'Nieuwe Maaltijd';
        openDialog('dialog-template-maaltijd', init);
    });

    $("button[role='maaltijd-create']").click(function() {
    var form = $(this).closest("div[role='dialog']").find("form");
    var fields = {};

    if (validateForm(form, fields)) {
        $.ajax ({
            type: form.attr('method'),
            url: form.attr('action'),
            data: {attributes: JSON.stringify(fields)},
            dataType: "json"
        }).done(function (result) {
            options = {};
            options.title = 'Nieuwe Maaltijd';
            if (result.status == 'ok') {
                updateGridView();
                closeDialog(form);
                options.message = 'De maaltijd is toegevoegd.';
          } else {
                options = {};
                options.title = 'Nieuwe Maaltijd';
                options.html = 'Er is een technische fout opgetreden:<p><i>' + result.message + '</i></p>';
            }
            openModal('modal-template', options);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            options = {};
            options.title = 'Nieuw Subtype';
            options.html = 
                '<p>Er is een technische fout opgetreden:<p>' +
                '<p><i>Status: ' + textStatus + '</i></p>' + 
                '<p><i>Exception: ' + errorThrown + '</i></p>';
            openModal('modal-template', options);
        })
    }
  });
});