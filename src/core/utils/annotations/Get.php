<?php

#[Attribute]
class Get extends Route
{
    public function __construct($path = "")
    {
        parent::__construct($path, 'GET');
    }
}
