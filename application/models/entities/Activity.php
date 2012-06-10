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
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setUser($user)
		{
			$this->user = $user;
		}
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
		}
	}