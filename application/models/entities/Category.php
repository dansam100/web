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
		/** @Column(type="string") */
		protected $name;
		
		public function getName()
		{
			return $this->name;
		}
    }