<?php
    //entities/Address.php
    /**
	 * @Entity @Table(name="address")
	 */
    class Address
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
    }
?>