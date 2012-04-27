<?php
    //enttities/Media.php
    /**
	 * @Entity @Table(name="media")
	 */
    class Media
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		/** @Column(type="string") */
		protected $value;
		/**
	     * @ManyToOne(targetEntity="MediaType", inversedBy="medias")
		 * @JoinColumn(name="type", referencedColumnName="name")
	     */
		protected $type;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="profiles")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setUser($user)
		{
			$this->user = $user;
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function getType($type)
		{
			$this->type = $type;
		}
		
		public function getValue()
		{
			return $this->value;
		}
		
		public function setValue($value)
		{
			$this->value = $value;
		}
    }
?>