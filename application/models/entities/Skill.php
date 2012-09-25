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
	     * @ManyToOne(targetEntity="SkillType")
		 * @JoinColumn(name="type", referencedColumnName="name")
     	*/
		protected $type;
		
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
    }
?>