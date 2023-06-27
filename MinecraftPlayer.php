<?php

class MinecraftPlayer
{
    public $name;
    public $uuid;

    public function __construct($username,$uuid)
    {
        $this->name = $username;
        $this->uuid = $uuid;
    }
}

?>
