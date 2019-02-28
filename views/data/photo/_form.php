<?php
/**
 * Project Photos (project-photo)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\PhotoController
 * @var $model ommu\project\models\ProjectPhoto
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 February 2019, 15:34 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use ommu\project\models\ProjectPhoto;
?>

<div class="project-photo-form">

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		'enctype' => 'multipart/form-data',
	],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $uploadPath = join('/', [ProjectPhoto::getUploadPath(false), $model->photo_id]);
$photo = !$model->isNewRecord && $model->old_photo != '' ? Html::img(join('/', [Url::Base(), $uploadPath, $model->old_photo]), ['class'=>'mb-15', 'width'=>'100%']) : '';
echo $form->field($model, 'photo', ['template' => '{label}{beginWrapper}<div>'.$photo.'</div>{input}{error}{hint}{endWrapper}'])
	->fileInput()
	->label($model->getAttributeLabel('photo')); ?>

<?php echo $form->field($model, 'photo_title')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('photo_title')); ?>

<?php echo $form->field($model, 'photo_caption')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('photo_caption')); ?>

<?php echo $form->field($model, 'cover')
	->checkbox()
	->label($model->getAttributeLabel('cover')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>