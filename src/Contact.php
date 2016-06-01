<?php

namespace SendGrid;

class Contact implements \JsonSerializable
{
	private $email;
	private $contactId;

	public function __constrct($email, $contactId)
	{
		$this->email = $email;
		$this->id = $contactId;
	}

	/**
	 * Get the value of Email
	 * @return mixed
	 */
	public function getEmail()
	{
	    return $this->email;
	}

	/**
	 * Set the value of Email
	 * @param mixed email
	 * @return self
	 */
	public function setEmail($email)
	{
	    $this->email = $email;
	    return $this;
	}

	/**
	 * Get the value of Contact Id
	 * @return mixed
	 */
	public function getContactId()
	{
	    return $this->contactId;
	}

	/**
	 * Set the value of Contact Id
	 * @param mixed contactId
	 * @return self
	 */
	public function setContactId($contactId)
	{
	    $this->contactId = $contactId;
	    return $this;
	}
}
