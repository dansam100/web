<?php
    //entities/Project.php
    /**
	 * @Entity @Table(name="project")
	 */
    class Project
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		/**
	     * @ManyToOne(targetEntity="Degree")
		 * @JoinColumn(name="degreeId", referencedColumnName="id")
	     */
		protected $degree;
		/**
	     * @ManyToOne(targetEntity="Experience")
		 * @JoinColumn(name="experienceId", referencedColumnName="id")
	     */
		protected $experience;
		/** @Column(type="string") */
		protected $description;
		/** @Column(type="datetime") */
		protected $startDate;
		/** @Column(type="datetime") */
		protected $endDate;
		
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function getDegree()
		{
			return $this->degree;
		}
		
		public function setType($degree)
		{
			$this->degree = $degree;
		}
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
		}
		
		public function getStartDate()
		{
			return $this->startDate;
		}
		
		public function setStartDate($startDate)
		{
			$this->startDate = $startDate;
		}
		
		public function getEndDate()
		{
			return $this->endDate;
		}
		
		public function setEndDate($endDate)
		{
			$this->endDate = $endDate;
		}
		
		public function getExperience()
		{
			return $this->experience;
		}
		
		public function setExperience($experience)
		{
			$this->experience = $experience;
		}
    }
?>