<?php
    //enttities/Media.php
    /**
	 * @Entity @Table(name="media")
	 */
    class Media extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		/** @Column(type="string") */
		protected $value;
		/**
	     * @ManyToOne(targetEntity="MediaType", inversedBy="media", cascade={"persist"})
		 * @JoinColumn(name="type", referencedColumnName="id")
	     */
		protected $type;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="media")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		
		public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
            {
                $this->user = $user;
            }
            return $this->user;
		}
        
        public function name($name = null)
		{
			if(isset($name))
            {
                $this->name = $name;
            }
            return $this->name;
        }
        
        public function type($type = null)
		{
			if(isset($type))
            {
                $this->type = $type;
            }
            return $this->type;
        }
        
        public function value($value = null)
		{
			if(isset($value))
            {
                $this->value = $value;
            }
            return $this->value;
        }
    }
