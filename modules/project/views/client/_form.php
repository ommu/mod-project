<?php
/**
 * Project Clients (project-client)
 * @var $this ClientController * @var $model ProjectClient * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'project-client-form',
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
			<?php echo $form->labelEx($model,'client_name'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'client_name',array('maxlength'=>64)); ?>
				<?php echo $form->error($model,'client_name'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php if($model->isNewRecord) {
				echo $form->labelEx($model,'address');
			} else {?>
				<label>
					<?php echo $model->getAttributeLabel('address');?> <span class="required">*</span>
				</label>
			<?php }?>
			<div class="desc">
				<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50, 'class'=>'span-10 smaller')); ?>
				<?php echo $form->error($model,'address'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php if($model->isNewRecord) {
				echo $form->labelEx($model,'cp_name');
			} else {?>
				<label>
					<?php echo $model->getAttributeLabel('cp_name');?> <span class="required">*</span>
				</label>
			<?php }?>
			<div class="desc">
				<?php echo $form->textField($model,'cp_name',array('maxlength'=>32)); ?>
				<?php echo $form->error($model,'cp_name'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php if($model->isNewRecord) {
				echo $form->labelEx($model,'phone');
			} else {?>
				<label>
					<?php echo $model->getAttributeLabel('phone');?> <span class="required">*</span>
				</label>
			<?php }?>
			<div class="desc">
				<?php echo $form->textField($model,'phone',array('maxlength'=>32)); ?>
				<?php echo $form->error($model,'phone'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="clearfix">
			<?php if($model->isNewRecord) {
				echo $form->labelEx($model,'email');
			} else {?>
				<label>
					<?php echo $model->getAttributeLabel('email');?> <span class="required">*</span>
				</label>
			<?php }?>
			<div class="desc">
				<?php echo $form->textField($model,'email',array('maxlength'=>32)); ?>
				<?php echo $form->error($model,'email'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
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
