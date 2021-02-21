<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Create Post', 'url'=>array('create'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Update Post', 'url'=>array('update', 'id'=>$model->id), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Delete Post', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>!Yii::app()->user->isGuest),
	array('label'=>'Manage Post', 'url'=>array('admin'), 'visible'=>!Yii::app()->user->isGuest),
);
?>
	<?php date_default_timezone_set("Asia/Kolkata"); ?>

<h1>View Post #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'tags',
		'create_time:datetime',
		'update_time:datetime',
		array('label' => 'Author', 'value' => ucwords($model->author->username) ." [ ". $model->author->email . " ] "),
	),
)); ?>

<center>
<div id="comments">
    <?php if($model->commentCount>=1): ?>
        <br><h3>
            <?php echo $model->commentCount . ' comment(s)'; ?>
        </h3>
				<hr>
 
        <?php $this->renderPartial('_comments',array(
            'post'=>$model,
            'comments'=>$model->comments,
        )); ?>
    <?php endif; ?>
<br>
		<h3>Leave a Comment</h3>
 
 <?php if(Yii::app()->user->hasFlash('commentSubmitted')): ?>
		 <div class="flash-success">
				 <?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
		 </div>
 <?php else: ?>
		 <?php $this->renderPartial('/comment/_form',array(
				 'model'=>$comment,
		 )); ?>
 <?php endif; ?>
</div></center>


