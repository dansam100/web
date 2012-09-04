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
		
		public function getName()
		{
			return $this->name;
		}
    }
