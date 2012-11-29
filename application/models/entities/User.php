<?php
	//User.php
	use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
	
	/**
	 * @Entity @Table(name="user")
	 */
    class User extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $username;
		/** @Column(type="string") */
		protected $password;
		/** @Column(type="string") */
		protected $oauthToken;
        /** @Column(type="string") */
        protected $oauthSecret;
		/** @Column(type="string") */
        protected $memberId;
        /** @Column(type="string", name="verificationCode") */
        protected $verification;
        /** @Column(type="string") */
		protected $userSalt;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $lastName;
		/** @Column(type="string", name="email") */
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
         * @var Media
         * 
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
		 * @JoinColumn(name="defaultEmail", referencedColumnName="id")
	     */
        protected $defaultEmail;
        /**
         * @var Media
         * 
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
		 * @JoinColumn(name="defaultPhone", referencedColumnName="id")
	     */
        protected $defaultPhone;
        /**
         * @var UserAddress
         * 
	     * @ManyToOne(targetEntity="UserAddress", inversedBy="profiles")
		 * @JoinColumn(name="defaultAddressId", referencedColumnName="id")
	     */
        protected $defaultAddress;
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
		protected $addresses = null;
		/**
	     * @var Experience[]
		 * 
		 * @OneToMany(targetEntity="Experience", mappedBy="user")
		 * 
	     */
		protected $experiences = null;
		/**
	     * @OneToMany(targetEntity="Degree", mappedBy="user")
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
			$this->addresses = new ArrayCollection();
			$this->experiences = new ArrayCollection();
			$this->degrees = new ArrayCollection();
			$this->media = new ArrayCollection();
			$this->skills = new ArrayCollection();
			$this->languages = new ArrayCollection();
			$this->activities = new ArrayCollection();
			$this->categories = new ArrayCollection();
			$this->sessions = new ArrayCollection();
		}

        public function getId()
		{
			return $this->id;
		}
		
		public function userName($username = null)
		{
			if(isset($username))
            {
                $this->username = $username;
            }
            return $this->username;
        }
        
        public function firstName($firstName = null)
		{
			if(isset($firstName))
            {
                $this->firstName = $firstName;
            }
            return $this->firstName;
        }
        
        public function lastName($lastName = null)
		{
			if(isset($lastName))
            {
                $this->lastName = $lastName;
            }
            return $this->lastName;
        }
        
        public function email($email = null)
		{
			if(isset($email))
            {
                $this->email = $email;
            }
            return $this->email;
		}
		
		public function password($password = null)
		{
			if(isset($password))
            {
                $this->password = $password;
            }
            return $this->password;
		}
		
		public function userSalt($userSalt = null)
		{
			if(isset($userSalt))
            {
                $this->userSalt = $userSalt;
            }
            return $this->userSalt;
		}
        
        public function verificationCode($verification = null)
		{
			if(isset($verification))
            {
                $this->verification = $verification;
            }
            return $this->verification;
		}
        
        public function memberId($memberId = null)
        {
            if(isset($memberId))
            {
                $this->memberId = $memberId;
            }
            return $this->memberId;
        }
        
		public function oauthToken($oauthToken = null)
        {
            if(isset($oauthToken))
            {
                $this->oauthToken = $oauthToken;
            }
            return $this->oauthToken;
        }
        
        public function oauthSecret($oauthSecret = null)
        {
            if(isset($oauthSecret))
            {
                $this->oauthSecret = $oauthSecret;
            }
            return $this->oauthSecret;
        }
		
		public function profiles($profile = null)
        {
            if(isset($profile))
            {
                $this->profiles[] = $profile;
            }
            return $this->profiles;
        }
		
		public function sessions($session = null)
        {
            if(isset($session))
            {
                $this->sessions[] = $session;
            }
            return $this->sessions;
        }
		
		public function isAdmin($isAdmin = null)
		{
			if(isset($isAdmin))
			{
				$this->isAdmin = (int)$isAdmin;
			}
            return ($this->isAdmin == 1);
		}
		
		public function isVerified($isVerified = null)
		{
			if(isset($isVerified))
			{
				$this->isVerified = (int)$isVerified;
			}
            return ($this->isVerified == 1);
		}
		
		
		public function isActive($isActive = null)
		{
			if(isset($isActive))
			{
				$this->isActive = (int)$isActive;
			}
            return ($this->isActive == 1);
		}
    }