<?php
/**
 * Project Teams (project-team)
 * @var $this app\components\View
 * @var $this ommu\project\controllers\data\TeamController
 * @var $model ommu\project\models\ProjectTeam
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 12 February 2019, 16:53 WIB
 * @link https://bitbucket.org/ommu/project
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="project-team-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
