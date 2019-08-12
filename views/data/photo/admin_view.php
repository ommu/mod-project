<?php
/**
 * Project Photos (project-photo)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\PhotoController
 * @var $model ommu\project\models\ProjectPhoto
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:34 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\project\models\ProjectPhoto;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Photos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->photo_title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->photo_id]), 'icon' => 'eye', 'htmlOptions' => ['class'=>'btn btn-success']],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->photo_id]), 'icon' => 'pencil', 'htmlOptions' => ['class'=>'btn btn-primary']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->photo_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="project-photo-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'photo_id',
		[
			'attribute' => 'publish',
			'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'cover',
			'value' => $model->filterYesNo($model->cover),
		],
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
			'attribute' => 'photo',
			'value' => function ($model) {
				$uploadPath = join('/', [ProjectPhoto::getUploadPath(false), $model->project_id]);
				return $model->photo ? Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->photo])), ['alt'=>$model->photo, 'class'=>'mb-3']).'<br/>'.$model->photo : '-';
			},
			'format' => 'html',
		],
		'photo_title',
		[
			'attribute' => 'photo_caption',
			'value' => $model->photo_caption ? $model->photo_caption : '-',
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
	],
]); ?>

</div>