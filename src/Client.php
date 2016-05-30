<?php

namespace SendGrid;

class Client {

	protected $client;

	/**
	 * Store the API URL
	 * @var string
	 */
	private $apiUrl = 'https://api.sendgrid.com/';

	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client();
	}

	public function send(\SendGrid\Email $email)
	{
		$form = $email->jsonSerialize();

		// Pass over the API key
		if ($this->apiKey !== null) {
			$form['api_key'] = $this->apiKey;
		}

		// Make the POST request
		$client->request('POST', $this->apiUrl.'api/mail.send.json', [
			'form_params' => $form,
			'headers' => [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json'
			]
		]);

		// Anything non-200 is an error code
		if ($response->getStatusCode() != 200) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		return $response;
	}
}
