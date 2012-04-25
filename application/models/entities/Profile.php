<?php
    //entities/Profile.php
    use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
    
	/**
	 * @Entity @Table(name="profile")
	 */
    class Profile
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $objective;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $lastName;
		/** @Column(type="string") */
		protected $status;
		
		/**
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
	     */
		protected $defaultEmail;
		/**
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
	     */
		protected $defaultPhone;
		/**
	     * @ManyToOne(targetEntity="Address", inversedBy="profiles")
	     */
		protected $defaultAddress;
		/**
	     * @ManyToOne(targetEntity="User", inversedBy="profiles")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		/**
	     * @ManyToMany(targetEntity="Address", inversedBy="profiles")
	     */
		protected $addresses = null;
		/**
	     * @ManyToMany(targetEntity="Experience", inversedBy="profiles")
	     */
		protected $experiences = null;
		/**
	     * @ManyToMany(targetEntity="Degree", inversedBy="profiles")
	     */
		protected $degrees = null;
		/**
	     * @ManyToMany(targetEntity="Media", inversedBy="profiles")
	     */
		protected $media = null;
		/**
	     * @ManyToMany(targetEntity="Skill", inversedBy="profiles")
	     */
		protected $skills = null;
		/**
	     * @ManyToMany(targetEntity="Language", inversedBy="profiles")
	     */
		protected $languages = null;
		/**
	     * @ManyToMany(targetEntity="Activity", inversedBy="profiles")
	     */
		protected $activities = null;
		/**
	     * @ManyToMany(targetEntity="Category", inversedBy="categories")
	     */
		protected $categories = null;
		
		
		public function __construct()
		{
			$this->$addresses = new ArrayCollection();
			$this->$experiences = new ArrayCollection();
			$this->$degrees = new ArrayCollection();
			$this->$media = new ArrayCollection();
			$this->$skills = new ArrayCollection();
			$this->$languages = new ArrayCollection();
			$this->$activities = new ArrayCollection();
			$this->$categories = new ArrayCollection();
		}
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function setUser($user)
		{
			$user->addProfile($this);
			$this->user = $user;
		}
		
		
    }
?>