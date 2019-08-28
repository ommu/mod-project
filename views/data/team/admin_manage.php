<?php
/**
 * Project Teams (project-team)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TeamController
 * @var $model ommu\project\models\ProjectTeam
 * @var $searchModel ommu\project\models\search\ProjectTeam
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:40 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\project\models\Projects;
use ommu\users\models\Users;
use ommu\ipedia\models\IpediaPositions;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];

$project = Yii::$app->request->get('project');
if($project) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Team'), 'url' => Url::to(['create', 'project'=>$project]), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn modal-btn btn-success']],
	];
}
?>

<div class="project-team-manage">
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

<?php if($user != null)
	echo $this->render('@users/views/member/admin_view', ['model'=>$user, 'small'=>true]); ?>

<?php if($position != null) {
$model = $position;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'position_name',
			'value' => Html::a($model->position_name, ['/ipedia/position/view', 'id'=>$model->position_id], ['title'=>$model->position_name, 'class'=>'modal-btn']),
			'format' => 'html',
		],
		[
			'attribute' => 'position_desc',
			'value' => $model->position_desc ? $model->position_desc : '-',
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
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'Detail')]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title'=>Yii::t('app', 'Update')]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>