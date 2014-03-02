<?php
namespace Beerfest\Core\Form;

interface Element
{
    public function getName();
    public function getType();
    public function getHtml();
}// Element