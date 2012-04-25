<?php
    //entities/AddressType.php
    /**
	 * @Entity @Table(name="addresstype")
	 */
    class AddressType
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
    }
?>