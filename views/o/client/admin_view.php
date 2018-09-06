<?php
/**
 * Project Clients (project-client)
 * @var $this ClientController 
 * @var $model ProjectClient 
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2014 Ommu Platform (www.ommu.co)
 * @link https://bitbucket.org/ommu/project
 *
 */

	$this->breadcrumbs=array(
		'Project Clients'=>array('manage'),
		$model->client_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			'client_id',
			'publish',
			'client_name',
			'address',
			'phone',
			'email',
			'cp_name',
			'creation_date',
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button('Close', array('id'=>'closed')); ?>
</div>