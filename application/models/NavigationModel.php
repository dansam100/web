<?php
namespace Rexume\Application\Models;
class NavigationModel extends Model
{
    private $error;
    
    public function error($error = null)
    {
        if(!empty($error))
        {
            $this->error = $error;
        }
        return $this->error;
    }
}

