<?php
    //entities/Language.php
    /**
	 * @Entity @Table(name="language")
	 */
    class Language extends Entity
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