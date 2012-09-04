<?php
//entities/Entity.php
class Entity
{
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}

