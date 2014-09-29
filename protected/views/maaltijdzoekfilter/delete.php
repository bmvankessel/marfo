<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Modal for deleting maaltijdfilter.
 * 
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
?>

<div class="modal fade" id="modal-maaltijdfilter-delete" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="text-center">Verwijderen Maaltijdzoekfilter</h3>
			</div> 
			<div class="modal-body">
				<form id="form-maaltijdfilter-delete" class="form-horizontal" data-action-delete="<?=$this->createUrl('delete')?>" role="form">
					<input type="hidden" name="id">
					<input type="hidden" name="description">
				</form>
				<div id="message-maaltijdfilter-delete" class="alert alert-danger" role ="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn pull-left" data-dismiss="modal" id="btn-maaltijdfilter-delete-close">Sluiten</button>
				<button type="button" class="btn btn-danger" id="btn-maaltijdfilter-delete-action" >Verwijderen</button>				
			</div>
		</div>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/bootstrap_utils.js'), CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/maaltijdfilter.js'), CClientScript::POS_END);
?>

