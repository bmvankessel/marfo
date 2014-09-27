/**
 * Transform lookup into message box.
 */
function messageBoxModalAddLookupMessage(message) {
	form = $("#target-lookup-add");
	hide(form);
	showModalAddLookupMessage(message, false);
	button = $("#btn-lookup-add");
	hide(button);
}

/**
 * Clears the message in the modal screen for adding a lookup value.
 */
function clearModalAddLookupMessage() {
	messageContainer = $("#message-lookup-add");
	messageContainer.text('');
}

/**
 * Hides the container for displaying messages.
 */
function hideModalAddLookupMessage() {
	hide($("#message-lookup-add"));
}

/**
 * Shows a message in the modal screen for adding a lookup value.
 */
function showModalAddLookupMessage(message, warning) {
	warning = defaultTo(warning, true);
	clearModalAddLookupMessage();
	messageContainer = $("#message-lookup-add");
	if (warning === true) {
		messageContainer.removeClass('alert-success').addClass('alert-danger');
	} else {
		messageContainer.removeClass('alert-danger').addClass('alert-success');		
	}
	messageContainer.text(message);
	show(messageContainer);
}

/**
 * Adds a lookup value.
 */ 
function addLookup() {
	hideModalAddLookupMessage();
	description = $("#lookup-description").val();
	if (description.length > 0) {
		$.ajax({
			method: 'post',
			url: $("#target-lookup-add").attr('action'),
			data: {'description': JSON.stringify(description)},
			dataType: 'json',
		})
		.done(function(result) {
			if (result.status !== 'ok') {
				showModalAddLookupMessage(result.message);
			} else {
				messageBoxModalAddLookupMessage('Omschrijving toegevoegd.');
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			showModalAddLookupMessage('Error: ' + errorThrown);
		});
	} else {
		showModalAddLookupMessage("Omschrijving is verplicht");
	}
}

$(document).ready(function() {
	$("#btn-lookup-add").click(function() {
		addLookup();
	});
}); 
