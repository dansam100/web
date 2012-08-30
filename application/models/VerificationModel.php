<?php
namespace Rexume\Models;

class VerificationModel extends Model
{
    protected $model;
    /**
     * Constructor
     * @param \User $user
     */
    public function __construct($user = null)
    {
        $this->model = $user;
    }
}
