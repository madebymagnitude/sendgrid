<?php

namespace SendGrid;

class Email implements \JsonSerializable
{
	private $to = [];
	private $from = [];
	private $replyTo = [];
	private $cc = [];
	private $bcc = [];
	private $subject;
	private $text;
	private $html;
	private $date;
	private $headers = [];
    private $attachments = [];
	private $filters = [];
	private $section = [];
	private $category = [];
	private $uniqueArguments = [];
	private $substitutions = [];
	private $templateId = null;

    public function __construct()
    {
        $this->replyTo = false;
    }

    public function addTo($email, $name = null)
    {
        $newTo = [
			'email' => $email
		];

		// Did they give us a name, include that as well
		if (!empty($name)) {
			$newTo['name'] = $name;
		}

		// Set the to object
		$this->to[] = $newTo;
        return $this;
    }

	public function getTo()
	{
		return $this->to;
	}

    public function setFrom($email, $name = null)
    {
        $this->from = [
			'email' => $email,
			'name' => $name
		];
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setReplyTo($email)
    {
        $this->replyTo = [
			'email' => $email
		];
        return $this;
    }

    public function getReplyTo()
    {
        return (!empty($this->replyTo) ? $this->replyTo : $this->from);
    }

    public function addCc($email, $name = null)
    {
        $this->cc[] = [
			'email' => $email,
			'name' => $name
		];
        return $this;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function addBcc($email, $name = null)
    {
		$this->bcc[] = [
			'email' => $email,
			'name' => $name
		];
        return $this;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getText()
    {
        return (empty($this->text) ? '&nbsp;' : $this->text);
    }

    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    public function getHtml()
    {
        return (empty($this->html) ? '&nbsp;' : $this->html);
    }

    /**
     * Convenience method to add template
     *
     * @param string The ID of the template
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }

	public function getTemplateId()
    {
        return $this->templateId;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

	public function getHeaders()
    {
        return $this->headers;
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

	/**
     * @param string $filterName
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addFilter($filterName, $name, $value)
    {
        $this->filters[$filterName]['settings'][$name] = $value;
        return $this;
    }

	/**
     * @param array $filters
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return array filters
     */
    public function getFilters()
    {
        return $this->filters;
    }

	/**
     * @param string $from_value
     * @param array $to_values
     * @return $this
     */
    public function addSubstitution($key, $name)
    {
        $this->substitutions[$key] = (string) $name;
        return $this;
    }

    /**
     * @param array $key_value_pairs
     * @return $this
     */
    public function setSubstitutions(array $substitutions)
    {
        foreach ($substitutions as $key => $value) {
        	$this->addSubstitution($key, $value);
        }
        return $this;
    }

	/**
     * @return array
     */
    public function getSubstitutions()
    {
        return $this->substitutions;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function addUniqueArgument($key, $value)
    {
        $this->uniqueArguments[$key] = (string) $value;
        return $this;
    }

    /**
     * @param array $key_value_pairs
     * @return $this
     */
    public function setUniqueArguments(array $arguments)
    {
        $this->uniqueArguments = $arguments;
        return $this;
    }

	/**
     * @return array
     */
    public function getUniqueArguments()
    {
        return $this->uniqueArguments;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function addCategory($category)
    {
        $this->category[] = $category;
        return $this;
    }

    /**
     * @param array $categories
     * @return $this
     */
    public function setCategories(array $categories)
    {
        $this->category = $categories;
        return $this;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = array($category);
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addSection($name, $value)
    {
        $this->section[$name] = $value;
        return $this;
    }

    /**
     * @param array $key_value_pairs
     * @return $this
     */
    public function setSections(array $sections)
    {
        $this->section = $sections;
        return $this;
    }

	/**
	 * Convert the data into a JSON acceptable output
	 * @return array
	 */
    public function jsonSerialize()
    {
        $form = [
            'from' => $this->getFrom(),
			'content' => [
				[
					'type' => 'text/plain',
					'value' => $this->getText(),
				],
				[
					'type' => 'text/html',
					'value' => $this->getHtml()
				]
			],
			'date' => $this->getDate(),
			'template_id' => $this->getTemplateId(),
			'reply_to' => $this->getReplyTo(),
			'custom_args' => $this->getUniqueArguments(),
			'filters' => $this->getFilters(),
			'headers' => $this->getHeaders()
        ];

		$indexCounter = 0;

		// Add them as an individual array
		foreach ($this->getTo() as $to) {

			// Add a user object
			$form['personalizations'][$indexCounter] = [
				'to' => $this->getTo(),
				'subject' => $this->getSubject(),
			];

			if (!empty($this->getSubstitutions())) {
				$form['personalizations'][$indexCounter]['substitutions'] = $this->getSubstitutions();
			}

			if (!empty($this->getCc())) {
				$form['personalizations'][$indexCounter]['cc'] = $this->getCc();
			}

			if (!empty($this->getBcc())) {
				$form['personalizations'][$indexCounter]['bcc'] = $this->getBcc();
			}

			// Increment the tally
			$indexCounter++;
		}

		return array_filter($form);
    }
}
