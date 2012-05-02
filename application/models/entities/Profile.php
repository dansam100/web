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
		protected $lastName;
		/** @Column(type="string") */
		protected $status;
		
		/**
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
		 * @JoinColumn(name="defaultEmail", referencedColumnName="id")
	     */
		protected $defaultEmail;
		/**
	     * @ManyToOne(targetEntity="Media", inversedBy="profiles")
		 * @JoinColumn(name="defaultPhone", referencedColumnName="id")
	     */
		protected $defaultPhone;
		/**
	     * @ManyToOne(targetEntity="Address", inversedBy="profiles")
		 * @JoinColumn(name="defaultAddress", referencedColumnName="id")
	     */
		protected $defaultAddress;
		/**
		 * @var User 
		 *
	     * @ManyToOne(targetEntity="User", inversedBy="profiles")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
		/** @Column(type="datetime") */
		protected $editTime;
		/**
		 * @var Address[]
		 * 
		 * @ManyToMany(targetEntity="UserAddress", inversedBy="addresses")
	     * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="locationId", referencedColumnName="id")}
 		 * )
	     */
		protected $addresses = null;
		/**
	     * @var Experience[]
		 * 
		 * @ManyToMany(targetEntity="Experience", inversedBy="profiles")
		 * @JoinTable(name="ProfileExperience",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="experienceId", referencedColumnName="id")}
 		 * )
	     */
		protected $experiences = null;
		/**
	     * @var Degree[]
		 * 
		 * @ManyToMany(targetEntity="Degree", inversedBy="profiles")
		 * @JoinTable(name="ProfileDegree",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="degreeId", referencedColumnName="id")}
 		 * )
	     */
		protected $degrees = null;
		/**
	     * @var Media[]
		 * 
		 * @ManyToMany(targetEntity="Media", inversedBy="profiles")
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="locationId", referencedColumnName="id")}
 		 * )
	     */
		protected $media = null;
		/**
	     * @var Skill[]
		 * 
		 * @ManyToMany(targetEntity="Skill", inversedBy="profiles")
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="skillId", referencedColumnName="id")}
 		 * )
	     */
		protected $skills = null;
		/**
	     * @var Language[]
		 * 
		 * @ManyToMany(targetEntity="Language", inversedBy="profiles")
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="languageId", referencedColumnName="id")}
 		 * )
	     */
		protected $languages = null;
		/**
		 *	@var Language[] 
		 *
	     * @ManyToMany(targetEntity="Activity", inversedBy="profiles")
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="activityId", referencedColumnName="id")}
 		 * )
	     */
		protected $activities = null;
		/**
		 * @var Category[]
		 * 
	     * @ManyToMany(targetEntity="Category", inversedBy="categories")
		 * @JoinTable(name="ProfileAddress",
		 *      joinColumns={@JoinColumn(name="profileId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="categoryId", referencedColumnName="id")}
 		 * )
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