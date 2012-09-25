<?php
    //entities/Degree.php
    /**
	 * @Entity @Table(name="degree")
	 */
    class Degree extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
        /** @Column(type="string") */
        protected  $school;
        /** @Column(type="string") */
        protected $program;
        /** @Column(type="string") */
		protected $status;
		/** @Column(type="datetime") */
		protected $startDate;
		/** @Column(type="datetime") */
		protected $endDate;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @ManyToOne(targetEntity="Address")
		 * @JoinColumn(name="locationId", referencedColumnName="id")
	     */
		protected $location;
		/**
	     * @ManyToOne(targetEntity="User")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		
		public function userName($user = null)
		{
			if(isset($user))
            {
                $this->username = $user;
            }
            return $this->username;
        }
        
        public function status($status = null)
		{
			if(isset($status))
            {
                $this->status = $status;
            }
            return $this->status;
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
        
        public function location($location = null)
		{
			if(isset($location))
            {
                $this->location = $location;
            }
            return $this->location;
        }
    }
?>