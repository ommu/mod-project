<?php
/**
 * @var $this AdminController
 * @var $model Projects
 * @var $form CActiveForm */

	if($model->isNewRecord || (!$model->isNewRecord && $setting->media_limit == 1)) {
		$validation = false;
	} else {
		$validation = true;
	}
	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('input#Projects_is_website').live('click', function() {
		$(this).parent('div').find('div').slideToggle();
	});
EOP;
	$cs->registerScript('website', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'projects-form',
	'enableAjaxValidation'=>$validation,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php 
		echo $form->errorSummary($model);
		if(Yii::app()->user->hasFlash('error'))
			echo Utility::flashError(Yii::app()->user->getFlash('error'));
		if(Yii::app()->user->hasFlash('success'))
			echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
		?>
	</div>
	<?php //begin.Messages ?>

	<fieldset class="clearfix">
		<div class="clear">
			<?php //begin.Basic Information ?>
			<div class="left">

				<div class="clearfix">
					<?php echo $form->labelEx($model,'cat_id'); ?>
					<div class="desc">
						<?php if(ProjectCategory::getCategory(1) != null) {
							echo $form->dropDownList($model,'cat_id', ProjectCategory::getCategory(1));
						} else {
							echo $form->dropDownList($model,'cat_id', array('prompt'=>'No Parent'));
						}?>
						<?php echo $form->error($model,'cat_id'); ?>
					</div>
				</div>
	
				<div class="clearfix">
					<?php echo $form->labelEx($model,'title'); ?>
					<div class="desc">
						<?php echo $form->textField($model,'title',array('maxlength'=>128,'class'=>'span-8')); ?>
						<?php echo $form->error($model,'title'); ?>
					</div>
				</div>
	
				<div class="clearfix">
					<?php echo $form->labelEx($model,'body'); ?>
					<div class="desc">
						<?php 
						//echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50));
						$this->widget('application.extensions.cleditor.ECLEditor', array(
							'model'=>$model,
							'attribute'=>'body',
							'options'=>array(
								'width'=>'100%',
								'height'=>250,
								'useCSS'=>true,
								'controls'=>'bold italic underline strikethrough subscript superscript | bullets numbering | outdent indent | alignleft center alignright justify | undo redo | rule image link unlink | cut copy paste pastetext | print source',
							),
							'value'=>$model->body,
						)); ?>
						<?php echo $form->error($model,'body'); ?>
					</div>
				</div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'client_input');?>
					<div class="desc">
						<?php
						if(!$model->isNewRecord)
							$model->client_input = $model->client->client_name;
						//echo $form->textField($model,'client_input',array('maxlength'=>64, 'class'=>'span-7'));
						$url = Yii::app()->controller->createUrl('client/ajaxadd');
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'model' => $model,
							'attribute' => 'client_input',
							'source' => Yii::app()->controller->createUrl('client/suggest'),
							'options' => array(
								//'delay '=> 50,
								'minLength' => 1,
								'showAnim' => 'fold',
								'select' => "js:function(event, ui) {
									if(ui.item.id != 0) {
										$('#Projects_client_id').val(ui.item.id);
									} else {
										$.ajax({
											type: 'post',
											url: '$url',
											data: {client_name: ui.item.value},
											dataType: 'json',
											success: function(response) {
												$('#Projects_client_id').val(response.client_id);
											}
										});											
									}		
								}"
							),
							'htmlOptions' => array(
								'class'	=> 'span-6',
							),
						));
						
						//$model->client_id = $model->isNewRecord ? 0 : $model->client_id;
						echo $form->hiddenField($model,'client_id');
						echo $form->error($model,'client_input'); ?>
					</div>
				</div>
				
				<?php if(!$model->isNewRecord) {?>
					<div class="clearfix">
						<?php echo $form->labelEx($model,'team_input');?>
						<div class="desc">
							<?php						
							//echo $form->textField($model,'team_input',array('maxlength'=>32, 'class'=>'span-6'));						
							$project = $model->project_id;
							$teamField = 'Projects_team_input';
							$url = Yii::app()->controller->createUrl('team/ajaxadd', array('type'=>'project'));
							$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
								'model' => $model,
								'attribute' => 'team_input',
								'source' => Yii::app()->controller->createUrl('team/suggest'),
								'options' => array(
									//'delay '=> 50,
									'minLength' => 1,
									'showAnim' => 'fold',
									'select' => "js:function(event, ui) {
										$.ajax({
											type: 'post',
											url: '$url',
											data: { project_id: '$project', user_id: ui.item.id},
											dataType: 'json',
											success: function(response) {
												$('form #$teamField').val('');
												$('form #team-suggest').append(response.data);
											}
										});
									}"
								),
								'htmlOptions' => array(
									'class'	=> 'span-6',
								),
							));
							echo $form->error($model,'team_input');
							?>
							<div id="team-suggest" class="suggest clearfix">
								<?php if($team != null) {
									foreach($team as $key => $val) {?>
									<div><?php echo $val->user->displayname;?><a href="<?php echo Yii::app()->controller->createUrl('team/delete',array('id'=>$val->team_id,'type'=>'project'));?>" title="<?php echo 'Delete';?>"><?php echo 'Delete';?></a></div>
								<?php }
								}?>
							</div>
						</div>
					</div>
				<?php }?>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'website');?>
					<div class="desc">
						<?php 
						$model->is_website = $model->website != '' ? 1 : 0;
						echo $form->checkBox($model,'is_website'); ?>
						<div class="mt-10 <?php echo $model->website == '' ? 'hide' : '';?>">
							<?php echo $form->textField($model,'website',array('maxlength'=>128, 'class'=>'span-6')); ?>
						</div>
						<?php echo $form->error($model,'website'); ?>
					</div>
				</div>
				
				<?php if(!$model->isNewRecord || ($model->isNewRecord && $setting->meta_keyword != '')) {?>
				<div class="clearfix">
					<?php echo $form->labelEx($model,'keyword'); ?>
					<div class="desc">
						<?php if(!$model->isNewRecord) {
							//echo $form->textField($model,'keyword',array('maxlength'=>32,'class'=>'span-6'));
							$url = Yii::app()->controller->createUrl('tag/add', array('type'=>'project'));
							$project = $model->project_id;
							$tagId = 'Projects_keyword';
							$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
								'model' => $model,
								'attribute' => 'keyword',
								'source' => Yii::app()->createUrl('globaltag/suggest'),
								'options' => array(
									//'delay '=> 50,
									'minLength' => 1,
									'showAnim' => 'fold',
									'select' => "js:function(event, ui) {
										$.ajax({
											type: 'post',
											url: '$url',
											data: { project_id: '$project', tag_id: ui.item.id, tag: ui.item.value },
											dataType: 'json',
											success: function(response) {
												$('form #$tagId').val('');
												$('form #keyword-suggest').append(response.data);
											}
										});
		
									}"
								),
								'htmlOptions' => array(
									'class'	=> 'span-6',
								),
							));
							echo $form->error($model,'keyword');
						}?>
						<div id="keyword-suggest" class="suggest clearfix">
							<?php 
							$arrKeyword = explode(',', $setting->meta_keyword);
							foreach($arrKeyword as $row) {?>
								<div class="d"><?php echo $row;?></div>
							<?php }
							if(!$model->isNewRecord) {
								if($tag != null) {
									foreach($tag as $key => $val) {?>
									<div><?php echo $val->tag->body;?><a href="<?php echo Yii::app()->controller->createUrl('tag/delete',array('id'=>$val->id,'type'=>'project'));?>" title="<?php echo 'Delete';?>"><?php echo 'Delete';?></a></div>
								<?php }
								}
							}?>
						</div>
					</div>
				</div>
				<?php }?>

			</div>
	
			<?php //begin.Other Information ?>
			<div class="right">

				<?php if(!$model->isNewRecord && $setting->media_limit == 1) {
					$model->old_media = $model->cover->media;
					echo $form->hiddenField($model,'old_media');
					
					if($model->old_media != '') {
						$images = Yii::app()->request->baseUrl.'/public/project/'.$model->project_id.'/'.$model->old_media; ?>
						<div class="clearfix">
							<?php echo $form->labelEx($model,'old_media'); ?>
							<div class="desc">
								<img src="<?php echo Utility::getTimThumb($images, 320, 150, 1);?>" alt="">
							</div>
						</div>
				<?php }
				}?>
	
				<?php if($model->isNewRecord || (!$model->isNewRecord && $setting->media_limit == 1)) {?>
				<div class="clearfix">
					<?php echo $form->labelEx($model,'media'); ?>
					<div class="desc">
						<?php echo $form->fileField($model,'media',array('maxlength'=>64)); ?>
						<?php echo $form->error($model,'media'); ?>
					</div>
				</div>
				<?php }?>
	
				<?php if(OmmuSettings::getInfo('site_type') == 1) {?>
				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'comment_code'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'comment_code'); ?>
						<?php echo $form->labelEx($model,'comment_code'); ?>
						<?php echo $form->error($model,'comment_code'); ?>
					</div>
				</div>
				<?php } else {
					$model->comment_code = 0;
					echo $form->hiddenField($model,'comment_code');
				}?>
	
				<?php if(OmmuSettings::getInfo('site_headline') == 1) {?>
				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'headline'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'headline'); ?>
						<?php echo $form->labelEx($model,'headline'); ?>
						<?php echo $form->error($model,'headline'); ?>
					</div>
				</div>
				<?php } else {
					$model->headline = 0;
					echo $form->hiddenField($model,'headline');
				}?>
	
				<div class="clearfix">
					<?php echo $form->labelEx($model,'status'); ?>
					<div class="desc">
						<?php echo $form->dropDownList($model,'status', array(
							0=>'Waiting',
							1=>'Process',
							2=>'Done',
						), array('prompt'=>'Choose')); ?>
						<?php echo $form->error($model,'status'); ?>
					</div>
				</div>

				<div class="clearfix">
					<?php echo $form->labelEx($model,'start_date'); ?>
					<div class="desc">
						<?php 
						!$model->isNewRecord ? ($model->start_date != '0000-00-00' ? $model->start_date = date('d-m-Y', strtotime($model->start_date)) : '') : '';
						//echo $form->textField($model,'start_date', array('class'=>'span-4'));
						$this->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$model, 
							'attribute'=>'start_date',
							//'mode'=>'datetime',
							'options'=>array(
								'dateFormat' => 'dd-mm-yy',
							),
							'htmlOptions'=>array(
								'class' => 'span-6',
							 ),
						));	?>
						<?php echo $form->error($model,'start_date'); ?>
					</div>
				</div>
	
				<div class="clearfix">
					<?php echo $form->labelEx($model,'finish_date'); ?>
					<div class="desc">
						<?php 
						!$model->isNewRecord ? ($model->finish_date != '0000-00-00' ? $model->finish_date = date('d-m-Y', strtotime($model->finish_date)) : '') : '';
						//echo $form->textField($model,'finish_date', array('class'=>'span-4'));
						$this->widget('zii.widgets.jui.CJuiDatePicker',array(
							'model'=>$model, 
							'attribute'=>'finish_date',
							//'mode'=>'datetime',
							'options'=>array(
								'dateFormat' => 'dd-mm-yy',
							),
							'htmlOptions'=>array(
								'class' => 'span-6',
							 ),
						));	?>
						<?php echo $form->error($model,'finish_date'); ?>
					</div>
				</div>
	
				<div class="clearfix publish">
					<?php echo $form->labelEx($model,'publish'); ?>
					<div class="desc">
						<?php echo $form->checkBox($model,'publish'); ?>
						<?php echo $form->labelEx($model,'publish'); ?>
						<?php echo $form->error($model,'publish'); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="submit clearfix">
			<label>&nbsp;</label>
			<div class="desc">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
<?php $this->endWidget(); ?>
