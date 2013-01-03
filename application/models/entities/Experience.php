<?php
    use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
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
		protected $companyName;
		/** @Column(type="string") */
		protected $description;
        /**
	     * @var Duration[]
		 * 
		 * @OneToMany(targetEntity="Duration", mappedBy="experience", cascade={"persist"})
	     */
		protected $durations;
        /** @Column(type="boolean", name="current") */
        protected $isCurrent;
		/**
	     * @ManyToOne(targetEntity="ExperienceType", cascade={"persist"})
		 * @JoinColumn(name="type", referencedColumnName="id")
	     */
		protected $type;
		/**
	     * @ManyToOne(targetEntity="User")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
        /**
	     * @var Achievement[]
		 * 
		 * @OneToMany(targetEntity="Achievement", mappedBy="experience", cascade={"persist"})
	     */
        protected $achievements;
        
        public function __construct()
		{
			$this->achievements = new ArrayCollection();
            $this->durations = new ArrayCollection();
		}
        
        public function getId()
		{
			return $this->id;
		}
        
        public function isCurrent($isCurrent = null){
            if(isset($isCurrent)){
                $this->isCurrent = $isCurrent;
            }
            return $this->isCurrent;
        }
		
		public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
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
        
        public function achievements($achievement = null)
        {
            if(isset($achievement))
            {
                $achievement->experience($this);
                $this->achievements[] = $achievement;
            }
            return $this->achievements;
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
        
        public function durations($duration = null){
            if(isset($duration)){
                $duration->experience($this);
                $this->durations[] = $duration;
            }
            return $this->durations;
        }
    }
    