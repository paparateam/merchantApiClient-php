<?php

/**
 * Payment service will be used for getting, creating or listing payments and refunding.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\PaymentGetByReferenceOptions;
use Papara\Options\PaymentGetOptions;
use Papara\Options\PaymentListOptions;
use Papara\Options\PaymentRefundOptions;
use Papara\PaparaResult;
use Papara\PaparaService;
use PaymentCreateOptions;

class PaymentService extends PaparaService
{
  /**
   * Initializes a new instance of the PaymentService class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/payments";
  }

  /**
   * Returns payment and balance information for authorized merchant.
   *
   * @param PaymentGetOptions $options
   * @return PaparaResult
   */
  public function getPayment($options)
  {
    $result = $this->GetResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a payment for authorized merchant.
   *
   * @param PaymentCreateOptions $options
   * @return PaparaResult
   */
  public function createPayment($options)
  {
    $result = $this->PostResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a refund for a completed payment for authorized merchant.
   *
   * @param PaymentRefundOptions $options
   * @return PaparaResult
   */
  public function refund($options)
  {
    $result = $this->PutResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns payment and balance information for authorized merchant by reference.
   *
   * @param PaymentGetOptions $options
   * @return PaparaResult
   */
  public function getPaymentByReference($options)
  {
    $result = $this->GetResult("/reference", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns a list of completed payments sorted by newest to oldest for authorized merchant.
   *
   * @param PaymentListOptions $options
   * @return PaparaResult
   */
  public function listPayments($options)
  {
    $result = $this->GetResult("/list", $options);
    return new PaparaResult($result);
  }
}
