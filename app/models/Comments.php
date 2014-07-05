<?php

class Comments extends ModelBase
{

    /**
     * @var int
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
    public $tripcode;

    /**
     * @var text
     *
     */
    public $message;

    /**
     * @var int
     *
     */
    public $post;
    
    /**
     * @var int
     *
     */
    public $timestamp;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->hasMany("post", "Post", "id");
    }

    public function beforeSave()
    {
        global $NameAndTripcode;
        // Форматирование трипкода и имени
        $NameAndTripcode = $this->calcNameAndTripcode($this->name);
        $this->name = $NameAndTripcode[0];
        $this->tripcode = $NameAndTripcode[1];
    }

    public function afterFetch()
    {
        // Форматирование даты
        $this->timestamp = $this->formatDate($this->timestamp);
        // Форматирование трипкодов
        $this->tripcode = $this->tripcode ? '!'.$this->tripcode : '';
    }
}
