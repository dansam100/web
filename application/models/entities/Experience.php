<?php
    //entitites/Experience.php
    /**
	 * @Entity @Table(name="experience")
	 */
    class Experience extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $position;
		/** @Column(type="string") */
		protected $department;
		/** @Column(type="string") */
		protected $description;
        /** @Column(type="datetime") */
		protected $startDate;
		/** @Column(type="datetime") */
		protected $endDate;
        /** @Column(type="boolean") */
        protected $isCurrent;
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
		
		public function user($user = null)
		{
			if(isset($user))
            {
                $this->user = $user;
            }
            return $this->user;
		}
		
		public function type($type = null)
		{
			if(isset($type))
            {
                $this->type = $type;
            }
            return $this->type;
		}
		
		public function description($description = null)
		{
			if(isset($description))
            {
                $this->description = $description;
            }
            return $this->description;
		}
		
		public function department($department = null)
		{
			if(isset($department))
            {
                $this->department = $department;
            }
            return $this->department;
		}
		
		public function position($position = null)
		{
			if(isset($position))
            {
                $this->position = $position;
            }
            return $this->position;
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
    }
?>