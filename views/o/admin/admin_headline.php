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
 * @link https://github.com/ommu/ommu-project
 *
 */
 
	$this->breadcrumbs=array(
		'Projects'=>array('manage'),
		'Headline',
	);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
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