<?php
/**
 * @var $this SettingController
 * @var $model ProjectSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'project-setting-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<div class="clearfix">
			<label>
				<?php echo $model->getAttributeLabel('license');?> <span class="required">*</span><br/>
				<span>Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.</span>
			</label>
			<div class="desc">
				<?php echo $form->textField($model,'license',array('maxlength'=>32,'class'=>'span-4')); ?>
				<?php echo $form->error($model,'license'); ?>
				<span class="small-px">Format: XXXX-XXXX-XXXX-XXXX</span>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'permission'); ?>
			<div class="desc">
				<span class="small-px">Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.</span>
				<?php echo $form->radioButtonList($model, 'permission', array(
					1 => 'Yes, the public can view project unless they are made private.',
					0 => 'No, the public cannot view projects.',
				)); ?>
				<?php echo $form->error($model,'permission'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'meta_description'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'meta_description',array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
				<?php echo $form->error($model,'meta_description'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'meta_keyword'); ?>
			<div class="desc">
				<?php echo $form->textArea($model,'meta_keyword',array('rows'=>6, 'cols'=>50, 'class'=>'span-7 smaller')); ?>
				<?php echo $form->error($model,'meta_keyword'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'headline'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'headline', array('class'=>'span-2')); ?>
				<?php echo $form->error($model,'headline'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'media_limit'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'media_limit', array('class'=>'span-2')); ?>
				<?php echo $form->error($model,'media_limit'); ?>
			</div>
		</div>

		<div class="clearfix">
			<label>Media Setting <span class="required">*</span></label>
			<div class="desc">
				<p><?php echo $model->getAttributeLabel('media_resize');?></p>
				<?php echo $form->radioButtonList($model, 'media_resize', array(
					0 => 'No, not resize photo after upload',
					1 => 'Yes, resize photo after upload.',
				)); ?>
				<?php if($model->media_resize_size != '') {
					$resizeSize = explode(',', $model->media_resize_size);
					$model->media_resize_width = $resizeSize[0];
					$model->media_resize_height = $resizeSize[1];
				}?>
				<div id="resize_size" class="mt-15 <?php echo $model->media_resize == 0 ? 'hide' : '';?>">
					Width: <?php echo $form->textField($model,'media_resize_width',array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
					Height: <?php echo $form->textField($model,'media_resize_height',array('maxlength'=>4,'class'=>'span-2')); ?>
					<?php echo $form->error($model,'media_resize_size'); ?>
				</div>
				
				<p>Large Size</p>
				Width: <?php echo $form->textField($model,'media_large_width',array('maxlength'=>4,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				Height: <?php echo $form->textField($model,'media_large_height',array('maxlength'=>4,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'media_large_width'); ?>
				
				<p>Medium Size</p>
				Width: <?php echo $form->textField($model,'media_medium_width',array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				Height: <?php echo $form->textField($model,'media_medium_height',array('maxlength'=>3,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'media_medium_width'); ?>
				
				<p>Small Size</p>
				Width: <?php echo $form->textField($model,'media_small_width',array('maxlength'=>3,'class'=>'span-2')); ?>&nbsp;&nbsp;&nbsp;
				Height: <?php echo $form->textField($model,'media_small_height',array('maxlength'=>3,'class'=>'span-2')); ?>
				<?php echo $form->error($model,'media_small_width'); ?>
			</div>
		</div>

		<div class="submit clearfix">
			<label>&nbsp;</label>
			<div class="desc">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>
