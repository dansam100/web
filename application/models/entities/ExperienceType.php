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
