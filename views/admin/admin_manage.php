<?php
/**
 * Projects (projects)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\Projects
 * @var $searchModel ommu\project\models\search\Projects
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 07:18 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use ommu\project\models\ProjectCategory;
use ommu\ipedia\models\IpediaCompanies;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Project'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="projects-manage">
<?php Pjax::begin(); ?>

<?php if($category != null) {
$model = $categories;
echo DetailView::widget([
	'model' => $categories,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'cat_name_i',
			'value' => function ($model) {
				if($model->cat_name_i != '')
					return Html::a($model->cat_name_i, ['admin/category/view', 'id'=>$model->cat_id], ['title'=>$model->cat_name_i]);
				return $model->cat_name_i;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'cat_desc_i',
			'value' => function ($model) {
				if($model->cat_desc_i != '')
					return Html::a($model->cat_desc_i, ['admin/category/view', 'id'=>$model->cat_id], ['title'=>$model->cat_desc_i]);
				return $model->cat_desc_i;
			},
			'format' => 'html',
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

<?php if($company != null) {
$model = $companies;
echo DetailView::widget([
	'model' => $companies,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'memberDisplayname',
			'value' => function ($model) {
				$memberDisplayname = isset($model->member) ? $model->member->displayname : '-';
				if($memberDisplayname != '-')
					return Html::a($memberDisplayname, ['member/view', 'id'=>$model->member_id], ['title'=>$memberDisplayname]);
				return $memberDisplayname;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'company_name',
			'value' => $model->company_name ? $model->company_name : '-',
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
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Project')]);
		},
		'update' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['update', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Project')]);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Project'),
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