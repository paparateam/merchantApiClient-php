<?php

/**
 * RequestOptions class contains request options to be used in service classes.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara;

class RequestOptions
{
  /**
   * @var string $apiKey Merchant API Key
   *
   */
  public $apiKey;

  /**
   *  @var boolean $isTest Select whether the environment will be test or production
   *
   */
  public $isTest;

  /**
   * Initializes a new instance of the RequestOptions class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey, $isTest)
  {
    $this->apiKey = $apiKey;
    $this->isTest = $isTest;
  }
}
