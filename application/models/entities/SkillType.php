<?php
    //entities/SkillType.php
    /**
	 * @Entity @Table(name="skilltype")
	 */
    class SkillType extends Entity
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