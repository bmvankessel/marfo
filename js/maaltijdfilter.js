/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * CRUD functions for maaltijdfilters implemented with Bootstrap's modal. 
 *
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */

/**
 * Adds or edits a maaltijdfilter.
 */ 
function actionModalMaaltijdfilter() {
	showModalMaaltijdfilterMessage('', false);
	
	if (modusModalMaaltijdfilterIsCreate()) {
		data = JSON.stringify({
			'productgroep-id': getModalMaaltijdfilterValue('productgroep-id', false, 'select'),
			'maaltijdtype-id': getModalMaaltijdfilterValue('maaltijdtype-id', false, 'select'),
			'maaltijdsubtype-id': getModalMaaltijdfilterValue('maaltijdsubtype-id', false, 'select'),
			'tooltip' : getModalMaaltijdfilterValue('tooltip', false, 'textarea'),
		});
	} else {
		data = JSON.stringify({
			'id': 0,
			'productgroep-id': 0,
			'maaltijdtype-id': 0,
			'maaltijdsubtype-id': 0,
			'tooltip' : '',
		});
	}
	
	$.ajax({
		method: 'post',
		url: getModalMaaltijdfilterActionUrl(),
		data: {'data': data},
		dataType: 'json',
	})
	.done(function(result) {
		if (result.status !== 'ok') {
			showModalMaaltijdfilterMessage(result.message);
		} else {
			if (modusModalMaaltijdfilterIsCreate() === true) {
				$("#btn-maaltijdfilter-close").unbind('click').click(function() {location.reload()});
				displayModalMaaltijdfilterAsMessageBox('Het maaltijdfilter is toegevoegd.\nNa het sluiten van de melding wordt de lijst ververst.');
			} else {
				//$("#lup_" + id).find("td[name='description']").text(description);
				displayModalMaaltijdfilterAsMessageBox('Het maaltijdfilter is gewijzigd.');
			}
		}
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		showModalMaaltijdfilterMessage('Error: ' + errorThrown);
	});
} 

/**
 * Clears the message in modal.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function clearModalMaaltijdfilterMessage(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	if (modalFormDelete === true) {
		messageContainer = $("#message-lookup-delete");
	} else {
		messageContainer = $("#message-lookup");
	}
	messageContainer.children().remove();
}

/**
 * Clears the tooltip entry field.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function clearModalMaaltijdfilterValue(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	setModalMaaltijdfilterValue('tooltip','', modalFormDelete, 'textarea');
}

/**
 * Displays the modal as a message box.
 * 
 * @param string message			Message to display.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * 
 */
function displayModalMaaltijdfilterAsMessageBox(message, modalFormDelete, warning) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	warning = defaultTo(warning, false);
	if (modalFormDelete === false) {
		showModalMaaltijdfilterEntryControls(false);
	}
	showModalMaaltijdfilterMessage(message, modalFormDelete, warning);
	showModalMaaltijdfilterActionButton(false, modalFormDelete);
}

/**
 * Returns the action url.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * 
 * @returns string	Action url.
 */
function getModalMaaltijdfilterActionUrl(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	
	if (modalFormDelete === true) {
		return getModalMaaltijdfilterForm(modalFormDelete).attr('data-action-delete');
	} else {
		if (modusModalMaaltijdfilterIsCreate() === true) {
			return getModalMaaltijdfilterForm(modalFormDelete).attr('data-action-create'); 
		} else {
			return getModalMaaltijdfilterForm(modalFormDelete).attr('data-action-update'); 
		}
	}
}

/**
 * Returns the value of a control within modal.
 * 
 * @param string name				Control name.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * @param string controlType		Control type.
 * 
 * @return mixed					Value.
 */
function getModalMaaltijdfilterValue(name, modalFormDelete, controlType) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	controlType = defaultTo(controlType, 'input');

	selector = controlType + "[name='" + name + "']";
	control = getModalMaaltijdfilterForm(modalFormDelete).find(selector);
	
	if (control.length > 0) {
		return control.val();
	} else {
		alert('Control cannot be found with selector "' + selector + "'");
	}
} 

/**
 * Returns the form element within modal.
 * 
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function getModalMaaltijdfilterForm(modalFormDelete) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	if (modalFormDelete === true) {
		return $("#form-maalijdfilter-delete");
	} else {
		return $("#form-maaltijdfilter");
	}
}

/**
 * Returns the modus of  modal maaltijdfilter.
 * 
 * @return boolean	Modus create true/false.
 */
function modusModalMaaltijdfilterIsCreate() {
	return (getModalMaaltijdfilterForm(false).attr('data-modus') == 'create') ? true : false;
}

/**
 * Opens modal for creating maaltijdfilter.
 */
function openModalMaaltijdfilterCreate() {
	setModalMaaltijdfilterModus(true);
	clearModalMaaltijdfilterValue();
	$("#modal-maaltijdfilter").modal('show');	
}

/**
 * Sets the modus of modal maaltijdfilter.
 * 
 * @param boolean create	Modus create (true) or update (true).
 */
function setModalMaaltijdfilterModus(create) {
	modus = (create === true) ? 'create' : 'update';
	getModalMaaltijdfilterForm(false).attr('data-modus', modus);
}

/**
 * Sets the field value for a modal form.
 * 
 * @param string name				Control name.
 * @param string value				Value.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * @param string controlType		Control type.
 */
function setModalMaaltijdfilterValue(name, value, modalFormDelete, controlType) {
	modalFormDelete = defaultTo(modalFormDelete, false);	
	controlType = defaultTo(controlType, 'input');

	selector = controlType + "[name='" + name + "']";
	control = getModalMaaltijdfilterForm(modalFormDelete).find(selector);
	
	if (control.length > 0) {
		control.val(value);
	} else {
		alert('Control cannot be found with selector "' + selector + "'");
	}
} 

/**
 * Shows or hides the confirm button.
 * 
 * @param boolean show				Show / hide the confirm button.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 */
function showModalMaaltijdfilterActionButton(show, modalFormDelete) {
	show = defaultTo(show, true);
	modalFormDelete = defaultTo(modalFormDelete, false);

	if (modalFormDelete === true) {
		button = $("#btn-maaltijdfilter-delete-action");
	} else {
		button = $("#btn-maaltijdfilter-action");
	}

	if (show === true) {
		show(button);
	} else {
		hide(button);
	}
}

/**
 * Shows or hides the input controls.
 * 
 * @param boolean show	Show / hide the entry controls.
 */
function showModalMaaltijdfilterEntryControls(show) {
	show = defaultTo(show, true);
	form = $("#form-maaltijdfilter");
	if (show === true) {
		show(form);
	} else {
		hide(form);
	}
}

/**
 * Shows a message modal.
 * 
 * @param string message			Message to display.
 * @param boolean modalFormDelete	Target form delete (true) or create/update (false).
 * @param boolean warning			Displays message in warning (true) or normal (false) mode.
 */
function showModalMaaltijdfilterMessage(message, modalFormDelete, warning) {
	modalFormDelete = defaultTo(modalFormDelete, false);
	warning = defaultTo(warning, true);
	clearModalMaaltijdfilterMessage(modalFormDelete);
	if (modalFormDelete === true) {
		messageContainer = $("#message-maaltijdfilter-delete");
	} else {
		messageContainer = $("#message-maaltijdfilter");
	}
	if (warning === true) {
		messageContainer.removeClass('alert-success').addClass('alert-danger');
	} else {
		messageContainer.removeClass('alert-danger').addClass('alert-success');		
	}
	
	if (message.length > 0) {
		messages = message.split('\n');
		message = '';
		for (i=0;i<messages.length;i++) {
			message = message + '<div>' + messages[i] + '</div>';
		}
		messageContainer.append(message);
		show(messageContainer);
	} else {
		hide(messageContainer);
	}
}

$(document).ready(function() {
	$("#btn-maaltijdfilter-add").click(function() {
		openModalMaaltijdfilterCreate();
	});
	
	$("#btn-maaltijdfilter-action").click(function() {
		actionModalMaaltijdfilter();
	});
});
