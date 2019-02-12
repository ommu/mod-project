<?php
/**
 * Project Teams (project-team)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TeamController
 * @var $model ommu\project\models\ProjectTeam
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:40 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="project-team-form">

<?php $form = ActiveForm::begin([
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if(!Yii::$app->request->get('project')) {
echo $form->field($model, 'project_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('project_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']);
} ?>

<?php echo $form->field($model, 'user_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('user_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'position_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('position_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>