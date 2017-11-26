<?php
/**
 * Project Media (project-media)
 * @var $this AdminController
 * @var $model Projects
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2013 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-project
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Project Medias'=>array('manage'),
		'Cover',
	);
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
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