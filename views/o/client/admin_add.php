<?php
/**
 * Project Clients (project-client)
 * @var $this ClientController 
 * @var $model ProjectClient 
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/ommu-project
 *
 */

	$this->breadcrumbs=array(
		'Project Clients'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>