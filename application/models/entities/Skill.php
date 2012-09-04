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
		
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function setType($type)
		{
			$this->type = $type;
		}
    }
?>