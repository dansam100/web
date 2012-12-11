<?php
    //entities/Duration.php
    /**
	 * @Entity @Table(name="duration")
	 */
   	class Duration extends Entity
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
        
        public function getId(){
            return $this->id;
        }
		
		public function experience($experience = null)
		{
			if(isset($experience) && $this->experience !== $experience)
            {
                $this->experience = $experience;
            }
            return $this->experience;
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
