function closeModal(element) {
    element.closest("div[id^='modal']").modal('hide');
}

function openModalMessage(title, message, templateId) {
    var options = {};
    options.title = title;
    options.html = message;
    if (!templateId) {
        templateId = 'modal-template';
    }
    
    openModal(templateId, options);
}

function openModalError(title, message, templateId) {
    var options = {};
    options.title = title;
    options.html = message;
    options.headerCssClass = 'bg-danger';
    if (!templateId) {
        templateId = 'modal-template';
    }
    
    openModal(templateId, options);    
}

function openModalOkCancel(title, message, labelOk, labelCancel, functionOk, templateId) {
    if (!templateId) {templateId = 'modal-template';}
    var options = {};
    options.title = title;
    options.html = message;
    options.buttons = [];
    
    var button = {};
    button.role = "ok";
    
    if (labelOk) {button.label = labelOk;} 
    else {button.label = "Ok";}
    
    if(functionOk) {button.action = functionOk;}
    button.delayAfterClose = true;    
    
    options.buttons.push(button);
    
    button = {};
    button.role = "cancel";
    
    if (labelCancel) {button.label = labelCancel;} 
    else {button.label = "Cancel";}
    
    options.buttons.push(button);
    
    openModal(templateId, options);
}

function openModal(templateId, options) {
    var modal = $("#" + templateId).clone();
    var id = templateId.replace('template', 'live');
    
    modal.attr('id', id);
    options.size = 'small';
    if (!options.headerCssClass) {options.headerCssClass = 'bg-success';}
    
    modal.attr('id', templateId.replace('template', 'live'))
    
    modal.find(".modal-header").addClass(options.headerCssClass);
    
    modal.on('hidden.bs.modal', function() {$(this).remove();});
    
    if (options) {
        if (options.title) {modal.find('.modal-title').text(options.title);}
        
        if (options.message) {modal.find('.modal-body').find('p').text(options.message);}

        if (options.html) {modal.find('.modal-body').find('p').html(options.html);}
        
        if (options.size) {
            switch(options.size) {
                case 'small':
                    modal.find("div.modal-dialog").removeClass('modal-lg').addClass('modal-sm');
                    break;
                default:
                    modal.find("div.modal-dialog").removeClass('modal-sm').addClass('modal-lg');
                    break;
            }
        }
        
        if (options.buttons) {
            var footer = modal.find(".modal-footer");
            for (var i=0; i<options.buttons.length; i++) {
                var button = options.buttons[i];
                if (!button.class) {button.class = 'btn-default'}
                if (button.role == "cancel" || button.role == "close") {
                    var btn = modal.find("button[role='close']");
                    btn.text(button.label);
                    btn.addClass(button.class);
                } else {
                    var htmlButton = '<button type="button" role="' + button.role + '" class="btn pull-left ' + button.class + '">' +  button.label + '</button>';
                    
                    footer.prepend(htmlButton);
                }            
            }
        }
    }
    
    
    $("body").append(modal);
    modal = $("body").find("#" + id);
    
    if (options && options.buttons) {
        for (var i=0; i<options.buttons.length;i++) {
            var button = options.buttons[i];
            
            if (button.action && button.role) {
                var action = button.action;
                if (button.delayAfterClose) {
                    $("button[role='" + button.role + "']").click(function() {
                        closeModal($(this));
                        setTimeout(function(){ eval(action);}, 1250);
                    });    
                } else {
                    $("button[role='" + button.role + "']").click(function() {
                        closeModal($(this));
                        eval(action);
                    });                        
                }
            }
        }
}
    
    modal.modal('show');
}