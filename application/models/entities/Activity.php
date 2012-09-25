<?php   
	//entities/Activity.php
	/**
	 * @Entity @Table(name="activity")
	 */
	class Activity extends Entity
	{
		/** @Id @Column(type="integer") @GeneratedValue */
		protected $id;
		/** @Column(type="string") */
		protected $description;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="activities")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		
		public function user($user = null)
		{
			if(isset($user))
            {
                $this->user = $user;
            }
            return $this->user;
		}
		
		public function description($user = null)
		{
			if(isset($description))
            {
                $this->description = $description;
            }
            return $this->description;
		}
	}