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
		
        public function getId(){
            return $this->id;
        }
        
		public function name($name = null)
		{
			if(isset($name))
            {
                $this->name = $name;
            }
            return $this->name;
		}
    }
?>