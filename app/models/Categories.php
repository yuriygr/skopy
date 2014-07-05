<?php


class Categories extends ModelBase
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
    public $name;

    /**
     * @var string
     *
     */
    public $abbr;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->hasMany("abbr", "Post", "category");
    }

}
