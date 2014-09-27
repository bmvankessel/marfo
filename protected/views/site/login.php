<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array('Login');
?>

<h1>Aanmelden</h1>

<p>Voer uw gebruikersnaam en wachtwoord in om u aan te melden.</p>

<div class="form">
<?php 
    $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    ));
    
?>

	<div class="row">
            <div class="column">
		<?php echo $form->labelEx($model,'username'); ?>
            </div>
            <div class="column">
		<?php echo $form->textField($model,'username', array('class'=>'form-control')); ?>
            </div>
            <div class="column">
		<?php echo $form->error($model,'username'); ?>
            </div>
	</div>

	<div class="row">
            <div class="column">
		<?php echo $form->labelEx($model,'password'); ?>
            </div>
            <div class="column">
		<?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?>
            </div>
            <div class="column">
		<?php echo $form->error($model,'password'); ?>
            </div>
	</div>

	<p class="note">Velden met een <span class="required">*</span> zijn verplicht.</p>
<!--	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton('Aanmelden', array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
