<?php
    //entities/Achievement.php
    /**
	 * @Entity @Table(name="achievement")
	 */
    class Achievement
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @ManyToOne(targetEntity="Degree", inversedBy="achievements")
	     */
		protected $degree;
		/**
	     * @ManyToOne(targetEntity="Experience", inversedBy="achievements")
	     */
		protected $experience;
		/**
	     * @ManyToOne(targetEntity="Project", inversedBy="achievements")
	     */
		protected $project;
    }
?>