<?php
    require_once("Entity.php");
    //entities/Achievement.php
    /**
	 * @Entity @Table(name="achievement")
	 */
    class Achievement extends Entity
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
		
		
        public function getId()
        {
            return $this->id;
        }
        
        public function description($description = null)
		{
			if(isset($description))
            {
                $this->description = $description;
            }
            return $this->description;
        }
        
        public function project($project = null)
		{
			if(isset($project))
            {
                $this->project = $project;
            }
            return $this->project;
        }
        
        public function experience($experience = null)
		{
			if(isset($experience))
            {
                $this->experience = $experience;
            }
            return $this->experience;
        }
        
        public function degree($degree = null)
		{
			if(isset($degree))
            {
                $this->degree = $degree;
            }
            return $this->degree;
        }
    }