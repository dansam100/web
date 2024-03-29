<?php
    //entities/MediaType.php
    /**
	 * @Entity @Table(name="mediatype")
	 */
    class MediaType extends Entity
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
