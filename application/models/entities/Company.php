<?php
    //entitiies/Company.php
    /**
	 * @Entity @Table(name="company")
	 */
    class Company extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		/**
	     * @var Address
		 * 
		 * @ManyToOne(targetEntity="Address")
		 * @JoinColumn(name="locationId", referencedColumnName="id")
	     */
		protected $location;
		
		public function name($name = null)
		{
			if(isset($name))
            {
                $this->name = $name;
            }
            return $this->name;
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