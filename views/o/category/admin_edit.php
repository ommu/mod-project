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
		$model->name=>array('view','id'=>$model->cat_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
