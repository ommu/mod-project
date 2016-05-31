<?php
/**
 * @var $this LikeController
 * @var $model ProjectLikes
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('like_id'); ?><br/>
			<?php echo $form->textField($model,'like_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('project_id'); ?><br/>
			<?php echo $form->textField($model,'project_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('user_id'); ?><br/>
			<?php echo $form->textField($model,'user_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_date'); ?><br/>
			<?php echo $form->textField($model,'likes_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('likes_ip'); ?><br/>
			<?php echo $form->textField($model,'likes_ip',array('size'=>20,'maxlength'=>20)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton('Search'); ?>
		</li>
	</ul>
	<div class="clear"></div>
<?php $this->endWidget(); ?>
