<?php

namespace LittleThings;

use JsonSerializable;
use DateTime;

class Post implements JsonSerializable
{
    /**
     * @var integer
     **/
    protected $id;

    /**
     * @var DateTime
     **/
    protected $date;

    /**
     * @var integer
     **/
    protected $authorId;

    /**
     * @var string
     **/    
    protected $title;

    /**
     * @var string
     **/    
    protected $slug;

    /**
     * Constructor
     *
     * @var integer $id
     * @var string $date
     * @var integer $authorId
     * @var string $title
     * @var string $slug
     * @return null
     **/
    public function __construct($id, $date, $authorId, $title, $slug)
    {
        $this->id       = $id;
        $this->date     = new DateTime($date);
        $this->authorId = $authorId;
        $this->title    = $title;
        $this->slug     = $slug;
    }

    /**
     * Overloader to get inaccessible properties
     *
     * @param string $name
     * @return mixed
     **/
    public function __get($name)
    {
        return $this->$name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'authorId' => $this->authorId,
            'title' => $this->title,
            'slug' => $this->slug
        ];
    }
}