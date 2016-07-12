<?php

namespace SendGrid;

class Client {

	protected $client;

	/**
	 * Store the API URL
	 * @var string
	 */
	private $apiUrl = 'https://api.sendgrid.com/v3/';

	/**
	 * Store the API key
	 * @var string
	 */
	private $apiKey = '';

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
		$this->client = new \GuzzleHttp\Client([
			'headers' => [
				'Accept' => '*/*',
				'Authorization' => 'Bearer '.$this->apiKey,
				'Content-Type' => 'application/json'
			]
		]);
	}

	/**
	 * Create a new recipient
	 * @param string 	$email
	 * @param array  	$fields		Custom data to store against a contact
	 */
	public function addRecipient($email, array $fields = [])
	{
		$requestParameters = [];
		$requestParameters[] = array_merge([
			'email' => $email
		], $fields);

		$response = $this->client->request('POST', $this->apiUrl.'contactdb/recipients', [
			'json' => $requestParameters
		]);

		// We should get a 201
		if ($response->getStatusCode() !== 201) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		$json = json_decode($response->getBody(), true);

		return (new \SendGrid\Contact($email, reset($json['persisted_recipients'])));
	}

	/**
	 * Create multiple recipients by passing in their email addresses and custom data for each
	 * @param array  $emails Stores a list of emails to add
	 * @param array $fields An array of custom data values (for each recipient)
	 */
	public function addRecipients(array $emails, array $fields = [])
	{
		$requestParameters = [];
		$responseContacts = [];

		// Build the request object, we want to send over an array of field objects
		foreach ($emails as $index => $email) {

			// Setup the email value
			$requestParameters[$index] = [
				'email' => $email
			];

			if (!empty($fields[$index])) {
				$requestParameters[$index] = array_merge($fields[$index], $requestParameters[$index]);
			}
		}

		$response = $this->client->request('POST', $this->apiUrl.'contactdb/recipients', [
			'json' => $requestParameters
		]);

		// We should get a 201
		if ($response->getStatusCode() !== 201) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		$json = json_decode($response->getBody(), true);

		// Re-build the response list by going over all the emails
		foreach ($emails as $index => $email) {

			// If the email was invalid / malformed, check the error indices
			if (!empty($json['error_indices']) && in_array($json['error_indices'], $index)) {
				continue;
			}

			$responseContacts[] = new \SendGrid\Contact($email, $json['persisted_recipients'][$index]);
		}

		return $responseContacts;
	}

	/**
	 * Given a SendGrid contact object, add them to a sender list
	 * @param \SendGrid\Contact 	$contact
	 * @param mixed          $list    Contains either an integer or a \SendGrid\List object
	 */
	public function addContactToList(\SendGrid\Contact $contact, $list)
	{
		$listId = $list;

		// Do we have a list object?
		if (is_object($list) && (get_class($list) === 'SendGrid\List')) {
			$listId = $list->getId();
		}

		// We must have an integer at this point
		if (!is_numeric($listId)) {
			throw new \SendGrid\Exception\InvalidList;
		}

		$response = $this->client->request('POST', $this->apiUrl.'contactdb/lists/'.$listId.'/recipients/'.$contactId);

		// Anything non-200 is an error code
		if ($response->getStatusCode() !== 201) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		return true;
	}

	/**
	 * Send an email object out to one or more recipients
	 * @param  \SendGrid\Email 	$email
	 * @return \GuzzleHttp\Psr7\Response
	 */
	public function send(\SendGrid\Email $email)
	{
		$form = $email->jsonSerialize();

		// Make the POST request
		$response = $this->client->request('POST', $this->apiUrl.'mail/send', [
			'json' => $form
		]);

		// Anything non-200 is an error code
		if ($response->getStatusCode() !== 202) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		return $response;
	}
}
