function spinning(span, on, enlarge) {  
    if (enlarge === null) {
        enlarge = true;
    }
    
    if (on) {
        if (enlarge) {
           span.addClass("fa-spin").addClass("fa-lg");
        } else {
           span.addClass("fa-spin");
        }
    } else {
        if (enlarge) {
            span.removeClass("fa-spin").removeClass("fa-lg");
        } else {
            span.removeClass("fa-spin");            
        }
    }
}

function createPdf(button) {
    var enlarge = !button.hasClass("fa-lg");
    spinning(button, true, enlarge);
    var data = {};
    data.id = getSelectedId(button);
    $.ajax( {
        type: 'post',
        url: createUrl('search/createPdf'),
        data: {data: JSON.stringify(data)},
        success: function(result) {window.open(result.file)},
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Er is iets fout gegaan...');
        },
        complete: function() {spinning(button, false, enlarge);},
        dataType: 'json'
    });
}