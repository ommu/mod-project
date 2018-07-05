<?php
/**
 * Project Setting (project-setting)
 * @var $this SettingController
 * @var $model ProjectSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2013 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/ommu-project
 *
 */
 
	$this->breadcrumbs=array(
		'Project Settings'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input[name="ProjectSetting[media_resize]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#resize_size').slideDown();
		} else {
			$('div#resize_size').slideUp();
		}
	});
EOP;
	$cs->registerScript('resize', $js, CClientScript::POS_END);
?>

<div id="partial-project-category">
	<?php //begin.Messages ?>
	<div id="ajax-message">
	<?php
		if(Yii::app()->user->hasFlash('error'))
			echo $this->flashMessage(Yii::app()->user->getFlash('error'), 'error');
		if(Yii::app()->user->hasFlash('success'))
			echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');
	?>
	</div>
	<?php //begin.Messages ?>

	<div class="boxed">
		<h3>Product Categories</h3>
		<?php //begin.Grid Item ?>
		<?php 
			$columnData   = $columns;
			array_push($columnData, array(
				'header' => 'Actions',
				'class'=>'CButtonColumn',
				'buttons' => array(
					'view' => array(
						'label' => 'view',
						'imageUrl' => false,
						'options' => array(							
							'class' => 'view',
							'target' => '_blank',
						),
						'url' => 'Yii::app()->controller->createUrl("category/view", array("id"=>$data->primaryKey))'),
					'update' => array(
						'label' => 'update',
						'imageUrl' => false,
						'options' => array(
							'class' => 'update'
						),
						'url' => 'Yii::app()->controller->createUrl("category/edit", array("id"=>$data->primaryKey))'),
					'delete' => array(
						'label' => 'delete',
						'imageUrl' => false,
						'options' => array(
							'class' => 'delete'
						),
						'url' => 'Yii::app()->controller->createUrl("category/delete", array("id"=>$data->primaryKey))')
				),
				'template' => '{update}|{delete}',
			));

			$this->widget('application.libraries.core.components.system.OGridView', array(
				'id'=>'project-category-grid',
				'dataProvider'=>$category->search(),
				'filter'=>$category,
				'columns' => $columnData,
				'pager' => array('header' => ''),
			));
		?>
		<?php //end.Grid Item ?>
	</div>
</div>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
