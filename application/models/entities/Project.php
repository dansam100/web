<?php
    //entities/Project.php
    /**
	 * @Entity @Table(name="project")
	 */
    class Project extends Entity
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
		
		
		public function user($user = null)
		{
			if(isset($user))
            {
                $this->user = $user;
            }
            return $this->user;
		}
		
		public function degree($degree = null)
		{
			if(isset($degree))
            {
                $this->degree = $degree;
            }
            return $this->degree;
		}
		
		public function description($description = null)
		{
			if(isset($description))
            {
                $this->description = $description;
            }
            return $this->description;
		}
		
		public function startDate($startDate = null)
		{
			if(isset($startDate))
            {
                $this->startDate = $startDate;
            }
            return $this->startDate;
		}
		
		public function endDate($endDate = null)
		{
			if(isset($endDate))
            {
                $this->endDate = $endDate;
            }
            return $this->endDate;
		}
		
		public function experience($experience = null)
		{
			if(isset($experience))
            {
                $this->experience = $experience;
            }
            return $this->experience;
		}
    }
?>