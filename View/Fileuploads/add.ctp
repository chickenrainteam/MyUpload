<div class="fileuploads form">
<?php echo $this->Form->create('Fileupload', array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Choose File to Upload'); ?></legend>
	<?php
		echo $this->Form->input('path', array('type'=>'file', 'label'=> false));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Files List'), '/my_upload'); ?></li>
		<li><?php echo $this->Html->link(__('Upload Multiple Files'), '/my_upload/multi_upload'); ?></li>
	</ul>
</div>
