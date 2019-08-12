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
		$model->client_id=>array('view','id'=>$model->client_id),
		Yii::t('phrase', 'Update'),
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>