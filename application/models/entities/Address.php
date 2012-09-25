<?php
    //entities/Address.php
    /**
	 * @Entity @Table(name="address")
	 */
    class Address extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $street1;
		/** @Column(type="string") */
		protected $street2;
		/** @Column(type="string") */
		protected $city;
		/** @Column(type="string") */
		protected $province;
		/** @Column(type="string") */
		protected $postalCode;
		/** @Column(type="string") */
		protected $country;
		
		public function street1($street1 = null)
		{
			if(isset($street1))
            {
                $this->street1 = $street1;
            }
            return $this->street1;
		}
		
		public function street2($street2 = null)
		{
			if(isset($street2))
            {
                $this->street2 = $street2;
            }
            return $this->street2;
		}
		
		public function city($city = null)
		{
			if(isset($city))
            {
                $this->city = $city;
            }
            return $this->city;
		}
		
		public function province($province = null)
		{
			if(isset($province))
            {
                $this->province = $province;
            }
            return $this->province;
		}
		
		public function postalCode($postalCode = null)
		{
			if(isset($postalCode))
            {
                $this->postalCode = $postalCode;
            }
            return $this->postalCode;
		}
		
		public function country($country = null)
		{
			if(isset($country))
            {
                $this->country = $country;
            }
            return $this->country;
		}
    }
?>