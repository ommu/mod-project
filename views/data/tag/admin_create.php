<?php
/**
 * Project Tags (project-tag)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TagController
 * @var $model ommu\project\models\ProjectTag
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 12 February 2019, 17:07 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['manage']), 'icon' => 'table'],
];
?>

<div class="project-tag-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
