function spinning(id, on) {
    var span = $("#"+id);
    if (on) {
        span.addClass("fa-spin");
    } else {
        span.removeClass("fa-spin");
    }
}

function createPdf(id) {
    spinning(id,true);
    var data = {};
    data.id = id;
    $.ajax( {
        type: 'post',
        url: createUrl('search/createPdf'),
        data: {data: JSON.stringify(data)},
        success: function(result) {
            window.open(result.file);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Er is iets fout gegaan...');
        },
        complete: function() {spinning(id, false);},
        dataType: 'json'
    });
}

function setSearchOptionIcons(selector) {
    $(selector).find("ul[class*='collaps']").each(function() {
        var ul = $(this);
        if (ul.hasClass("in")) {
            ul.prev().find("span.icon").removeClass("fa-caret-right").addClass("fa-caret-down");
        } else {
            ul.prev().find("span.icon").removeClass("fa-caret-down").addClass("fa-caret-right");
        }
    });
}

function assignPdfCreation() {
    $(".meal").click(
        function(){
            id = $(this).closest("tr").find("span.fa").attr('id');
            createPdf(id);
        }
    );    
}

$(function() {
    setSearchOptionIcons("ul.search");

    $("ul.search").on("shown.bs.collapse", function() {
        setSearchOptionIcons("ul.search");
    });

    $("ul.search").on("hidden.bs.collapse", function() {
        setSearchOptionIcons("ul.search");
    });
    
    assignPdfCreation();
});