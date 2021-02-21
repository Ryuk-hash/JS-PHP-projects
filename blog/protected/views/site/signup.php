<?php
$this->pageTitle=Yii::app()->name . ' - Signup';
$this->breadcrumbs=array(
	'Signup',
);
?>

<h1>Signup</h1>

<p>Welcome! Kindly enter your registration details below:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'signup-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Select a strong password! Suggestions: <br><kbd>1 Uppercase Letter, </kbd><kbd>1 Lowercase Letter; </kbd><br><kbd>1 Number, </kbd><kbd>1 Special Character</kbd>.
		</p>
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'password2'); ?>
		<?php echo $form->passwordField($model,'password2'); ?>
		<?php echo $form->error($model,'password2'); ?>
		<p class="hint">
			Please enter your password again!
		</p>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Signup'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
