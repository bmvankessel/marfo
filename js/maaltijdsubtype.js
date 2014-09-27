function initDialogMaaltijdSubType(dialog, data) {
    // change button caption
    var button = dialog.find("button[role='maaltijdsubtype-create']");
    button.text('Wijzigen');
    
    var form = dialog.find("form");

    // add input field form id
    var inputId = '<input type="hidden" name="id" value="' + data.id + '">';
    form.append(inputId);

    // set field with value
    form.find("input[name='omschrijving']").val(data.omschrijving);
    
    // change action create into update
    form.attr('action', form.attr('action').replace('Create', 'Update'));
}

function deleteMaaltijdSubTypeConfirmed(id) {
    var title = 'Verwijderen Subtype';
    var data = {};
    data.id = id;

    $.ajax( {
        type: 'post',
        url: createUrl('maaltijdsubtype/delete'),
        data: {data: JSON.stringify(data)},
        success: function(result) {
            var options = {};
            options.title = title;
            if (result.status == 'ok') {
                updateGridView();
                openModalMessage(title, 'Het subtype is verwijderd');
            } else {
                openModalError(title, '<p>Het subtype kon niet verwijderd worden.<p><i>' + result.message + '</i>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var message = '<p>Het subtype kon niet verwijderd worden.<p>';
            message += '<p><i>Status: ' + textStatus + '</i><p>';
            message += '<p><i>Error: ' + errorThrown + '</i></p>';
            openModalError(title, message);                
        },
        dataType: 'json'
    });
}

function deleteMaaltijdSubType(button) {
    var data = {};
    var title = 'Verwijder Subtype';
    var row = button.closest('tr');
    var omschrijving = row.find('td:nth-child(2)').text();
    data.id = row.find("td[name='id']").text();

    $.ajax( {
        type: 'post',
        url: createUrl('maaltijdsubtype/relatedParents'),
        data: {data: JSON.stringify(data)},
        success: function(result) {
            if (result.status == 'ok') {
                var message = "<p>Wilt u het subtype '" + omschrijving + "' verwijderen?<p>";
                
                result.parentCount = parseInt(result.parentCount);

                switch (result.parentCount) {
                    case 0:
                        break;
                    case 1:
                        message += "<p><b>Het subtype wordt gebruikt door 1 maaltijd.</b></p>";
                        message += "<p><b>Het subtype in de maaltijd zal gewist worden.</b></p>";
                        break;
                    default:
                        message += "Het subtype wordt gebruikt door " + result.parentCount + " maaltijden.";
                        message += "<p><b>Het subtype in de maaltijden zal gewist worden.</b></p>";
                        break;
                }
                openModalOkCancel(title, message, "Verwijderen", "Annuleren", "deleteMaaltijdSubTypeConfirmed(" + data.id + ")");
            } else {
                openModalError(title, '<p>Het maaltijdtype kon niet verwijderd worden.<p><i>' + result.message + '</i>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var message = '<p>Het subtype kon niet verwijderd worden.<p>';
            message += '<p><i>Status: ' + textStatus + '</i><p>';
            message += '<p><i>Error: ' + errorThrown + '</i></p>';
            openModalError(title, message);                
        },
        dataType: 'json'
    });
}

function updateMaaltijdSubType(button) {
    var row = button.closest("tr");
    var omschrijving = row.find("td:nth-child(2)").text();
    var id = row.find("td:last-child").text();
    
    var init = {};
    init.init = {};
    init.init.title = 'Wijzigen Subtype';
    init.init.method = 'initDialogMaaltijdSubType';
    init.data = {};
    init.data.id = id;
    init.data.omschrijving = omschrijving;
    
    openDialog('dialog-template-maaltijdsubtype', init);
}

$(function() {

    $("button[role='maaltijdsubtype-add']").click(function() {
        var init = {};
        init['init'] = {};
        init['init']['title'] = 'Nieuw Subtype';
        openDialog('dialog-template-maaltijdsubtype', init);
    });

    $("button[role='maaltijdsubtype-create']").click(function() {
        var form = $(this).closest("div[role='dialog']").find("form");
        var fields = {};
        var title = '';
        var message = '';
        if (form.attr('action').search('Update') > -1) {
            title = 'Wijzig Subtype';
            message = 'Het subtype is gewijzigd.';
        } else {
            title = 'Nieuw Subtype';
            message = 'Het subtype is toegevoegd.';
        }

        if (validateForm(form, fields)) {
            $.ajax ({
                type: form.attr('method'),
                url: form.attr('action'),
                data: {attributes: JSON.stringify(fields)},
                dataType: "json"
            }).done(function (result) {
                var options = {};
                options.title = title;
                if (result.status == 'ok') {
                    closeDialog(form);
                    updateGridView();
                    options.message = message;
              } else {
                    options = {};
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