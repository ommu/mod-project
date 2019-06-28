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

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
// use yii\bootstrap\ActiveForm;
use ommu\project\models\ProjectSetting;
use ommu\project\models\ProjectCategory;
?>

<div class="project-setting-form">

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

<?php if($model->isNewRecord && !$model->getErrors())
	$model->license = $model->licenseCode();
echo $form->field($model, 'license')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = ProjectSetting::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<?php $headlineLimit = $form->field($model, 'headline_limit', ['template' => '<div class="h5">'.$model->getAttributeLabel('headline_limit').'</div>{input}{error}{hint}', 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('headline_limit')); ?>

<?php $category = ProjectCategory::getCategory(1);
$headlineCategory = $form->field($model, 'headline_category', ['template' => '<div class="h5">'.$model->getAttributeLabel('headline_category').'</div>{input}{error}{hint}', 'options' => ['tag' => null]])
	->checkboxList($category)
	->label($model->getAttributeLabel('headline_category')); ?>

<?php $headline = ProjectSetting::getHeadline();
echo $form->field($model, 'headline', ['template' => '{label}{beginWrapper}{input}{error}{hint}'.$headlineLimit.$headlineCategory.'{endWrapper}'])
	->radioList($headline)
	->label($model->getAttributeLabel('headline')); ?>

<?php echo $form->field($model, 'photo_limit', ['template' => '{label}{beginWrapper}<div class="h5">'.$model->getAttributeLabel('photo_limit').'</div>{input}{error}{hint}{endWrapper}'])
	->textInput(['type'=>'number', 'min'=>'1'])
	->label(Yii::t('app', 'Project Image')); ?>

<?php $photoResize = ProjectSetting::getPhotoResize();
echo $form->field($model, 'photo_resize', ['template' => '{beginWrapper}<div class="h5">'.$model->getAttributeLabel('photo_resize').'</div>{input}{error}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->radioList($photoResize)
	->label($model->getAttributeLabel('photo_resize')); ?>

<?php $photo_resize_size_height = $form->field($model, 'photo_resize_size[height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[height]')])
	->label($model->getAttributeLabel('photo_resize_size[height]')); ?>

<?php echo $form->field($model, 'photo_resize_size[width]', ['template' => '{hint}{beginWrapper}{input}{endWrapper}'.$photo_resize_size_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3', 'hint'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_resize_size[width]')])
	->label($model->getAttributeLabel('photo_resize_size'))
	->hint(Yii::t('app', 'If you have selected "Yes" above, please input the maximum dimensions for the project image. If your users upload a image that is larger than these dimensions, the server will attempt to scale them down automatically. This feature requires that your PHP server is compiled with support for the GD Libraries.')); ?>

<?php echo $form->field($model, 'photo_file_type', ['template' => '{beginWrapper}<div class="h5">'.$model->getAttributeLabel('photo_file_type').'</div>{input}{error}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3']])
	->textInput()
	->label($model->getAttributeLabel('photo_file_type'))
	->hint(Yii::t('app', 'What file types do you want to allow for project image (gif, jpg, jpeg, or png)? Separate file types with commas, i.e. jpg, jpeg, gif, png')); ?>

<?php $photo_view_size_small_height = $form->field($model, 'photo_view_size[small][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[small][height]')])
	->label($model->getAttributeLabel('photo_view_size[small][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[small][width]', ['template' => '{label}<div class="h5 col-md-6 col-sm-9 col-xs-12">'.$model->getAttributeLabel('photo_view_size[small]').'</div>{beginWrapper}{input}{endWrapper}'.$photo_view_size_small_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[small][width]')])
	->label($model->getAttributeLabel('photo_view_size')); ?>

<?php $photo_view_size_medium_height = $form->field($model, 'photo_view_size[medium][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[medium][height]')])
	->label($model->getAttributeLabel('photo_view_size[medium][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[medium][width]', ['template' => '<div class="h5 col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">'.$model->getAttributeLabel('photo_view_size[medium]').'</div>{beginWrapper}{input}{endWrapper}'.$photo_view_size_medium_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[medium][width]')])
	->label($model->getAttributeLabel('photo_view_size[medium]')); ?>

<?php $photo_view_size_large_height = $form->field($model, 'photo_view_size[large][height]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-5 col-xs-6'], 'options' => ['tag' => null]])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[large][height]')])
	->label($model->getAttributeLabel('photo_view_size[large][height]')); ?>

<?php echo $form->field($model, 'photo_view_size[large][width]', ['template' => '<div class="h5 col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">'.$model->getAttributeLabel('photo_view_size[large]').'</div>{beginWrapper}{input}{endWrapper}'.$photo_view_size_large_height.'{error}', 'horizontalCssClasses' => ['wrapper'=>'col-md-3 col-sm-4 col-xs-6 col-sm-offset-3', 'error'=>'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
	->textInput(['type'=>'number', 'min'=>0, 'maxlength'=>'4', 'placeholder'=>$model->getAttributeLabel('photo_view_size[large][width]')])
	->label($model->getAttributeLabel('photo_view_size[large]')); ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>