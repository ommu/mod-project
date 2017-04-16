<?php
/**
 * Projects (projects)
 * @var $this AdminController
 * @var $model Projects
 * @var $form CActiveForm 
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/Products
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Projects'=>array('manage'),
		'Create',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
	)); ?>
</div>
