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
		protected $userSalt;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $lastName;
		/** @Column(type="string") */
		protected $email;
		/** @Column(type="boolean", name="admin") */
		protected $isAdmin;
		/** @Column(type="boolean", name="verified") */
		protected $isVerified;
		/** @Column(type="boolean", name="active") */
		protected $isActive;
		/** @Column(type="datetime") */
		protected $editTime;
		
		/**
		 * @var Profile[]
		 *
	     * @OneToMany(targetEntity="Profile", mappedBy="user")
		 * 
	     */
		protected $profiles = null;
		/**
		 * @var Category[]
		 *
	     * @OneToMany(targetEntity="Category", mappedBy="user")
		 * 
	     */
		protected $categories = null;
		/**
	     * @var UserAddress[]
		 * 
		 * @OneToMany(targetEntity="UserAddress", mappedBy="user")
		 * 
	     */
		
		/**
	     * @var UserAddress[]
		 * 
		 * @OneToMany(targetEntity="UserAddress", mappedBy="user")
		 * 
	     */
		protected $addresses = null;
		/**
	     * @var Experience[]
		 * 
		 * @OneToMany(targetEntity="Profile", mappedBy="users")
		 * 
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
	     * @OneToMany(targetEntity="Session", mappedBy="user")
		 * @var Session[]
	     */
		protected $sessions = null;
		
		
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
			$this->sessions = new ArrayCollection();
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
		
		public function getUserSalt()
		{
			return $this->userSalt;
		}
		
		public function setUserSalt($salt)
		{
			$this->userSalt = $salt;
		}
		
		public function addProfile($profile)
		{
			$this->profiles[] = $profile;
		}
		
		public function getSessions()
		{
			return $this->sessions;
		}
		
		public function setSession($session)
		{
			$this->sessions[] = $session;
		}
		
		public function isAdmin($isAdmin = null)
		{
			if(isset($isAdmin))
			{
				$this->isAdmin = (int)$isAdmin;
			}
			else{
				return ($this->isAdmin == 1);
			}
		}
		
		public function isVerified($isVerified = null)
		{
			if(isset($isVerified))
			{
				$this->isVerified = (int)$isVerified;
			}
			else{
				return ($this->isVerified == 1);
			}
		}
		
		
		public function isActive($isActive = null)
		{
			if(isset($isActive))
			{
				$this->isActive = (int)$isActive;
			}
			else{
				return ($this->isActive == 1);
			}
		}
    }