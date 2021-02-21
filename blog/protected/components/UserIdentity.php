<?php
class UserIdentity extends CUserIdentity
{
    private $_id;
 
    // has a default constructor which initalizes the (username, password) into it
    public function authenticate()
    {
        $username=strtolower($this->username);
        $user=User::model()->find('LOWER(username)=?', array($username));

        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;

        else if(!$user->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            
        else
        {
            $this->_id=$user->id;
            $this->username=$user->username;
            $this->errorCode=self::ERROR_NONE;
        }
        
        return $this->errorCode==self::ERROR_NONE;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}