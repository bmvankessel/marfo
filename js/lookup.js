/**
 * Sets the caption of the action button.
 * 
 * @param string caption			Caption for the action button.
 * @param boolean modalFormDelete	Form containing the button.
 */
function setModalLookupActionButtonCaption(caption, modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	if (modalFormDelete === true) {
		$("#btn-lookup-delete").text(caption);
	} else {
		$("#btn-lookup-action").text(caption);
	}
}

/**
 * Sets the modus of the modal lookup form.
 * 
 * @param boolean create	Modus create (true) or update (true).
 */
function setModalLookupModus(create) {
	modus = (create === true) ? 'create' : 'update';
	getModalLookupForm(false).attr('data-modus', modus);
}

/**
 * Returns the modus of the lookup form.
 * 
 * @return boolean	Modus create true/false.
 */
function modusModalLookupIsCreate() {
	return (getModalLookupForm(false).attr('data-modus') == 'create') ? true : false;
}

/**
 * Displays the modal form as a message box.
 * 
 * @param string message			Message to display.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * 
 */
function displayModalLookupAsMessageBox(message, modalFormDelete, warning) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	warning = defaultTo(warning, false);
	if (modalFormDelete === true) {
		displayModalLookupEntryConrols(false);
		displayModalLookupMessage();
	}
	showModalLookupMessage(message, modalFormDelete, warning);
	displayModalLookupActionButton(false, modalFormDelete);
}

/**
 * Clears value entry.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function clearModalLookupValue(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	setModalLookupValue('description','', modalFormDelete);
}

/**
 * Returns the form element within the modal form.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function getModalLookupForm(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	if (modalFormDelete === true) {
		return $("#form-lookup-delete");
	} else {
		return $("#form-lookup");
	}
}

/**
 * Sets the field value for a modal form.
 * 
 * @param string name				Field name to set.
 * @param string value				Field value to set.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function setModalLookupValue(name, value, modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	getModalLookupForm(modalFormDelete).find("input[name='" + name + "']").val(value);
} 

/**
 * Gets the field value from a modal form.
 * 
 * @param string name				Field name to set.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * 
 * @return string					Field value.
 */
function getModalLookupValue(name, modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	return getModalLookupForm(modalFormDelete).find("input[name='" + name + "']").val();
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
 * @param boolean display			Show / hide the confirm button.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function displayModalLookupActionButton(display, modalFormDelete) {
	display = defaultTo(display, true);
	modalFormDelete = defaultTo(modalFormDelete, false);

	if (modalFormDelete === true) {
		button = $("#btn-lookup-delete-action");
	} else {
		button = $("#btn-lookup-action");
	}

	if (display === true) {
		show(button);
	} else {
		hide(button);
	}
}

/**
 * Clears the message in the modal screen for adding a lookup value.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function clearModalLookupMessage(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	if (modalFormDelete === true) {
		messageContainer = $("#message-lookup-delete");
	} else {
		messageContainer = $("#message-lookup");
	}
	messageContainer.children().remove();
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
 * Shows a message in the modal form.
 * 
 * @param string message			Message to display.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * @param boolean warning			Displays message in warning (true) or normal (false) mode.
 */
function showModalLookupMessage(message, modalFormDelete, warning) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	warning = defaultTo(warning, true);
	clearModalLookupMessage(modalFormDelete);
	if (modalFormDelete === true) {
		messageContainer = $("#message-lookup-delete");
	} else {
		messageContainer = $("#message-lookup");
	}
	if (warning === true) {
		messageContainer.removeClass('alert-success').addClass('alert-danger');
	} else {
		messageContainer.removeClass('alert-danger').addClass('alert-success');		
	}
	messages = message.split('\n');
	message = '';
	for (i=0;i<messages.length;i++) {
		message = message + '<div>' + messages[i] + '</div>';
	}
	messageContainer.append(message);
	show(messageContainer);
}

/**
 * Returns the action url.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * 
 * @returns string	Action url.
 */
function getModalLookupActionUrl(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	
	if (modalFormDelete === true) {
		return getModalLookupForm(modalFormDelete).attr('data-action-delete');
	} else {
		if (modusModalLookupIsCreate() === true) {
			return getModalLookupForm(modalFormDelete).attr('data-action-create'); 
		} else {
			return getModalLookupForm(modalFormDelete).attr('data-action-update'); 
		}
	}
}

/**
 * Initialize the modal form.
 */
function initModalLookup() {
	if (modusModalLookupIsCreate()) {
		clearModalLookupValue();
		setModalLookupActionButtonCaption('Toevoegen');
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
	description = getModalLookupValue('description');
	
	if (modusModalLookupIsCreate()) {
		data = JSON.stringify({'description': description});
	} else {
		id = getModalLookupValue('id');
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
					$("#btn-lookup-close").unbind('click').click(function() {location.reload()});
					displayModalLookupAsMessageBox('De omschrijving is toegevoegd.\nNa het sluiten van de melding wordt de lijst ververst.');
				} else {
					$("#lup_" + id).find("td[name]").text(description);
					displayModalLookupAsMessageBox('De omschrijving is gewijzigd.');
				}
			}
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			showModalAddLookupMessage('Error: ' + errorThrown);
		});
	} else {
		showModalAddLookupMessage("De omschrijving is verplicht");
	}
}

/**
 * Deletes a lookup value.
 */ 
function deleteModalLookup() {
	id = getModalLookupValue('id', true);
	data = JSON.stringify({'id': id});

	$.ajax({
		method: 'post',
		url: getModalLookupActionUrl(true),
		data: {'data': data},
		dataType: 'json',
	})
	.done(function(result) {
		if (result.status !== 'ok') {
			displayModalLookupMessage(result.message, false);
		} else {
			$("#btn-lookup-delete-close").unbind('click').click(function() {location.reload()});
			displayModalLookupAsMessageBox('De omschrijving is verwijderd.\nNa het sluiten van de melding wordt de lijst ververst.', true, false);
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		showModalAddLookupMessage('Error: ' + errorThrown);
	});
}

/**
 * Opens modal view for update action.
 * 
 * @param object column	Html action column clicked.
 */
function openModalLookupUpdate(htmlColumn) {
	row = htmlColumn.closest("tr");
	id = row.find("td[name='id']").text();
	// Set the id for the row so it can be identified for a text update.
	row.attr('id', 'lup_' + id);
	description = row.find("td[name='description']").text();
	setModalLookupModus(false);
	setModalLookupValue('id',id, false);
	setModalLookupValue('description', description, false);
	$("#modal-lookup").modal('show');
}

/**
 * Opens modal view for create action.
 */
function openModalLookupCreate() {
	setModalLookupModus(true);
	$("#modal-lookup").modal('show');	
}

/**
 * Open modal view for delete action
 *
 * @param object column	Html action column clicked.
 */
 function openModalLookupDelete(htmlColumn) {
	row = htmlColumn.closest("tr");
	id = row.find("td[name='id']").text();
	description = row.find("td[name='description']").text();
	setModalLookupValue('id', id, true);
	setModalLookupValue('description', description, true);
	showModalLookupMessage("Deze actie verwijdert '" + description + "'.\nKlik verwijderen om de actie uit te voeren.", true);
	$("#modal-lookup-delete").modal('show');	 
 }

$(document).ready(function() {
	$("#btn-lookup-action").click(function() {
		actionModalLookup();
	});
	
	$("#modal-lookup").on('show.bs.modal', function() {
		initModalLookup();
	});

	$("#btn-lookup-add").click(function() {
		openModalLookupCreate();
	});
	
	$("#btn-lookup-delete-action").click(function() {
		deleteModalLookup();
	});
}); 
