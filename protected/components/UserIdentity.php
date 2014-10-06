<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
                
                $user = Gebruiker::model()->find("gebruikersnaam='$this->username'");
                
                if($user && strlen($user->hash)>0) {
                    if (Utils::slowEquals($user->hash, Utils::createHash($this->password, $user->salt))) {
                        $this->errorCode=self::ERROR_NONE;
                        //Yii::app()->authManager->revoke('*', $user->gebruikersnaam);
                        //Yii::app()->authManager->revoke('user', $user->gebruikersnaam);
                        foreach(Yii::app()->authManager->getRoles($user->gebruikersnaam) as $role=>$object) {
                            Yii::app()->authManager->revoke($role, $user->gebruikersnaam);                            
                        }
                        if ($user->rol == 'administrator') {
                            Yii::app()->authManager->assign('admin', $user->gebruikersnaam);
                        } else {
                            Yii::app()->authManager->assign('user', $user->gebruikersnaam);                            
                        }
                    } else {
                        $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;                        
                    }
                } else {
                    $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;                    
                }
                
//                $user = Gebruiker::model()->find(
//                        "gebruikersnaam='$this->username' AND wachtwoord='$this->password'");
//                if ($user) {
//                    $this->errorCode=self::ERROR_NONE;
//                } else {
//                    $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
//                }
//                var_dump($user);die;
//		if(!isset($users[$this->username]))
//			$this->errorCode=self::ERROR_USERNAME_INVALID;
//		elseif($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}        
}