<?php
/**
 * Project Clients (project-client)
 * @var $this ClientController * @var $model ProjectClient *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Project Clients'=>array('manage'),
		$model->client_id=>array('view','id'=>$model->client_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>