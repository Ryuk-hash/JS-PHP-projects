<?php

class SignupForm extends CFormModel
{
	public $email;
	public $username;
	public $password;
  public $password2;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username, email & password are required,
   * username must be b/w. 3-12 characters,
   * email needs to be unique,
   * password1 === password2,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required // 'match'
			array('username, email, password, password2', 'required'),
      // username must be between 3 and 12 characters
      array('username', 'length', 'min'=>3, 'max'=>12),
      // email needs to be unique
      array('email', 'unique', 'className' => 'User'),
      // password needs to be same as password2
      array('password2', 'compare', 'compareAttribute'=>'password'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
      'username' => 'Username',
      'email' => 'Email',
      'password' => 'Password',
      'password2' => 'Confirm Password',
		);
	}

  public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}
}
