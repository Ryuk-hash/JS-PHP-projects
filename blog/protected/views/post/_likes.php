<?php $likes = Like::model()->getLikes($comment_id);
 $var1; $var2; 
 if($likes === null) { $var1 = 0; $var2 = null; } 
 else { $var1=$likes->likes; $var2 = $likes->id; }; ?>

<div class="like" id="c<?php echo $var2; ?>">
	<br>

	<div class="like">
		<?php echo "<b>Likes: " . $var1 .  "</b>"?>
	</div>

	<br>
</div>
