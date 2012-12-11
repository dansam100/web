<?php
    //entities/Skill.php
    /**
	 * @Entity @Table(name="skill")
	 */
    class Skill extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		/**
	     * @ManyToOne(targetEntity="SkillType", cascade={"persist"})
		 * @JoinColumn(name="type", referencedColumnName="id")
     	*/
		protected $type;
        /**
	     * @ManyToOne(targetEntity="User")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		
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
        
        public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
            {
                $this->user = $user;
            }
            return $this->user;
		}
    }
