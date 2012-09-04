<?php
    //entities/AddressType.php
    /**
	 * @Entity @Table(name="addresstype")
	 */
    class AddressType extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
		
		public function getName()
		{
			return $this->name;
		}
    }
?>