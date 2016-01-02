<?php
/**
 * @var $this SiteController
 * @var $model Projects
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Projects'=>array('manage'),
		$model->title,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'project_id',
		'publish',
		'cat_id',
		'user_id',
		'media_id',
		'headline',
		'comment_code',
		'title',
		'body',
		'status',
		'start_date',
		'finish_date',
		'comment',
		'view',
		'likes',
		'creation_date',
		'modified_date',
	),
)); ?>
