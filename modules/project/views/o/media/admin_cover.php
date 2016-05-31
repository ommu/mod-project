<?php
/**
 * @var $this AdminController
 * @var $model Projects
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Project Medias'=>array('manage'),
		'Cover',
	);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'projects-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
	<div class="dialog-content">
		Are you sure made ​​cover this item?
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton('Cover', array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button('Cancel', array('id'=>'closed')); ?>
	</div>
<?php $this->endWidget(); ?>