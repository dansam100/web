<?php
    //entities/Project.php
    /**
	 * @Entity @Table(name="project")
	 */
    class Project
    {
    	protected $id;
		protected $name;
		protected $degree;
		protected $experience;
		protected $description;
		protected $startDate;
		protected $endDate;
    }
?>