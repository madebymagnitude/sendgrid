<?php

namespace SendGrid;

class Email implements \JsonSerializable
{
	protected $to;
	protected $toName;
	protected $from;
	protected $fromName;
	protected $replyTo;
	protected $cc;
	protected $ccName;
	protected $bcc;
	protected $bccName;
	protected $subject;
	protected $text;
	protected $html;
	protected $date;
	protected $headers;
    protected $attachments;
	protected $filters = [];
	protected $section = [];
	protected $category = [];
	protected $uniqueArguments = [];
	protected $substitutions = [];

    public function __construct()
    {
        $this->fromName = false;
        $this->replyTo = false;
    }

    public function addTo($email, $name = null)
    {
        if ($this->to == null) {
            $this->to = [];
        }

        if (is_array($email)) {
            foreach ($email as $e) {
                $this->to[] = $e;
            }
        } else {
            $this->to[] = $email;
        }

        if (is_array($name)) {
            foreach ($name as $n) {
                $this->addToName($n);
            }
        } elseif ($name) {
            $this->addToName($name);
        }

        return $this;
    }

    public function setTos(array $emails)
    {
        $this->to = $emails;
        return $this;
    }

    public function addToName($name)
    {
        if ($this->toName == null) {
            $this->toName = [];
        }

        $this->toName[] = $name;
        return $this;
    }

    public function getToNames()
    {
        return $this->toName;
    }

    public function setFrom($email)
    {
        $this->from = $email;
        return $this;
    }

    public function getFrom($asArray = false)
    {
        if ($asArray && ($name = $this->getFromName())) {
            return array($this->from => $name);
        } else {
            return $this->from;
        }
    }

    public function setFromName($name)
    {
        $this->fromName = $name;
        return $this;
    }

    public function getFromName()
    {
        return $this->fromName;
    }

    public function setReplyTo($email)
    {
        $this->replyTo = $email;
        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setCc($email)
    {
        $this->cc = array($email);
        return $this;
    }

    public function setCcs(array $ccList)
    {
        $this->cc = $ccList;
        return $this;
    }

    public function addCc($email, $name = null)
    {
        if ($this->cc == null) {
            $this->cc = [];
        }

        if (is_array($email)) {
            foreach ($email as $e) {
                $this->cc[] = $e;
            }
        } else {
            $this->cc[] = $email;
        }

        if (is_array($name)) {
            foreach ($name as $n) {
                $this->addCcName($n);
            }
        } elseif ($name) {
            $this->addCcName($name);
        }

        return $this;
    }

    public function addCcName($name)
    {
        if ($this->ccName == null) {
            $this->ccName = [];
        }

        $this->ccName[] = $name;
        return $this;
    }

    public function getCcs()
    {
        return $this->cc;
    }

    public function getCcNames()
    {
        return $this->ccName;
    }

    public function setBcc($email)
    {
        $this->bcc = array($email);
        return $this;
    }

    public function setBccs($bccList)
    {
        $this->bcc = $bccList;
        return $this;
    }

    public function addBcc($email, $name = null)
    {
        if ($this->bcc == null) {
            $this->bcc = [];
        }

        if (is_array($email)) {
            foreach ($email as $e) {
                $this->bcc[] = $e;
            }
        } else {
            $this->bcc[] = $email;
        }

        if (is_array($name)) {
            foreach ($name as $n) {
                $this->addBccName($n);
            }
        } elseif ($name) {
            $this->addBccName($name);
        }

        return $this;
    }

    public function addBccName($name)
    {
        if ($this->bccName == null) {
            $this->bccName = [];
        }

        $this->bccName[] = $name;
        return $this;
    }

    public function getBccNames()
    {
        return $this->bccName;
    }

    public function getBccs()
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
        return $this->text;
    }

    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Convenience method to add template
     *
     * @param string The ID of the template
     * @return $this
     */
    public function setTemplateId($templateId)
    {
        $this->addFilter('templates', 'enabled', 1);
        $this->addFilter('templates', 'template_id', $templateId);

        return $this;
    }

    public function getHeadersJson()
    {
        if (count($this->getHeaders()) <= 0) {
            return "{}";
        }

        return json_encode($this->getHeaders(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

	public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
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
    public function addSubstitution($fromValue, array $toValues)
    {
        $this->substitutions[$fromValue] = $toValues;
        return $this;
    }

    /**
     * @param array $key_value_pairs
     * @return $this
     */
    public function setSubstitutions(array $substitutions)
    {
        $this->substitutions = $substitutions;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function addUniqueArg($key, $value)
    {
        $this->uniqueArguments[$key] = $value;
        return $this;
    }

    /**
     * @param array $key_value_pairs
     * @return $this
     */
    public function setUniqueArgs(array $key_value_pairs)
    {
        $this->uniqueArguments = $key_value_pairs;
        return $this;
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
     * @param string $from_value
     * @param string $to_value
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
        $web = [
			'to' => $this->to,
			'subject' => $this->getSubject(),
            'from' => $this->getFrom(),
			'html' => $this->getHtml(),
			'text' => $this->getText(),
            'headers' => $this->getHeadersJson()
        ];

		if ($this->getFilters()) {
			$web['x-smtpapi'] = json_encode([
				'filters' => $this->getFilters()
			]);
		}
        if ($this->getToNames()) {
            $web['toname'] = $this->getToNames();
        }
        if ($this->getCcs()) {
            $web['cc'] = $this->getCcs();
        }
        if ($this->getCcNames()) {
            $web['ccname'] = $this->getCcNames();
        }
        if ($this->getBccs()) {
            $web['bcc'] = $this->getBccs();
        }
        if ($this->getBccNames()) {
            $web['bccname'] = $this->getBccNames();
        }
        if ($this->getFromName()) {
            $web['fromname'] = $this->getFromName();
        }
        if ($this->getReplyTo()) {
            $web['replyto'] = $this->getReplyTo();
        }
        if ($this->getDate()) {
            $web['date'] = $this->getDate();
        }

		return $web;
    }
}
