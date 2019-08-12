<?php
/**
 * Project Clients (project-client)
 * @var $this ClientController 
 * @var $model ProjectClient 
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://bitbucket.org/ommu/project
 *
 */

	$this->breadcrumbs=array(
		'Project Clients'=>array('manage'),
		Yii::t('phrase', 'Delete'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-client-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo 'Are you sure you want to delete this item?';?>
		
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton('Delete', array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button('Cancel', array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
