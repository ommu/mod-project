<?php
/**
 * Project Photos (project-photo)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\PhotoController
 * @var $model ommu\project\models\ProjectPhoto
 * @var $searchModel ommu\project\models\search\ProjectPhoto
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:34 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\project\models\Projects;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="project-photo-manage">
<?php Pjax::begin(); ?>

<?php if($project != null) {
$model = $project;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'categoryName',
			'value' => function ($model) {
				$categoryName = isset($model->category) ? $model->category->title->message : '-';
				if($categoryName != '-')
					return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName, 'class'=>'modal-btn']);
				return $categoryName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'companyName',
			'value' => function ($model) {
				$companyName = isset($model->company) ? $model->company->company_name : '-';
				if($companyName != '-')
					return Html::a($companyName, ['/ipedia/company/view', 'id'=>$model->company_id], ['title'=>$companyName, 'class'=>'modal-btn']);
				return $companyName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'project_name',
			'value' => Html::a($model->project_name, ['admin/view', 'id'=>$model->project_id], ['title'=>$model->project_name, 'class'=>'modal-btn']),
			'format' => 'html',
		],
		[
			'attribute' => 'project_desc',
			'value' => $model->project_desc ? $model->project_desc : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'status',
			'value' => Projects::getStatus($model->status),
		],
		[
			'attribute' => 'start_date',
			'value' => Yii::$app->formatter->asDate($model->start_date, 'medium'),
		],
		[
			'attribute' => 'finish_date',
			'value' => Yii::$app->formatter->asDate($model->finish_date, 'medium'),
		],
	],
]);
}?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['delete', 'id'=>$key]);
	},
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>