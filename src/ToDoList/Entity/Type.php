<?php

	namespace ToDoList\Entity;

	use Doctrine\ORM\Mapping as ORM;

	use \DateTime;

	class Type {
		private $id;

		private $name;

		private $actions;


		/**
	     * Get id
	     *
	     * @return integer 
	     */
	    public function getId()
	    {
	        return $this->id;
	    }

	    /**
	     * Set name
	     *
	     * @param string $name
	     * @return Type
	     */
	    public function setName($name)
	    {
	        $this->name = $name;

	        return $this;
	    }

	    /**
	     * Get name
	     *
	     * @return string 
	     */
	    public function getName()
	    {
	        return $this->name;
	    }

	    	    /**
	     * Set title
	     *
	     * @param array $actions
	     * @return Type
	     */
	    public function setActions(array $actions)
	    {

	        $this->actions = implode(',',$actions);

	        return $this;
	    }

	    /**
	     * Get title
	     *
	     * @return string 
	     */
	    public function getActions()
	    {
	        return $this->actions;
	    }

	}