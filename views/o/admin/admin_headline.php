<?php
/**
 * Projects (projects)
 * @var $this AdminController
 * @var $model Projects
 * @var $form CActiveForm 
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2013 Ommu Platform (www.ommu.co)
 * @link https://bitbucket.org/ommu/project
 *
 */
 
	$this->breadcrumbs=array(
		'Projects'=>array('manage'),
		'Headline',
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'projects-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
	<div class="dialog-content">
		Are you sure you want to headline this item?
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton('Headline', array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button('Cancel', array('id'=>'closed')); ?>
	</div>
<?php $this->endWidget(); ?>