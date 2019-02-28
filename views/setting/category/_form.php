<?php
/**
 * Project Categories (project-category)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\setting\CategoryController
 * @var $model ommu\project\models\ProjectCategory
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 7 February 2019, 17:51 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="project-category-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'cat_name_i')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('cat_name_i')); ?>

<?php echo $form->field($model, 'cat_desc_i')
	->textarea(['rows'=>6, 'cols'=>50, 'maxlength'=>true])
	->label($model->getAttributeLabel('cat_desc_i')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>