<?php
/**
 * Project Team (project-team)
 * @var $this TeamController
 * @var $model ProjectTeam
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://bitbucket.org/ommu/project
 *
 */

$this->breadcrumbs=array(
	'Project Teams'=>array('manage'),
	'Delete',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-team-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<div class="dialog-content">
		<?php echo 'Are you sure you want to delete this item?';?>
		
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton('Delete', array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button('Cancel', array('id'=>'closed')); ?>
	</div>
	
<?php $this->endWidget(); ?>
