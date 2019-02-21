<?php
/**
 * Project Tags (project-tag)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TagController
 * @var $model ommu\project\models\ProjectTag
 * @var $searchModel ommu\project\models\search\ProjectTag
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:33 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use ommu\project\models\Projects;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];

$project = Yii::$app->request->get('project');
if($project) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Tag'), 'url' => Url::to(['create', 'project'=>$project]), 'htmlOptions' => ['class'=>'modal-btn'], 'icon' => 'plus-square'],
	];
}
?>

<div class="project-tag-manage">
<?php Pjax::begin(); ?>

<?php if($project != null) {
$model = $projects;
echo DetailView::widget([
	'model' => $projects,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'categoryName',
			'value' => function ($model) {
				$categoryName = isset($model->category) ? $model->category->title->message : '-';
				if($categoryName != '-')
					return Html::a($categoryName, ['setting/category/view', 'id'=>$model->cat_id], ['title'=>$categoryName]);
				return $categoryName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'companyName',
			'value' => function ($model) {
				$companyName = isset($model->company) ? $model->company->company_name : '-';
				if($companyName != '-')
					return Html::a($companyName, ['/ipedia/company/view', 'id'=>$model->company_id], ['title'=>$companyName]);
				return $companyName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'project_name',
			'value' => Html::a($model->project_name, ['admin/view', 'id'=>$model->project_id], ['title'=>$model->project_name]),
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

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$this->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'yii\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'contentOptions' => [
		'class'=>'action-column',
	],
	'buttons' => [
		'view' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['view', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Tag')]);
		},
		'update' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['update', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Tag')]);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Tag'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view}{delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>