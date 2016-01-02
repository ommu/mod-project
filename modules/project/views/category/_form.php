<?php
/**
 * @var $this CategoryController
 * @var $model ProjectCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'project-category-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">
	<fieldset>

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'dependency'); ?>
			<div class="desc">
				<?php if(ProjectCategory::getCategory() != null) {
					echo $form->dropDownList($model,'dependency', ProjectCategory::getCategory(), array('prompt'=>'No Parent'));
				} else {
					echo $form->dropDownList($model,'dependency', array(0=>'No Parent'));
				}?>
				<?php echo $form->error($model,'dependency'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'name'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'name',array('maxlength'=>32,'class'=>'span-8')); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'desc'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'desc',array('maxlength'=>128,'class'=>'span-11 smaller')); ?>
				<?php echo $form->error($model,'desc'); ?>
			</div>
		</div>

		<div class="clearfix publish">
			<?php echo $form->labelEx($model,'publish'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'publish'); ?>
				<?php echo $form->labelEx($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save' ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button('Close', array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>
