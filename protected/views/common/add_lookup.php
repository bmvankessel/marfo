<?php
/**
 * @copyright Copyright @copy; Brainpower Solutions.nl, 2014
*/

/**
 * Modal dialog for adding a new lookup value.
 */
?>

<div class="modal fade" id="modal-lookup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="text-center"><?=$this->modelname?></h3>
			</div> 
			<div class="modal-body">
				<form id="target-lookup" data-modus="create" class="form-horizontal" data-action-create="<?=$this->createUrl('create')?>" role="form">
					<div class="form-group">
						<label class="col-md-2 control-label">Omschrijving</label>
						<div class="col-md-9">
							<input id ="lookup-description" type="text" name="description" class="form-control">
						</div>
					</div>
				</form>
				<div id="message-lookup" class="alert alert-danger hidden" role ="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn pull-left" data-dismiss="modal">Sluiten</button>
				<button type="button" class="btn btn-success" id="btn-lookup-action" >Toevoegen</button>				
			</div>
		</div>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/bootstrap_utils.js'), CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/lookup.js'), CClientScript::POS_END);
?>

