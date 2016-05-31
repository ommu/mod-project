<?php
/**
 * @var $this MediaController
 * @var $model ProjectMedia
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
	'id'=>'project-media-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php
		echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo Utility::flashError(Yii::app()->user->getFlash('error'));
		if(Yii::app()->user->hasFlash('success'))
			echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
		?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>
	
		<?php if(!$model->isNewRecord) {?>		
		<div class="clearfix">
			<?php echo $form->labelEx($model,'old_media'); ?>
			<div class="desc">
				<?php 
				$model->old_media = $model->media;
				echo $form->hiddenField($model,'old_media');
				if($model->old_media != '') {
					$media = Yii::app()->request->baseUrl.'/public/project/'.$model->project_id.'/'.$model->old_media;				
				} else {
					$media = Yii::app()->request->baseUrl.'/public/project/project_default.png';					
				}?>
				<img src="<?php echo Utility::getTimThumb($media, 400, 400, 3);?>" alt="">
			</div>
		</div>
		<?php }?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'media'); ?>
			<div class="desc">
				<?php echo $form->fileField($model,'media',array('maxlength'=>64)); ?>
				<?php echo $form->error($model,'media'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'cover'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'cover'); ?>
				<?php echo $form->error($model,'cover'); ?>
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
