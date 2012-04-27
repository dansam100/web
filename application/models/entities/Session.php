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
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setUser($user)
		{
			$this->user = $user;
		}
		
		public function getSessionId()
		{
			return $this->sessionId;
		}
		
		public function setSessionId($sessionId)
		{
			$this->sessionId = $sessionId;
		}
		
		public function getToken()
		{
			return $this->token;
		}
		
		public function setToken($token)
		{
			$this->token = $token;
		}
    }
?>