<?php
/**
 * Project Categories (project-category)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\setting\CategoryController
 * @var $model ommu\project\models\ProjectCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 17:51 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="project-category-form">

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

<?php echo $form->field($model, 'cat_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('cat_name_i')); ?>

<?php echo $form->field($model, 'cat_desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('cat_desc_i')); ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>