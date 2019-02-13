<?php
/**
 * Projects (projects)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\Projects
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 11:45 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\project\models\Projects;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->project_name;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['manage']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->project_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->project_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->project_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="projects-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'project_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
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
		'project_name',
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
		[
			'attribute' => 'headline',
			'value' => $this->quickAction(Url::to(['headline', 'id'=>$model->primaryKey]), $model->headline, 'Headline,Unheadline'),
			'format' => 'raw',
		],
		[
			'attribute' => 'comment',
			'value' => $this->quickAction(Url::to(['comment', 'id'=>$model->primaryKey]), $model->comment, 'Enable,Disable'),
			'format' => 'raw',
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
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modifiedDisplayname',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		'slug',
		[
			'attribute' => 'photos',
			'value' => function ($model) {
				$photos = $model->getPhotos(true);
				return Html::a($photos, ['data/photo/manage', 'project'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} photos', ['count'=>$photos])]);
			},
			'format' => 'html',
		],
		[
			'attribute' => 'tags',
			'value' => function ($model) {
				$tags = $model->getTags(true);
				return Html::a($tags, ['data/tag/manage', 'project'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} tags', ['count'=>$tags])]);
			},
			'format' => 'html',
		],
		[
			'attribute' => 'teams',
			'value' => function ($model) {
				$teams = $model->getTeams(true);
				return Html::a($teams, ['data/team/manage', 'project'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} teams', ['count'=>$teams])]);
			},
			'format' => 'html',
		],
	],
]); ?>

</div>