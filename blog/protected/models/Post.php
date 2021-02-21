<?php

class Post extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;

	public $status = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
			return array(
				array('title, content, status, author_id', 'required'),
				array('title', 'length', 'max'=>128),
					array('status', 'in', 'range'=>array(1,2,3)),
					array('tags', 'match', 'pattern'=>'/^[\w\s,]+$/',
							'message'=>'Tags can only contain word characters.'),
					array('tags', 'normalizeTags'),
					array('title, status', 'safe', 'on'=>'search'),
			);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
			return array(
					'author' => array(self::BELONGS_TO, 'User', 'author_id'),
					'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time DESC'),
					'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),
					'likes' => array(self::HAS_MANY, 'Like', array('id'=>'comment_id'), 'through'=>'comments'),
			);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'content' => 'Content',
			'tags' => 'Tags',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'author_id' => 'Author',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('author_id',$this->author_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function normalizeTags($attribute,$params)
	{
    $this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}
	
	public function getUrl()
	{
		return Yii::app()->createUrl('post/view', array(
				'id'=>$this->id,
				'title'=>$this->title,
		));
	}

	protected function beforeSave()
	{
    if(parent::beforeSave())
    {
        if($this->isNewRecord)
        {
            $this->create_time=$this->update_time=time();
            $this->author_id=Yii::app()->user->id;
        }
        else
            $this->update_time=time();
        return true;
    }
    else
        return false;
	}

	// Our aim is to update the old tags table, with new ones (increment): Happens after the SAVE operation and compares with _oldTags variable
	protected function afterSave()
	{
    parent::afterSave();
    Tag::model()->updateFrequency($this->_oldTags, $this->tags);
	}
 
	private $_oldTags;
 
	// In order to see which tags were updated, we need the olds tags first - afterFind is used to do that and store it in _oldTags variable
	protected function afterFind()
	{
    parent::afterFind();
    $this->_oldTags=$this->tags;
	}

	// It first deletes all those comments whose post_id is the same as the ID of the deleted post; it then updates the tbl_tag table for the tags of the deleted post.
	protected function afterDelete()
	{
    parent::afterDelete();
    Comment::model()->deleteAll('post_id='.$this->id);
    Tag::model()->updateFrequency($this->tags, '');
	}

public function addComment($comment)
	{
		if(Yii::app()->params['commentNeedApproval'])
			$comment->status=Comment::STATUS_PENDING;
		else
			$comment->status=Comment::STATUS_APPROVED;
		$comment->post_id=$this->id;
		return $comment->save();
	}

	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=CHtml::link(CHtml::encode($tag), array('post/index', 'tag'=>$tag));
		return $links;
	}

}
