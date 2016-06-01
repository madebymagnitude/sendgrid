<?php

namespace SendGrid;

class List implements \JsonSerializable
{
	private $id;

	public function __constrct($listId)
	{
		$this->id = $listId;
	}

	/**
	 * Get the value of Id
	 * @return mixed
	 */
	public function getId()
	{
	    return $this->id;
	}
	
	/**
	 * Set the value of Id
	 * @param mixed id
	 * @return self
	 */
	public function setId($id)
	{
	    $this->id = $id;
	    return $this;
	}
}
