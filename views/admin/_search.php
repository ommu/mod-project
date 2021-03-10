<?php
/**
 * Projects (projects)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\AdminController
 * @var $model ommu\project\models\search\Projects
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 7 February 2019, 19:54 WIB
 * @modified date 8 February 2019, 15:21 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\project\models\Projects;
use ommu\project\models\ProjectCategory;
?>

<div class="projects-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $category = ProjectCategory::getCategory();
		echo $form->field($model, 'cat_id')
			->dropDownList($category, ['prompt' => '']);?>

		<?php echo $form->field($model, 'companyName');?>

		<?php echo $form->field($model, 'project_name');?>

		<?php echo $form->field($model, 'project_desc');?>

		<?php $status = Projects::getStatus();
			echo $form->field($model, 'status')
			->dropDownList($status, ['prompt' => '']);?>

		<?php echo $form->field($model, 'start_date')
			->input('date');?>

		<?php echo $form->field($model, 'finish_date')
			->input('date');?>

		<?php echo $form->field($model, 'headline_date')
			->input('date');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'slug');?>

		<?php echo $form->field($model, 'comment')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'headline')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>