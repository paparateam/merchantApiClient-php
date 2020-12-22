<?php

/**
 * Papara Service class contains service methods to be used in service classes.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara;

use Papara\Client\PaparaServiceClient;

abstract class PaparaService
{
  /**
   * Base endpoint of a service class.
   *
   * @var string
   */
  protected $basePath;

  /**
   * Service client's instance.
   */
  private $client;

  /**
   * Request options' instance.
   */
  private $requestOptions;

  /**
   * Initializes a new instance of the PaparaService class.
   *
   * @param string $apiKey Merchant API Key.
   * @param boolean $isTest Select whether the environment will be test or production.
   */
  public function __construct($apiKey, $isTest)
  {
    $this->client = new PaparaServiceClient();
    $this->requestOptions = new RequestOptions($apiKey, $isTest);
  }

  /**
   * Get service result request from API.
   *
   * @param string $childPath is requested method from base service endpoint
   * @param $options is request options. Should be declared and used according to the service class.
   * @return void Returns service result from given path.
   */
  public function GetResult($childPath, $options = null)
  {
    return $this->client->Request("GET", $this->basePath . $childPath, $options, $this->requestOptions);
  }

  /**
   * Post a service request to API.
   *
   * @param string $childPath is requested method from base service endpoint
   * @param $options is request options. Should be declared and used according to the service class.
   * @return void Returns service result from given path.
   */
  public function PostResult($childPath, $options)
  {
    return $this->client->Request("POST", $this->basePath . $childPath, $options, $this->requestOptions);
  }

  /**
   * Put service request to API.
   *
   * @param string $childPath is requested method from base service endpoint
   * @param $options is request options. Should be declared and used according to the service class.
   * @return void Returns service result from given path
   */
  public function PutResult($childPath, $options)
  {
    return $this->client->Request("PUT", $this->basePath . $childPath, $options, $this->requestOptions);
  }
}
