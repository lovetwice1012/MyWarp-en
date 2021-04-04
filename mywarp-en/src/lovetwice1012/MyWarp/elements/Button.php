<?php

namespace lovetwice1012\MyWarp\elements;

use lovetwice1012\MyWarp\window\SimpleWindowForm;
use lovetwice1012\MyWarp\window\WindowForm;

class Button extends Element
{


    public function __construct(String $name, String $text, WindowForm $form)
    {
        parent::__construct($form, $name, $text);

        $this->content = [
            "text" => $this->text
        ];
    }

}