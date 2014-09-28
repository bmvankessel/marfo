<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Modal dialog for deleting lookup value.
 * 
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
?>

<div class="modal fade" id="modal-lookup-delete" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="text-center">Verwijderen <?=$this->modelname?></h3>
			</div> 
			<div class="modal-body">
				<form id="form-lookup-delete" class="form-horizontal" data-action-delete="<?=$this->createUrl('delete')?>" role="form">
					<input type="hidden" name="id">
					<input type="hidden" name="description">
				</form>
				<div id="message-lookup-delete" class="alert alert-danger" role ="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn pull-left" data-dismiss="modal" id="btn-lookup-delete-close">Sluiten</button>
				<button type="button" class="btn btn-danger" id="btn-lookup-delete-action" >Verwijderen</button>				
			</div>
		</div>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/bootstrap_utils.js'), CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/lookup.js'), CClientScript::POS_END);
?>

