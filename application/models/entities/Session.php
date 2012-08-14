<?php
    //entities/Session.php
    /**
	 * @Entity @Table(name="session")
	 */
    class Session
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="sessions")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/** @Column(type="string") */
		protected $sessionId;
		/** @Column(type="string") */
		protected $token;
		
		
		public function user($user = null)
		{
			if(isset($user))
            {
                $this->user = $user;
            }
            return $this->user;
		}
		
		public function sessionId($sessionId = null)
		{
			if(isset($sessionId))
            {
                $this->sessionId = $sessionId;
            }
            return $this->sessionId;
		}
		
		public function token($token = null)
		{
            if(isset($token))
            {
                $this->token = $token;
            }
            return $this->token;
		}
    }