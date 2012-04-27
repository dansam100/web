<?php
    //entities/Duration.php
    /**
	 * @Entity @Table(name="duration")
	 */
   	class Duration
   	{
   		/** @Id @Column(type="integer") @GeneratedValue */
   		protected $id;
		/** @Column(type="datetime") */
		protected $startDate;
		/** @Column(type="datetime") */
		protected $endDate;
		/**
	     * @ManyToOne(targetEntity="Experience")
		 * @JoinColumn(name="experienceId", referencedColumnName="id")
	     */
		protected $experience = null;
		
		public function getExperience()
		{
			return $this->experience;
		}
		
		public function setExperience($experience)
		{
			$this->experience = $experience;
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
			return $this->startDate;
		}
		
		public function setEndDate($endDate)
		{
			$this->endDate = $endDate;
		}
   	}
?>