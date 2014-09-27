function getSelectedId(button) {
    return button.attr("id");
}

$(function() {
    $("span[role='create-pdf']").click(function() {
        createPdf($(this));
    });
});