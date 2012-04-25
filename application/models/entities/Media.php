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
		/** @Column(type="string") */
		protected $type;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="profiles")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		
		public function __construct()
		{
			
		}
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function getType()
		{
			return $this->type;
		}
    }
?>