<?php

namespace Aleahy\ArtisanVueMake\Exceptions;

use Exception;

class TagAlreadyExistsException extends Exception
{
    public function __construct($tagName) {
        parent::__construct('The tag \'' . $tagName . '\' already exists in app.js');
    }
}
