<?php
/**
 * Project Teams (project-team)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TeamController
 * @var $model ommu\project\models\ProjectTeam
 * @var $searchModel ommu\project\models\search\ProjectTeam
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
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
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use ommu\project\models\Projects;
use ommu\users\models\Users;
use ommu\ipedia\models\IpediaPositions;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Team'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="project-team-manage">
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
			'attribute' => 'categoryCatName',
			'value' => function ($model) {
				$categoryCatName = isset($model->category) ? $model->category->title->message : '-';
				if($categoryCatName != '-')
					return Html::a($categoryCatName, ['category/view', 'id'=>$model->cat_id], ['title'=>$categoryCatName]);
				return $categoryCatName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'companyName',
			'value' => function ($model) {
				$companyName = isset($model->company) ? $model->company->company_name : '-';
				if($companyName != '-')
					return Html::a($companyName, ['company/view', 'id'=>$model->company_id], ['title'=>$companyName]);
				return $companyName;
			},
			'format' => 'html',
		],
		'project_name',
		[
			'attribute' => 'project_desc',
			'value' => $model->project_desc ? $model->project_desc : '-',
			'format' => 'html',
		],
		[
			'attribute' => 'status',
			'value' => Projects::get($model->status),
		],
		[
			'attribute' => 'start_date',
			'value' => Yii::$app->formatter->asDate($model->start_date, 'medium'),
		],
		[
			'attribute' => 'finish_date',
			'value' => Yii::$app->formatter->asDate($model->finish_date, 'medium'),
		],
		[
			'attribute' => 'headline_date',
			'value' => Yii::$app->formatter->asDatetime($model->headline_date, 'medium'),
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
	],
]);
}?>

<?php if($user != null) {
$model = $users;
echo DetailView::widget([
	'model' => $users,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'enabled',
			'value' => Users::getEnabled($model->enabled),
		],
		[
			'attribute' => 'verified',
			'value' => $model->verified == 1 ? Yii::t('app', 'Verified') : Yii::t('app', 'Unverified'),
		],
		[
			'attribute' => 'levelName',
			'value' => isset($model->level) ? $model->level->title->message : '-',
		],
		'email:email',
		[
			'attribute' => 'lastlogin_date',
			'value' => Yii::$app->formatter->asDatetime($model->lastlogin_date, 'medium'),
		],
	],
]);
}?>

<?php if($position != null) {
$model = $positions;
echo DetailView::widget([
	'model' => $positions,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'position_name',
		[
			'attribute' => 'position_desc',
			'value' => $model->position_desc ? $model->position_desc : '-',
		],
		[
			'attribute' => 'position_task',
			'value' => $model->position_task ? $model->position_task : '-',
		],
		[
			'attribute' => 'position_jobdesc',
			'value' => $model->position_jobdesc ? $model->position_jobdesc : '-',
		],
		[
			'attribute' => 'position_knowledge',
			'value' => $model->position_knowledge ? $model->position_knowledge : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
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
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Team')]);
		},
		'update' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['update', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Team')]);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Team'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view}{update}{delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>