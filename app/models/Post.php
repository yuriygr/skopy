<?php

class Post extends ModelBase
{

    public $id;

    public $slug;

    public $subject;

    public $message;

    public $image;

    public $timestamp;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->belongsTo("users_id", "Users", "id");
    }

    public function afterFetch()
    {
        // Форматирование даты
        $this->timestamp = $this->formatDate($this->timestamp);
    }
}
