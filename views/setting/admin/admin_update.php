<?php
/**
 * Project Settings (project-setting)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\setting\AdminController
 * @var $model ommu\project\models\ProjectSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 February 2019, 14:19 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Project Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Setting'), 'url' => Url::to(['index']), 'icon' => 'table'],
];
?>

<div class="project-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
