<?php
    //entitites/Experience.php
    /**
	 * @Entity @Table(name="experience")
	 */
    class Experience
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $position;
		/** @Column(type="string") */
		protected $department;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @ManyToOne(targetEntity="ExperienceType")
		 * @JoinColumn(name="type", referencedColumnName="type")
	     */
		protected $type;
		/**
	     * @ManyToOne(targetEntity="User")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/**
	     * @ManyToOne(targetEntity="Company")
		 * @JoinColumn(name="companyId", referencedColumnName="id")
	     */
		protected $company;
		
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
		
		public function setType($type)
		{
			$this->type = $type;
		}
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
		}
		
		public function getDepartment()
		{
			return $this->description;
		}
		
		public function setDepartment($department)
		{
			$this->department = $department;
		}
		
		public function getPosition()
		{
			return $this->position;
		}
		
		public function setPosition($position)
		{
			$this->position = $position;
		}
    }
?>