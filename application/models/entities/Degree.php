<?php
    //entities/Degree.php
    /**
	 * @Entity @Table(name="degree")
	 */
    class Degree
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
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
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setUser($user)
		{
			$this->user = $user;
		}
		
		public function getStatus()
		{
			return $this->status;
		}
		
		public function setStatus($status)
		{
			$this->status = $status;
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
		
		public function setEndState($endDate)
		{
			$this->endDate = $endDate;
		}
		
		public function getLocation()
		{
			return $this->location;
		}
		
		public function setLocation($location)
		{
			$this->location = $location;
		}
    }
?>