<?php
/**
 * Project Categories (project-category)
 * @var $this CategoryController
 * @var $model ProjectCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2013 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-project
 *
 */
 
	$this->breadcrumbs=array(
		'Project Categories'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>