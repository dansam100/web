<?php
    //entities/Address.php
    /**
	 * @Entity @Table(name="useraddress")
	 */
    class UserAddress extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/**  
		 * @var Address
		 *  
		 * @ManyToOne(targetEntity="UserAddress")
		 * @JoinColumn(name="addressId", referencedColumnName="id")
	     */
		protected $address = null;
		/**  
		 * @var User
		 *  
		 * @ManyToOne(targetEntity="User")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user = null;
		/** @Column(type="boolean") */
		protected $isDefault;
		/**
	     * @ AddressType
		 * 
		 * @ManyToOne(targetEntity="AddressType")
		 * @JoinColumn(name="type", referencedColumnName="name")
     	*/
		protected $type;
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function getStreet1()
		{
			return $this->address->street1;
		}
		
		public function setStreet1($street1)
		{
			$this->address->setStreet1($street1);
		}
		
		public function getStreet2()
		{
			return $this->address->street2;
		}
		
		public function setStreet2($street2)
		{
			$this->address->setStreet2($street2);
		}
		
		public function getCity()
		{
			return $this->address->city;
		}
		
		public function setCity($city)
		{
			$this->address->setCity($city);
		}
		
		public function getProvince()
		{
			return $this->address->province;
		}
		
		public function setProvince($province)
		{
			$this->address->setProvince($province);
		}
		
		public function getPostalCode()
		{
			return $this->address->postalCode;
		}
		
		public function setPostalCode($postalCode)
		{
			$this->address->setPostalCode($postalCode);
		}
		
		public function getCountry()
		{
			return $this->address->country;
		}
		
		public function setCountry($country)
		{
			$this->address->setCountry($country);
		}
    }
?>