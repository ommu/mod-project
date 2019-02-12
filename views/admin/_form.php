<?php
/**
 * Projects (projects)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\Projects
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 11:23 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\project\models\Projects;
use ommu\project\models\ProjectCategory;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor','imagemanager']
];
?>

<div class="projects-form">

<?php $form = ActiveForm::begin([
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = ProjectCategory::getCategory();
echo $form->field($model, 'cat_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $company_id = $form->field($model, 'company_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()->label(false);
echo $form->field($model, 'companyName', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}'.$company_id.'{error}</div>'])
	// ->textInput(['maxlength'=>true])
	->widget(AutoComplete::className(), [
		'options' => [
			'data-toggle' => 'tooltip', 'data-placement' => 'top',
			'class' => 'ui-autocomplete-input form-control'
		],
		'clientOptions' => [
			'source' => Url::to(['/ipedia/company/suggest']),
			'minLength' => 2,
			'select' => new JsExpression("function(event, ui) {
				\$('.field-companyname #company_id').val(ui.item.id);
				\$('.field-companyname #companyname').val(ui.item.label);
				return false;
			}"),
		]
	])
	->label($model->getAttributeLabel('companyName'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'project_name', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('project_name'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'project_desc', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('project_desc'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php $status = Projects::getStatus();
echo $form->field($model, 'status', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($status, ['prompt' => ''])
	->label($model->getAttributeLabel('status'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'start_date', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('start_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'finish_date', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['type' => 'date'])
	->label($model->getAttributeLabel('finish_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'comment', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('comment'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'headline', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('headline'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

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