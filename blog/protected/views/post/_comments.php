<?php foreach($comments as $comment): ?>
<?php $currUrl = Yii::app()->controller->createUrl(Yii::app()->controller->action->id, $_GET); ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=>'Permalink to this comment',
	)); ?>

	<div class="author">
		<?php echo $comment->authorLink; ?> <b>says:</b> <?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

	<?php echo "Like:   " . CHtml::link('👍', array('like/create', 'comment_id'=>$comment->id, 'url' => $currUrl)); ?>

	<div class="time">
		<?php date_default_timezone_set('Asia/Kolkata'); echo "<b>Date - </b>" . date('F j, Y \a\t h:i a',$comment->create_time); ?>
	</div><br>

	<?php $this->renderPartial('_likes',array('comment_id'=>$comment->id)); ?><br>

</div>

<hr>

<?php endforeach; ?>


