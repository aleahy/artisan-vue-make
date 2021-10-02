<?php

namespace Aleahy\ArtisanVueMake\Exceptions;

use Exception;

class ComponentAlreadyExistsException extends Exception
{
    public function __construct() {
        parent::__construct('Component already exists');
    }
}
