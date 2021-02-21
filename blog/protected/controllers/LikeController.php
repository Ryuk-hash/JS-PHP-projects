<?php

class LikeController extends Controller
{
	public function loadModel($id)
	{
		$model=Like::model()->find('comment_id',$id);

		if($model===null || empty($model))
			return null;

		return $model;
	}

	public function actionCreate($comment_id, $url)
	{
		Like::model()->newLike($comment_id);
		Yii::app()->request->redirect($url);
	}
}