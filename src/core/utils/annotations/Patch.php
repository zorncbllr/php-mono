<?php

#[Attribute]
class Patch extends Route
{
    public function __construct($path = "")
    {
        parent::__construct($path, 'PATCH');
    }
}
