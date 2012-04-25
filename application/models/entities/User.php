<?php
	//User.php
	use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
	
	/**
	 * @Entity @Table(name="user")
	 */
    class User
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $username;
		/** @Column(type="string") */
		protected $password;
		/** @Column(type="string") */
		protected $oAuthToken;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $lastName;
		/** @Column(type="string") */
		protected $email;
		/** @Column(type="boolean") */
		protected $isAdmin;
		/** @Column(type="boolean") */
		protected $isVerified;
		/** @Column(type="boolean") */
		protected $isActive;
		/** @Column(type="datetime") */
		protected $editTime;
		
		/**
	     * @OneToMany(targetEntity="Profile", mappedBy="user")
		 * @var Profile[]
	     */
		protected $profiles = null;
		/**
	     * @OneToMany(targetEntity="Address", mappedBy="user")
		 * @var Address[]
	     */
		protected $addresses = null;
		/**
	     * @OneToMany(targetEntity="Profile", mappedBy="users")
		 * @var Experience[]
	     */
		protected $experiences = null;
		/**
	     * @OneToMany(targetEntity="Profile", mappedBy="user")
		 * @var Degree[]
	     */
		protected $degrees = null;
		/**
	     * @OneToMany(targetEntity="Media", mappedBy="user")
		 * @var Media[]
	     */
		protected $media = null;
		/**
	     * @OneToMany(targetEntity="Skill", mappedBy="user")
		 * @var Skill[]
	     */
		protected $skills = null;
		/**
	     * @OneToMany(targetEntity="Language", mappedBy="user")
		 * @var Language[]
	     */
		protected $languages = null;
		/**
	     * @OneToMany(targetEntity="Activity", mappedBy="user")
		 * @var Activity[]
	     */
		protected $activities = null;
		/**
	     * @OneToMany(targetEntity="Category", mappedBy="user")
		 * @var Category[]
	     */
		protected $categories = null;
		
		
		public function __construct()
		{
			$this->profiles = new ArrayCollection();
			$this->$addresses = new ArrayCollection();
			$this->$experiences = new ArrayCollection();
			$this->$degrees = new ArrayCollection();
			$this->$media = new ArrayCollection();
			$this->$skills = new ArrayCollection();
			$this->$languages = new ArrayCollection();
			$this->$activities = new ArrayCollection();
			$this->$categories = new ArrayCollection();
		}
		
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getUserName()
		{
			return $this->username;
		}
		
		public function setUserName($username)
		{
			$this->username = $username;
		}
		
		public function getPassword()
		{
			return $this->password;
		}
		
		public function setPassword($password)
		{
			$this->password = $password;
		}
		
		public function addProfile($profile)
		{
			$this->profiles[] = $bugs;
		}
    }
?>