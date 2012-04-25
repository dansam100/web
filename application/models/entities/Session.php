<?php
    //entities/Session.php
    /**
	 * @Entity @Table(name="session")
	 */
    class Session
    {
    	protected $id;
		protected $user;
		protected $sessionId;
		protected $token;
    }
?>