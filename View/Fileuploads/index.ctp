<div class="fileuploads index">
	<h2><?php echo __('Files List'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo __('#'); ?></th>
			<th><?php echo $this->Paginator->sort('path', 'Name'); ?></th>
			<th><?php echo $this->Paginator->sort('file_type', 'Type'); ?></th>
			<th><?php echo $this->Paginator->sort('file_size', 'Size'); ?></th>
			<th><?php echo $this->Paginator->sort('created', 'Uploaded on'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php 
	 $i = 1;
	 $currentpage = $this->Paginator->current(); 
	 $currentpage = $currentpage-1; 
	 $limit = 20;
	?>
	<?php foreach ($fileuploads as $fileupload): ?>
	<tr>
		<td><?php echo $limit*$currentpage+$i++;?>&nbsp;</td>
		<td><?php echo basename($fileupload['Fileupload']['path']); ?>&nbsp;</td>
		<td><?php echo h($fileupload['Fileupload']['file_type']); ?>&nbsp;</td>
		<td><?php echo $this->Number->toReadableSize($fileupload['Fileupload']['file_size']); ?>&nbsp;</td>
		<td><?php echo $this->Time->format('Y/m/d H:i A',$fileupload['Fileupload']['created']); ?>&nbsp;</td>		
		<td class="actions">
			<?php echo $this->Html->link(__('View/Download'), $fileupload['Fileupload']['path']); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $fileupload['Fileupload']['id']), null, __('Are you sure you want to delete this file?')); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} files out of {:count} total, starting on file #{:start}, ending on #{:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Upload New File'), '/my_upload/upload'); ?></li>
		<li><?php echo $this->Html->link(__('Upload Multiple Files'), '/my_upload/multi_upload'); ?></li>
		<li><?php echo $this->Form->postLink(__('Scan Upload Folder'), array('action' => 'scanfiles'),null, __('This action will be delete all your existent data. Are you sure you want do that?')); ?></li>
	</ul>
</div>
