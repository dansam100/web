<?php
namespace Rexume\Application\Models;

interface IModel
{
}

class Model
{
    /**
     * Override to invoke calls on embedded models directly
     * @param type $name
     * @return null
     */
    public function __get($name) {
        if(property_exists($this, $name)){
            return $this->$name;
        }
        elseif(property_exists($this->model, $name)){
            return $this->model->$name;
        }
        return null;
    }
    
    /**
     * Override to invoke calls on embedded models directly
     * @param string $name name of function to invoke
     * @return null
     */
    public function __call($name, $arguments) {
        if(method_exists($this, $name)){
            return call_user_func_array(array($this, $name), $arguments);
        }
        elseif(method_exists($this->model, $name)){
            return call_user_func_array(array($this->model, $name), $arguments);
        }
        return null;
    }
}
