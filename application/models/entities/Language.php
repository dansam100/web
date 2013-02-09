<?php
    //entities/Language.php
    /**
	 * @Entity @Table(name="language")
	 */
    class Language extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
        /**
	     * @ManyToOne(targetEntity="User", inversedBy="languages")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
        protected $user;
        
        public function getId(){
            return $this->id;
        }
		
		public function name($name = null)
		{
			if(isset($name))
            {
                $this->name = $name;
            }
            return $this->name;
		}
        
        public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
            {
                $this->user = $user;
            }
            return $this->user;
		}
    }
    