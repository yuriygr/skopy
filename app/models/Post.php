<?php

class Post extends ModelBase
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
    public $subject;

    /**
     * @var string
     *
     */
    public $message;

    /**
     * @var int
     *
     */
    public $timestamp;

    /**
     * @var integer
     *
     */
    public $category;

    /**
     * @var int
     *
     */
    public $comments_enabled;

    /**
     * @var string
     *
     */
    public $images;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->belongsTo("users_id", "Users", "id");
        $this->belongsTo("category", "Categories", "abbr");
        $this->belongsTo("id", "Comments", "post");
    }

    public function afterFetch()
    {
        // Форматирование даты
        $this->timestamp = $this->formatDate($this->timestamp);
        // Подсчёт кол-ва комментариев
        $this->count = Comments::count("post = ". $this->id);
    }
}
