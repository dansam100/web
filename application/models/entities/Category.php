<?php
    //entities/Category.php
    /**
	 * @Entity @Table(name="category")
	 */
    class Category extends Entity
    {
		/** @Id @Column(type="integer") @GeneratedValue */
    	protected $id;
		/**
	     * @var Category[]
		 * 
	     * @ManyToOne(targetEntity="Category", inversedBy="categories")
		 * @JoinColumn(name="userId", referencedColumnName="id")
	     */
		protected $user;
        /**
	     * @var Profile[]
		 * 
	     * @ManyToMany(targetEntity="Profile", inversedBy="categories", cascade={"persist"})
		 * @JoinTable(name="ProfileCategory",
		 *      joinColumns={@JoinColumn(name="categoryId", referencedColumnName="id")},
     	 *      inverseJoinColumns={@JoinColumn(name="profileId", referencedColumnName="id")}
 		 * )
	     */
        protected $profiles;
        /** @Column(type="string") */
		protected $name;
		
		public function name($name = null)
		{
			if(isset($name))
            {
                $this->name = $name;
            }
            return $this->name;
		}
        
        public function user($user = null)
		{
			if(isset($user) && $this->user !== $user)
            {
                $this->user = $user;
                $user->categories($this);
            }
            return $this->user;
		}
        
        public function profiles($profile = null)
        {
            if(isset($profile))
            {
                $this->profiles[] = $profile;
            }
            return $this->profiles;
        }
    }