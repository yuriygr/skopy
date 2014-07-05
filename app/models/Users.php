<?php


class Users extends ModelBase
{

    /**
     * @var integer
     *
     */
    public $id;

    /**
     * @var string
     *
     */
    public $login;

    /**
     * @var string
     *
     */
    public $password;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->hasMany("id", "Post", "users_id");
    }

}
