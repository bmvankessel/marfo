function formData(form) {
    var arrayData = form.serializeArray();    
    var formData = {};
    
    for(var i=0; i <arrayData.length; i++) {
        formData[arrayData[i].name] = arrayData[i].value;
    }
    
    // serializeArray() only returns the checked check boxes
    // add unchecked check boxes to formData
    form.find("input:checkbox:not(:checked)").each(function() {
        formData[$(this).attr('name')] = 0;
    });
    
    return formData;
}

function editModeNone() {
    return ($(".mode-edit").length == 0);
}

function setMode(element, modeNormal, save) {
    if (modeNormal) {
        if (save) {save = true;} 
        else {save = false}
        element.removeClass("mode-edit");
        element.addClass("mode-normal");

        i=1;
        element.find("div[data-role='input-group']").each(function() {
            var label = $(this).find("div[role='label']").find("span").first();
            var inputElement = $(this).find("div[role='input']").children().first();
            var inputElementTag = inputElement.prop("tagName").toLowerCase();
            
            if (inputElementTag == 'input') {
                if (save) {
                    label.text(inputElement.val())
                } else {
                    inputElement.val(label.text());
                }
            } else if (inputElementTag == 'div' && inputElement.hasClass('radio')) {
                if (save) {
                    inputElement.parent().find("label").each(function(){
                        var input = $(this).find("input");
                        var inputValue = $(this).text().trim();
                        if (input.prop('checked')) {
                            label.text(inputValue)
                        }
                    });                    
                } else {
                    var labelValue = label.text().toLowerCase().trim();
                    inputElement.parent().find("label").each(function(){
                        var input = $(this).find("input");
                        var inputValue = $(this).text().toLowerCase().trim();
                        if (labelValue == inputValue) {
                            input.prop('checked', true);
                        } else {
                            input.prop('checked', false);                        
                        }
                    });                    
                }
            } else if (inputElementTag == 'select') {
                if (save) {
                    var value = inputElement.val();
                    inputElement.find("option").each(function(){
                        var option = $(this);
                        var optionValue = option.text().toLowerCase().trim();
                        if (value == option.attr('value')) {
                            label.text(optionValue);
                        }
                    });                    
                } else {
                    var value;
                    inputElement.find("option").each(function(){
                        var labelValue = label.text().toLowerCase().trim();
                        var optionValue = $(this).text().toLowerCase().trim();

                        if (labelValue == optionValue) {
                            value = $(this).attr('value');
                        }
                        inputElement.val(value);
                    });                    
                }
            } else if (inputElementTag == 'textarea') {
                if (save) {
                    label.text(inputElement.val());                    
                } else {
                    inputElement.val(label.text());
                }
            } else if (inputElementTag == 'div' && inputElement.find("input[type='checkbox']").length == 1) {
                if (save) {
                    inputElement = inputElement.find("input[type='checkbox']");
                    if (label.find("input[type='checkbox']").length == 1) {
                        label = label.find("input[type='checkbox']");
                        label.prop('checked',inputElement.prop('checked'));
                    } else {
                        var displayValue = JSON.parse(inputElement.parent().attr('display-values'));                    
                        var displayValueTrue = displayValue['true'].toLowerCase().trim();
                        var displayValueFalse = displayValue['false'].toLowerCase().trim();
                        
                        if (inputElement.prop('checked')) {
                            label.html(displayValueTrue);
                        } else {
                            label.html(displayValueFalse);                 
                        }
                    }                    
                } else {
                    inputElement = inputElement.find("input[type='checkbox']");
                    if (label.find("input[type='checkbox']").length == 1) {
                        label = label.find("input[type='checkbox']");
                        inputElement.prop('checked', label.prop('checked'));
                    } else {
                        var labelValue = label.html().toLowerCase().trim();
                        var displayValue = JSON.parse(inputElement.parent().attr('display-values'));                    
                        var displayValueTrue = displayValue['true'].toLowerCase().trim();

                        inputElement.prop('checked', labelValue == displayValueTrue);
                    }
                }
            } else {
                alert('Element ' + inputElementTag + ' not supported');                
            }            
        });
    } else {
        element.removeClass("mode-normal");
        element.addClass("mode-edit");
    }
}

$("button[role=edit]").click(function(){
    if (editModeNone()) {
        var element =$(this).closest(".mode-normal");
        setMode(element, false);
    } else {
        alert('Al in edit mode!');
    }
});

$("button[role=save]").click(function(){
    var panel = $(this).closest(".mode-edit");
    var form = panel.find('form');
    update(JSON.stringify(formData(form)), form.attr('action'), panel);
});

$("button[role=cancel]").click(function(){
    var element =$(this).closest(".mode-edit");
    setMode(element, true);
});

function update(formData, action, panel) {
    $.ajax({
        type: "POST",
        url: action,
        data: {data: formData},
        dataType: "json",
        })
    .done(function(result) {
        if (result.status === 'ok') {
            setMode(panel, true, true);
//            updatePanel(panel);
        } else {
            setMode(panel, true, false);
            alert(result.message);
        }
     })
    .fail(function() {alert("Not OK");});
}

/*
function updatePanel(panel) {
    panel.find("[data-role='input-group']").each(function() {
        var inputGroup = $(this);
        
        var label = inputGroup.find("[role='label']");
        var input = inputGroup.find("[role='input']");

        var inputElement = null;
        if (input.find("select").length > 0) {
            inputElement = input.find("select");
            var option = inputElement.find("option[value='" + inputElement.val() + "']");
            label.text(option.text());
        } else if (input.find("div.radio").length > 0) {
            inputElement = input.find("div.radio");
            label.text(inputElement.find("input:checked").closest("label").text().trim());
        } else if (input.find("div.checkbox").length > 0) {
            inputElement = input.find("div.checkbox");
            label.find("input[type='checkbox']").prop("checked", inputElement.find("input").prop("checked"));
        } else if (input.find("input[type='text']").length > 0){
            inputElement = input.find("input[type='text']");
            label.text(inputElement.val());            
        } else if (input.find("textarea[type='text']").length > 0){
            inputElement = input.find("textarea[type='text']");
            label.text(inputElement.val());            
        } else {
            alert('unknown input element');
        }
    });
}
*/