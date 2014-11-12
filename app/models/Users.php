<?php

class Users extends ModelBase
{

    public $id;

    public $login;

    public $password;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->hasMany("id", "Post", "users_id");
    }

}
