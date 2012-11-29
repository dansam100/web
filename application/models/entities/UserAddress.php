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
		
		public function user($user = null)
		{
			if(isset($user))
            {
                $this->user = $user;
            }
            return $this->user;
		}
		
		public function street1($street1 = null)
		{
			if(isset($street1))
            {
                $this->address->street1 = $street1;
            }
            return $this->address->street1;
		}
		
		public function street2($street2 = null)
		{
			if(isset($street2))
            {
                $this->address->street2 = $street2;
            }
            return $this->address->street2;
		}
		
		public function city($city = null)
		{
			if(isset($city))
            {
                $this->address->city = $city;
            }
            return $this->address->city;
		}
		
		public function province($province = null)
		{
			if(isset($province))
            {
                $this->address->province = $province;
            }
            return $this->address->province;
		}
		
		public function postalCode($postalCode = null)
		{
			if(isset($postalCode))
            {
                $this->address->postalCode = $postalCode;
            }
            return $this->address->postalCode;
		}
		
		public function country($country = null)
		{
			if(isset($country))
            {
                $this->address->country = $country;
            }
            return $this->address->country;
		}
        
        public function isDefault($isDefault = null)
		{
			if(isset($isDefault))
            {
                $this->user->defaultAddress = $this;
            }
            return ($this->address == $this->user->defaultAddress);
		}
    }
