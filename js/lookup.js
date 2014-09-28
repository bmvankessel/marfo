/**
 * Sets the caption of the action button.
 * 
 * @param string caption	Caption for the action button.
 */
function setModalLookupActionButtonCaption(caption) {
 $("#btn-lookup-action").text(caption);
}

/**
 * Sets the modus of the modal lookup form.
 * 
 * @param boolean create	Modus create (true) or update (true).
 */
function setModalLookupModus(create) {
	modus = (create === true) ? 'create' : 'update';
	$("#target-lookup").attr('data-modus', modus);
}

/**
 * Returns the modus of the lookup form.
 * 
 * @return boolean	Modus create true/false.
 */
function modusModalLookupIsCreate() {
	return ($("#target-lookup").attr('data-modus') == 'create') ? true : false;
}

/**
 * Displays the modal form as a message box.
 * 
 * @param string message	Message to display.
 */
function displayModalLookupAsMessageBox(message) {
	displayModalLookupEntryConrols(false);
	showModalAddLookupMessage(message, false);
	displayModalLookupActionButton(false);
}

/**
 * Clears value entry.
 */
function clearModalLookupValue() {
	$("#lookup-description").val('');
}

/**
 * Sets the value entry.
 * 
 * @param string value	Entry value.
 */
function setModalLookupValue(value) {
	$("#lookup-description").val(value);
} 

/**
 * Returns the value entry.
 */
function getModalLookupValue(value) {
	return $("#lookup-description").val();
} 

/**
 * Sets the id of the lookup value.
 * 
 * @param int id	Id of the lookup value.
 */
function setModalLookupId(id) {
	$("#lookup-id").val(id);
} 

/**
 * Returns the id of the lookup value.
 *
 * @return int	Id of the lookup value.
 */
function getModalLookupId() {
	return $("#lookup-id").val();
}

/**
 * Shows or hides the input entries
 * 
 * @param boolean display	Show / hide the entry controls.
 */
function displayModalLookupEntryConrols(display) {
	display = defaultTo(display, true);
	form = $("#target-lookup");
	if (display === true) {
		show(form);
	} else {
		hide(form);
	}
}

/**
 * Shows or hides the confirm button.
 * 
 * @param boolean display	Show / hide the confirm button.
 */
function displayModalLookupActionButton(display) {
	display = defaultTo(display, true);
	button = $("#btn-lookup-action");

	if (display === true) {
		show(button);
	} else {
		hide(button);
	}
}

/**
 * Clears the message in the modal screen for adding a lookup value.
 */
function clearModalLookupMessage() {
	messageContainer = $("#message-lookup-add");
	messageContainer.text('');
}

/**
 * Displays the container for the messages.
 * 
 * @param boolean display	Show / hides the container message.
 */
function displayModalLookupMessage(display) {
	display = defaultTo(display, true);
	messageContainer = $("#message-lookup");
	if (display) {
		show(messageContainer);
	} else {
		hide(messageContainer);
	}
}

/**
 * Shows a message in the modal screen for adding a lookup value.
 */
function showModalAddLookupMessage(message, warning) {
	warning = defaultTo(warning, true);
	clearModalLookupMessage();
	messageContainer = $("#message-lookup");
	if (warning === true) {
		messageContainer.removeClass('alert-success').addClass('alert-danger');
	} else {
		messageContainer.removeClass('alert-danger').addClass('alert-success');		
	}
	messageContainer.text(message);
	show(messageContainer);
}

/**
 * Returns the action url.
 * 
 * @returns string	Action url.
 */
function getModalLookupActionUrl() {
	form = $("#target-lookup");
	if (modusModalLookupIsCreate() === true) {
		return form.attr('data-action-create'); 
	} else {
		return form.attr('data-action-update'); 
	}
}

/**
 * Initialize the modal form.
 */
function initModalLookup() {
	if (modusModalLookupIsCreate()) {
		setModalLookupActionButtonCaption('Toevoegen');
		clearModalLookupValue();
	} else {
		setModalLookupActionButtonCaption('Wijzigen');
}
	displayModalLookupEntryConrols();
	displayModalLookupActionButton();
	displayModalLookupMessage(false);
}

/**
 * Adds or edits a lookup value.
 */ 
function actionModalLookup() {
	displayModalLookupMessage(false);
	description = getModalLookupValue();
	
	if (modusModalLookupIsCreate()) {
		data = JSON.stringify({'description': description});
	} else {
		id = getModalLookupId();
		data = JSON.stringify({'id': id, 'description': description});
	}
	
	if (description.length > 0) {
		$.ajax({
			method: 'post',
			url: getModalLookupActionUrl(),
			data: {'data': data},
			dataType: 'json',
		})
		.done(function(result) {
			if (result.status !== 'ok') {
				showModalAddLookupMessage(result.message);
			} else {
				if (modusModalLookupIsCreate() === true) {
					displayModalLookupAsMessageBox('Omschrijving toegevoegd.');
				} else {
					$("#lup_" + id).find("td[name]").text(description);
					displayModalLookupAsMessageBox('Omschrijving gewijzigd.');
				}
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			showModalAddLookupMessage('Error: ' + errorThrown);
		});
	} else {
		showModalAddLookupMessage("Omschrijving is verplicht");
	}
}

/**
 * Opens the modal view.
 * 
 * @param object column	Html action column.
 */
function openModalLookupForEdit(htmlColumn) {
	row = htmlColumn.closest("tr");
	id = row.find("td[name='id']").text();
	row.attr('id', 'lup_' + id);
	description = row.find("td[name='description']").text();
	setModalLookupModus(false);
	setModalLookupId(id);
	setModalLookupValue(description);
	$("#modal-lookup").modal('show');
}

$(document).ready(function() {
	$("#btn-lookup-action").click(function() {
		actionModalLookup();
	});
	
	$("#modal-lookup").on('show.bs.modal', function() {
		initModalLookup();
	});
}); 
