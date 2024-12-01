<?php

#[Attribute]
class Delete extends Route
{
    public function __construct($path = "")
    {
        parent::__construct($path, 'DELETE');
    }
}
