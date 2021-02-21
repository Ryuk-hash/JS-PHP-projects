<?php

/**
 * This is the model class for table "tbl_like".
 *
 * The followings are the available columns in table 'tbl_like':
 * @property integer $id
 * @property integer $create_time
 * @property integer $comment
 * @property integer $likes
 *
 * The followings are the available model relations:
 * @property Comment $comment
 */
class Like extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_like';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment_id, likes', 'required'),
			array('create_time, comment_id, likes', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, create_time, comment_id, likes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comment' => array(self::BELONGS_TO, 'Comment', 'comment'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'create_time' => 'Create Time',
			'comment_id' => 'Comment',
			'likes' => 'Likes',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('likes',$this->likes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Like the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
    if(parent::beforeSave())
    {
        if($this->isNewRecord)
            $this->create_time=time();
        return true;
    }
    else
        return false;
	}

	public function getLikes($comment_id) {
		$likes = Like::model()->find(array(
      'select'=>'*',
      'condition'=>'comment_id=:comment_id',
      'params'=>array(':comment_id'=>$comment_id)));

		return $likes;
	}

	public function newLike($comment_id) {
		$record = $this->getLikes($comment_id);

		if($record===null) {
			$like = new Like;
			$like->likes = 1;
			$like->comment_id = $comment_id;
			$like->save();
    } else {
			$record->likes = $record->likes + 1;
			$record->save();
    }
	}
}