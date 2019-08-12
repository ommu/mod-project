<?php
/**
 * Projects (projects)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\Projects
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 11:23 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\project\models\Projects;
use ommu\project\models\ProjectCategory;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload' => ['/redactor/upload/image'],
	'fileUpload' => ['/redactor/upload/file'],
	'plugins' => ['clips', 'fontcolor', 'imagemanager']
];
?>

<div class="projects-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $category = ProjectCategory::getCategory();
echo $form->field($model, 'cat_id')
	->dropDownList($category, ['prompt'=>''])
	->label($model->getAttributeLabel('cat_id')); ?>

<?php $company_id = $form->field($model, 'company_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput();
echo $form->field($model, 'companyName', ['template' => '{label}{beginWrapper}{input}'.$company_id.'{error}{hint}{endWrapper}'])
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
	->label($model->getAttributeLabel('companyName')); ?>

<?php echo $form->field($model, 'project_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('project_name')); ?>

<?php echo $form->field($model, 'project_desc')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('project_desc')); ?>

<?php $status = Projects::getStatus();
echo $form->field($model, 'status')
	->dropDownList($status, ['prompt'=>''])
	->label($model->getAttributeLabel('status')); ?>

<?php echo $form->field($model, 'start_date')
	->textInput(['type'=>'date'])
	->label($model->getAttributeLabel('start_date')); ?>

<?php echo $form->field($model, 'finish_date')
	->textInput(['type'=>'date'])
	->label($model->getAttributeLabel('finish_date')); ?>

<?php echo $form->field($model, 'comment')
	->checkbox()
	->label($model->getAttributeLabel('comment')); ?>

<?php echo $form->field($model, 'headline')
	->checkbox()
	->label($model->getAttributeLabel('headline')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>