<?php
    //entities/Profile.php
    use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
    
	/**
	 * @Entity @Table(name="profile")
	 */
    class Profile extends Entity
    {
    	/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/** @Column(type="string") */
		protected $objective;
		/** @Column(type="string") */
		protected $firstName;
		/** @Column(type="string") */
		protected $lastName;
		/** @Column(type="string") */
		protected $status;
		/**
		 * @var User 
		 *
	     * @ManyToOne(targetEntity="User", inversedBy="profiles", cascade={"persist"})
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		/**
		 * @var Address[]
		 * 
		 * @ManyToMany(targetEntity="UserAddress", inversedBy="addresses", cascade={"persist"})
	     * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="locationId", referencedColumnName="id")}
 		 * )
	     */
		protected $addresses = null;
		/**
	     * @var Experience[]
		 * 
		 * @ManyToMany(targetEntity="Experience", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileExperience",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="experienceId", referencedColumnName="id")}
 		 * )
	     */
		protected $experiences = null;
		/**
	     * @var Degree[]
		 * 
		 * @ManyToMany(targetEntity="Degree", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileDegree",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="degreeId", referencedColumnName="id")}
 		 * )
	     */
		protected $degrees = null;
		/**
	     * @var Media[]
		 * 
		 * @ManyToMany(targetEntity="Media", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileMedia",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="mediaId", referencedColumnName="id")}
 		 * )
	     */
		protected $media = null;
		/**
	     * @var Skill[]
		 * 
		 * @ManyToMany(targetEntity="Skill", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="skillId", referencedColumnName="id")}
 		 * )
	     */
		protected $skills = null;
		/**
	     * @var Language[]
		 * 
		 * @ManyToMany(targetEntity="Language", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileLanguage",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="languageId", referencedColumnName="id")}
 		 * )
	     */
		protected $languages = null;
		/**
		 *	@var Activity[] 
		 *
	     * @ManyToMany(targetEntity="Activity", inversedBy="profiles", cascade={"persist"})
		 * @JoinTable(name="ProfileActivity",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="activityId", referencedColumnName="id")}
 		 * )
	     */
		protected $activities = null;
		/**
		 * @var Category[]
		 * 
	     * @ManyToMany(targetEntity="Category", inversedBy="categories", cascade={"persist"})
		 * @JoinTable(name="ProfileCategory",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="categoryId", referencedColumnName="id")}
 		 * )
	     */
		protected $categories = null;
		
		
		public function __construct()
		{
			$this->addresses = new ArrayCollection();
			$this->experiences = new ArrayCollection();
			$this->degrees = new ArrayCollection();
			$this->media = new ArrayCollection();
			$this->skills = new ArrayCollection();
			$this->languages = new ArrayCollection();
			$this->activities = new ArrayCollection();
			$this->categories = new ArrayCollection();
		}
        
        public function getId()
		{
			return $this->id;
		}
		
		public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
            {
                $this->user = $user;
            }
            return $this->user;
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
        
        public function categories($category = null)
        {
            if(isset($category))
            {
                $category->profiles($this);
                $this->categories[] = $category;
            }
            return $this->categories;
        }
        
        public function experiences($experience = null)
        {
            if(isset($experience))
            {
                $experience->profiles($this);
                $this->experiences[] = $experience;
            }
            return $this->experiences;
        }
        
        public function activities($activity = null)
        {
            if(isset($activity))
            {
                $activity->profiles($this);
                $this->activities[] = $activity;
            }
            return $this->activities;
        }
        
        public function degrees($degree = null)
        {
            if(isset($degree))
            {
                $degree->profiles($this);
                $this->degrees[] = $degree;
            }
            return $this->degrees;
        }
        
        public function skills($skill = null)
        {
            if(isset($skill))
            {
                $skill->profiles($this);
                $this->skills[] = $skill;
            }
            return $this->skills;
        }
        
        public function languages($language = null)
        {
            if(isset($language))
            {
                $language->profiles($this);
                $this->languages[] = $language;
            }
            return $this->languages;
        }
        
        public function media($media = null)
        {
            if(isset($media))
            {
                $media->profiles($this);
                $this->media[] = $media;
            }
            return $this->media;
        }
    }
    
