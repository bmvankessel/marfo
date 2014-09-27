<h1>Gebruiker</h1>

<?php 
    $form = $this->beginWidget('CActiveForm', array(
    'id'=>'gebruiker-form',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
            'validateOnSubmit'=>true,
    ),
    'focus'=>array($model,'voorletters'),
));

    
    echo Render::activeFormFields($form, $model);
?>


    <div class="row">
        <div class="col-md-2">
            <?php echo $form->labelEx($model,'rol'); ?>
        </div>
        <div class="col-md-4">
            <?php echo CHtml::activeDropDownList($model, 'rol', array('gebruiker'=>'gebruiker', 'administrator'=>'administrator'), array('class'=>'form-control'))?>
        </div>
    </div>
    
<?php
    $buttonLabel = ($action=='create') ? 'Toevoegen' : 'Opslaan';
    echo CHtml::submitButton($buttonLabel, array('class'=>'btn bnt-primary'));

    $this->endWidget(); 
?>
