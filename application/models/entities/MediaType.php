<?php
    //entities/MediaType.php
    /**
	 * @Entity @Table(name="mediatype")
	 */
    class MediaType
    {
		/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $name;
    }
?>