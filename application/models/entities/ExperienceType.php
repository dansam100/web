<?php
    //entities/ExperienceType.php
    /**
	 * @Entity @Table(name="experiencetype")
	 */
    class ExperienceType extends Entity
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