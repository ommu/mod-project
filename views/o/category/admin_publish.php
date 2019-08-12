<?php
/**
 * Project Categories (project-category)
 * @var $this CategoryController
 * @var $model ProjectCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2013 Ommu Platform (www.ommu.co)
 * @link https://bitbucket.org/ommu/project
 *
 */
 
	$this->breadcrumbs=array(
		'Project Categories'=>array('manage'),
		Yii::t('phrase', 'Publish'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-category-form',
	'enableAjaxValidation'=>true,
)); ?>
	<div class="dialog-content">
		<?php echo $model->publish == 1 ?'Are you sure you want to unpublish this item?' : 'Are you sure you want to publish this item?'?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button('Cancel', array('id'=>'closed')); ?>
	</div>
<?php $this->endWidget(); ?>