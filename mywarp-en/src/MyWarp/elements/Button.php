<?php

namespace MyWarp\elements;

use MyWarp\window\SimpleWindowForm;
use MyWarp\window\WindowForm;

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