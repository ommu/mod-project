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

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
?>

<div class="project-tag-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php if(!Yii::$app->request->get('project')) {
echo $form->field($model, 'project_id')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('project_id'));
} ?>

<?php $tag_id = $form->field($model, 'tag_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()->label(false);
echo $form->field($model, 'tagBody', ['template' => '{label}{beginWrapper}{input}'.$tag_id.'{error}{hint}{endWrapper}'])
	// ->textInput(['maxlength'=>true])
	->widget(AutoComplete::className(), [
		'options' => [
			'data-toggle' => 'tooltip', 'data-placement' => 'top',
			'class' => 'ui-autocomplete-input form-control'
		],
		'clientOptions' => [
			'source' => Url::to(['suggest', 'project'=>Yii::$app->request->get('project')]),
			'minLength' => 2,
			'select' => new JsExpression("function(event, ui) {
				\$('.field-companyname #tag_id').val(ui.item.id);
				\$('.field-companyname #companyname').val(ui.item.label);
				return false;
			}"),
		]
	])
	->label($model->getAttributeLabel('tagBody')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>