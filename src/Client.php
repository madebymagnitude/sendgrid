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
		$this->client = new \GuzzleHttp\Client();
		$this->apiKey = $apiKey;
	}

	public function send(\SendGrid\Email $email)
	{
		$form = $email->jsonSerialize();

		// Make the POST request
		$response = $this->client->request('POST', $this->apiUrl.'mail/send/beta', [
			'json' => $form,
			'headers' => [
				'Accept' => '*/*',
				'Authorization' => 'Bearer '.$this->apiKey,
				'Content-Type' => 'application/json'
			]
		]);

		// Anything non-200 is an error code
		if ($response->getStatusCode() !== 202) {
			throw new \SendGrid\Exception($response->getBody(), $response->getStatusCode());
		}

		return $response;
	}
}
