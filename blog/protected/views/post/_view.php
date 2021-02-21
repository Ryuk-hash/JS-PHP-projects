<?php date_default_timezone_set("Asia/Kolkata"); ?>
<div class="post">
<hr>
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?>
	</div>
	<br><hr>
	<div class="author">
		posted by <?php echo "<b>".ucwords($data->author->username)."</b>" . ' on ' . date('F j, Y',$data->create_time); ?>
	</div><br>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->content;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<br/>
		<?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
		Last updated on <?php echo date('F j, Y',$data->update_time); ?>
	</div>
</div><br><br>
