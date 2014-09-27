function validateForm(form, formFields) {
    var valid = true;
    var fields = form.serializeArray();
    
    for (var i=0; i < fields.length; i++) {
        formFields[fields[i].name] = fields[i].value;
        valid &= validateField(form, fields[i].name);        
    }
    
    return valid;
}

function validateField(form, name) {
    var valid = false;

    hideError(form, name);
    var input = form.find("input[name='" + name + "']");
    
    if (input.hasClass('required')) {
        var value = input.val();

        if (value.length > 0) {
            if (value.trim().length == 0) {
                displayError(form, name, "Veld mag niet alleen spaties bevatten");
            } else {
                if (input.hasClass('number')) {
                    alert('Controleren op numeriek');
                } else if (input.hasClass('float')) {
                    alert('Controleren op gebroken getal');
                } else if (input.hasClass('numberstring')) {
                    alert('Controleren op tekst met alleen cijfers (voorloopnullen)');                    
                } else {
                    // doe niets
                    valid = true;
                }
            }
        } else {
            if (input.hasClass('required')) {
                displayError(form, name, "Veld is verplicht");
            } else {
                valid = true;
            }
        }        
    } else {
        valid = true;
    }
    
    return valid;
}

function displayError(form, name, message) {
    var input = form.find("input[name='" + name + "']");
    var group = input.closest("div.form-group");

    if (group.length > 0) {
        group.addClass("has-feedback");
        group.addClass("has-error");
        
        group.append('<span class="glyphicon glyphicon-info-sign form-control-feedback"></span>');
        group.append('<div role="display-error">' + message + '</div>');
    }
}

function hideError(form, name) {
    var input = form.find("input[name='" + name + "']");
    var group = input.closest("div.form-group");
    
    if (group.length > 0) {
        group.removeClass("has-feedback");
        group.removeClass("has-error");
        
        group.find("span.glyphicon").remove();
        group.find("div[role='display-error']").remove();
        
    }    
}

function dialogFields(form) {
    var fields = {};
    var formFields = form.serializeArray();

    for(var i=0; i<formFields.length; i++) {
        fields[formFields[i].name] = formFields[i].value;
    }

    return fields;
}

function closeDialog(dialogElement) {
    var dialog = dialogElement.closest("div[role='dialog']");
    dialog.dialog("close");
}

function openDialog(templateId, dialogInit) {
    // create dialog from template
    var selector = "#" + templateId;
    var dialog = $(selector).clone(true);
        
    // set the id for the dialog
    var dialogId = templateId.replace('template', 'live');
    selector = "#" + dialogId;

    // check if there is already a copy of the dialog present/open
    if ($(selector).length == 0) {
        // dialog not open
        
        dialog.attr('id', dialogId);
        dialog.attr('role', 'dialog');

        // add the dialog to the body
        dialog.appendTo('body');

        var options = {};
        options['autoOpen']= false;
        options['resizable']= false;
        options['close']= function(event, ui){$(this).remove();}

        // call custom dialog init if set 
        if (dialogInit) {
            if (dialogInit['init']) {
                var init = dialogInit['init'];
                var initMethod = '';
                
                if (init['title']) {
                    options['title'] = init['title'];
                }
                
                if (dialogInit.data) {
                    initMethod += "(dialog, dialogInit['data'])";
                } else {
                    initMethod += "(dialog)";
                }

                if (init.method) {
                    // name of init method plus parameters
                    initMethod = init.method + initMethod
                    eval(initMethod);
                }
            }
        }

        // get a reference to the new dialog
        selector = "#" + dialogId;
        var dialog = $(selector);

        // initialize the dialog
        
        dialog.dialog(options);
//            {
//                autoOpen: false, 
//                resizable: false, 
//                close: function(event, ui){$(this).remove();}
//            });
//        dialog.dialog({autoOpen: false, resizable: false});

        dialog.find("button[role='cancel']").click(function() {
            closeDialog($(this));
        });
        

        // templates are hidden, make the dialog visible
        dialog.removeClass('hidden');
        //open the dialog
        dialog.dialog("open");
    } else {
        // dialog already open
        // ignore call
    }
}