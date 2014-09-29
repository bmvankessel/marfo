<?php
/**
 * @copyright Copyright @copy; Brainpower Solutions.nl, 2014
*/

/**
 * Modal dialog for adding and updateing maaltijd filters.
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 * 
 * @param array $productgroepOptions	Options for productgroep.
 * @param array $maaltijdtypeOptions	Options for maaltijdtype.
 * @param array $maaltijdsubtypeOptions	Optins for maaltijdsubtype.
 */
?>

<div class="modal fade" id="modal-maaltijdfilter" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="text-center">Maaltijdfilter</h3>
			</div> 
			<div class="modal-body">
				<form id="form-maaltijdfilter" data-modus="create" class="form-horizontal" data-action-create="<?=$this->createUrl('create')?>" data-action-update="<?=$this->createUrl('update')?>" role="form">
					<input type="hidden" name="id">
					<div class="form-group">
						<label class="col-md-3 control-label">Productgroep</label>
						<div class="col-md-9">
							<?=CHtml::dropdownList('productgroep-id', null, $productgroepOptions, array('class'=>'form-control'))?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Maaltijdtype</label>
						<div class="col-md-9">
							<?=CHtml::dropdownList('maaltijdtype-id', null, $maaltijdtypeOptions, array('class'=>'form-control'))?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Maalltijdsubtype</label>
						<div class="col-md-9">
							<?=CHtml::dropdownList('maaltijdsubtype-id', null, $maaltijdsubtypeOptions, array('class'=>'form-control'))?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Opmerking<p class="small">(getoond als tooltip)</p></label>
						<div class="col-md-9">
							<textarea name="tooltip" class="form-control" rows="3"></textarea>
						</div>
					</div>
				</form>
				<div id="message-maaltijdfilter" class="alert alert-danger hidden" role ="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn pull-left" id="btn-maaltijdfilter-close" data-dismiss="modal">Sluiten</button>
				<button type="button" class="btn btn-success" id="btn-maaltijdfilter-action" >Toevoegen</button>				
			</div>
		</div>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/bootstrap_utils.js'), CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile($this->createUrl('js/maaltijdfilter.js'), CClientScript::POS_END);
?>

