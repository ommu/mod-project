<?php
/**
 * Project Settings (project-setting)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\setting\AdminController
 * @var $model ommu\project\models\ProjectSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
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
