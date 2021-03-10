<?php
/**
 * Project Teams (project-team)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TeamController
 * @var $model ommu\project\models\ProjectTeam
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 8 February 2019, 15:40 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->project->project_name;

if (!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id' => $model->team_id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->team_id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="project-team-view">

<?php
$attributes = [
    'team_id',
    [
        'attribute' => 'publish',
        'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish, 'Active,Deactive'),
        'format' => 'raw',
    ],
    [
        'attribute' => 'categoryId',
        'value' => function ($model) {
            $categoryId = isset($model->project->category) ? $model->project->category->title->message : '-';
            if ($categoryId != '-') {
                return Html::a($categoryId, ['setting/category/view', 'id' => $model->project->cat_id], ['title' => $categoryId]);
            }
            return $categoryId;
        },
        'format' => 'html',
    ],
    [
        'attribute' => 'projectName',
        'value' => function ($model) {
            $projectName = isset($model->project) ? $model->project->project_name : '-';
            if ($projectName != '-') {
                return Html::a($projectName, ['admin/view', 'id' => $model->project_id], ['title' => $projectName]);
            }
            return $projectName;
        },
        'format' => 'html',
    ],
    [
        'attribute' => 'userDisplayname',
        'value' => isset($model->user) ? $model->user->displayname : '-',
    ],
    [
        'attribute' => 'positionName',
        'value' => function ($model) {
            $positionName = isset($model->position) ? $model->position->position_name : '-';
            if ($positionName != '-') {
                return Html::a($positionName, ['/ipedia/position/view', 'id' => $model->position_id], ['title' => $positionName]);
            }
            return $positionName;
        },
        'format' => 'html',
    ],
    [
        'attribute' => 'creation_date',
        'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
        'visible' => !$small,
    ],
    [
        'attribute' => 'creationDisplayname',
        'value' => isset($model->creation) ? $model->creation->displayname : '-',
        'visible' => !$small,
    ],
    [
        'attribute' => 'updated_date',
        'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
        'visible' => !$small,
    ],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>