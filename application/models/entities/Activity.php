<?php
	//entities/Activity.php
	/**
	 * @Entity @Table(name="activity")
	 */
	class Activity
	{
		/** @Id @Column(type="integer") @GeneratedValue */
		protected $id;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="activities")
	     */
		protected $user;
	}
?>