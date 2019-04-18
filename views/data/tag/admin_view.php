<?php
/**
 * Project Tags (project-tag)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TagController
 * @var $model ommu\project\models\ProjectTag
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
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->tag->body;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success btn-sm']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger btn-sm'], 'icon' => 'trash'],
];
?>

<div class="project-tag-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'categoryId',
			'value' => function ($model) {
				$categoryId = isset($model->project->category) ? $model->project->category->title->message : '-';
				if($categoryId != '-')
					return Html::a($categoryId, ['setting/category/view', 'id'=>$model->project->cat_id], ['title'=>$categoryId]);
				return $categoryId;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'projectName',
			'value' => function ($model) {
				$projectName = isset($model->project) ? $model->project->project_name : '-';
				if($projectName != '-')
					return Html::a($projectName, ['admin/view', 'id'=>$model->project_id], ['title'=>$projectName]);
				return $projectName;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'tagBody',
			'value' => isset($model->tag) ? $model->tag->body : '-',
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
]); ?>

</div>