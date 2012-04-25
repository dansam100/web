<?php
    //entitites/Experience.php
    /**
	 * @Entity @Table(name="experience")
	 */
    class Experience
    {
    	protected $id;
		protected $position;
		protected $department;
		protected $description;
		protected $type;
		protected $user;
		protected $company;
    }
?>