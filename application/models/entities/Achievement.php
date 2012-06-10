<?php
    //entities/Achievement.php
    /**
	 * @Entity @Table(name="achievement")
	 */
    class Achievement
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @var Degree
		 * 
		 * @ManyToOne(targetEntity="Degree", inversedBy="achievements")
		 * @JoinColumn(name="degreeId", referencedColumnName="id")
	     */
		protected $degree = null;
		/**
	     * @var Experience
		 * 
		 * @ManyToOne(targetEntity="Experience", inversedBy="achievements")
		 * @JoinColumn(name="experienceId", referencedColumnName="id")
	     */
		protected $experience = null;
		/**
	     * @var Project
		 * 
		 * @ManyToOne(targetEntity="Project", inversedBy="achievements")
		 * @JoinColumn(name="projectId", referencedColumnName="id")
	     */
		protected $project = null;
		
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
		}
		
		public function getProject()
		{
			return $this->project;
		}
		
		public function setProject($project)
		{
			$this->project = $project;
		}
		
		public function getExperience()
		{
			return $this->experience;
		}
		
		public function setExperience($experience)
		{
			$this->experience = $experience;
		}
		
		public function getDegree()
		{
			return $this->getDegree;
		}
		
		public function setDegree($degree)
		{
			$this->degree = $degree;
		}
    }