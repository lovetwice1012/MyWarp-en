<?php

namespace lovetwice1012\MyWarp\window;

use lovetwice1012\MyWarp\elements\Element;
use lovetwice1012\MyWarp\elements\ElementCustom;
use lovetwice1012\MyWarp\elements\Input;

use pocketmine\Player;

class CustomWindowForm extends WindowForm
{

    /** @var ElementCustom[] */
    public $elements = [];

    public function __construct(string $name, string $title, string $description = "")
    {
        $this->name = $name;
        $this->title = $title;

        $this->content = [
            "type" => "custom_form",
            "title" => $this->title,
            "content" => []
        ];

        $this->addLabel($description);
    }

    /**
     * @param mixed $data
     */
    public function setResponse($data): void
    {
        foreach($this->elements as $name => $element) {

            if(isset($data[$element->getArrayIndex()]))
                $element->setFinalData($data[$element->getArrayIndex()]);

        }

        parent::setResponse($data);
    }

    /**
     * @param ElementCustom $element
     */
    private function addElement(ElementCustom $element): void
    {
        $index = count($this->content["content"]);

        $element->setArrayIndex($index);

        $this->elements[$element->getName()] = $element;
        $this->content["content"][$index] = $element->getContent();
    }

    /**
     * @param String $name
     * @return ElementCustom|null
     */
    public function getElement(String $name): ?ElementCustom
    {
        if(empty($this->elements[$name])) return null;

        return $this->elements[$name];
    }

    /**
     * @param String $name
     * @param String $text
     * @param string $placeholder
     * @param string $value
     */
    public function addInput(String $name, String $text, String $placeholder = "", String $value = ""): void
    {
        $this->addElement(new Input($this, $name, $text, $placeholder, $value));
    }

    /**
     * @param String $text
     */
    public function addLabel(String $text): void
    {
        $this->content["content"][] = [
            "type" => "label",
            "text" => $text
        ];
    }


}