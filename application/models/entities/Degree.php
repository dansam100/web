<?php
    //entities/Degree.php
    /**
	 * @Entity @Table(name="degree")
	 */
    class Degree
    {
    	protected $id;
		protected $status;
		protected $startDate;
		protected $endDate;
		protected $description;
		protected $location;
		protected $user;
    }
?>