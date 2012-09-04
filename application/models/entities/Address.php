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
		
		public function getStreet1()
		{
			return $this->street1;
		}
		
		public function setStreet1($street1)
		{
			$this->street1 = $street1;
		}
		
		public function getStreet2()
		{
			return $this->street2;
		}
		
		public function setStreet2($street2)
		{
			$this->street2 = $street2;
		}
		
		public function getCity()
		{
			return $this->city;
		}
		
		public function setCity($city)
		{
			$this->city = $city;
		}
		
		public function getProvince()
		{
			return $this->province;
		}
		
		public function setProvince($province)
		{
			$this->province = $province;
		}
		
		public function getPostalCode()
		{
			return $this->postalCode;
		}
		
		public function setPostalCode($postalCode)
		{
			$this->postalCode = $postalCode;
		}
		
		public function getCountry()
		{
			return $this->country;
		}
		
		public function setCountry($country)
		{
			$this->country = $country;
		}
    }
?>