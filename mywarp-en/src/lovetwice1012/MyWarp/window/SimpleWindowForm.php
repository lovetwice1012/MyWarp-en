<?php

namespace lovetwice1012\MyWarp\window;

use lovetwice1012\MyWarp\elements\Button;
use lovetwice1012\MyWarp\Main;
use lovetwice1012\pocketmine\Player;

class SimpleWindowForm extends WindowForm
{


    /** @var String */
    public $description = "";

    /** @var Button[] */
    public $elements = [];


    public function __construct(String $name, String $title, String $description = "")
    {
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;

        $this->content = [
            "type" => "form",
            "title" => $this->title,
            "content" => $this->description,
            "buttons" => []
        ];
    }

    /**
     * @param String $name
     * @param String $text
     */
    public function addButton(String $name, String $text): void
    {
        if(isset($this->elements[$name])) return;

        $this->elements[$name] = new Button($name, $text, $this);
        $this->content["buttons"][] = $this->elements[$name]->getContent();
    }

    /**
     * @param String $name
     * @return Button
     */
    public function getButton(String $name): ?Button
    {
        if(empty($this->elements[$name])) return null;

        return $this->elements[$name];
    }

    /**
     * @return Button|null
     */
    public function getClickedButton(): ?Button
    {
        if($this->response === null) return null;

        return $this->elements[array_keys($this->elements)[$this->response]];
    }


}