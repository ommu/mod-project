<?php
/**
 * @var $this AdminController
 * @var $model Projects
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Projects'=>array('manage'),
		$model->title=>array('view','id'=>$model->project_id),
		'Update',
	);

	$url = Yii::app()->controller->createUrl('media/ajaxmanage', array('id'=>$model->project_id,'type'=>'admin'));
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$.ajax({
		type: 'get',
		url: '$url',
		dataType: 'json',
		//data: { id: '$id' },
		success: function(render) {
			$('.horizontal-data #media-render #upload').before(render.data);
		}
	});
EOP;
	$setting->media_limit != 1 ? $cs->registerScript('ajaxmanage', $js, CClientScript::POS_END) : '';
?>

<div class="form" <?php echo $setting->media_limit != 1 ? 'name="post-on"' : ''; ?>>
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'setting'=>$setting,
		'team'=>$team,
		'tag'=>$tag,
	)); ?>
</div>

<?php if($setting->media_limit != 1) {?>
<div class="boxed mt-15">
	<h3>Project Photo</h3>
	<div class="clearfix horizontal-data" name="four">
		<ul id="media-render">
			<li id="upload" <?php echo (count(ProjectMedia::getPhoto($model->project_id)) == $setting->media_limit) ? 'class="hide"' : '' ?>>
				<a id="upload-gallery" href="<?php echo Yii::app()->controller->createUrl('media/ajaxadd', array('id'=>$model->project_id,'type'=>'admin'));?>" title="Upload Photo">Upload Photo</a>
				<img src="<?php echo Utility::getTimThumb(Yii::app()->request->baseUrl.'/public/project/project_default.png', 320, 250, 1);?>" alt="" />
			</li>
		</ul>
	</div>
</div>
<?php }?>