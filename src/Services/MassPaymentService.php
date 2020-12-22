<?php

/**
 * Mass payment service will be used for getting mass payment info and sending payments to account number, mail address and phone number.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\MassPaymentByReferenceOptions;
use Papara\Options\MassPaymentGetOptions;
use Papara\Options\MassPaymentToEmailOptions;
use Papara\Options\MassPaymentToPaparaNumberOptions;
use Papara\Options\MassPaymentToPhoneNumberOptions;
use Papara\PaparaResult;
use Papara\PaparaService;

class MassPaymentService extends PaparaService
{
  /**
   * Initializes a new instance of the MassPaymentService class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/masspayment";
  }

  /**
   * Returns mass payment information for authorized merchant.
   *
   * @param MassPaymentGetOptions $options
   * @return PaparaResult
   */
  public function getMassPayment($options)
  {
    $result = $this->GetResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a mass payment to given account number for authorized merchant.
   *
   * @param MassPaymentToPaparaNumberOptions $options
   * @return PaparaResult
   */
  public function createMassPaymentWithAccountNumber($options)
  {
    $result = $this->PostResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a mass payment to given e-mail address for authorized merchant.
   *
   * @param MassPaymentToEmailOptions $options
   * @return void
   */
  public function createMassPaymentWithEmail($options)
  {
    $result = $this->PostResult("/email", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a mass payment to given phone number for authorized merchant.
   *
   * @param MassPaymentToPhoneNumberOptions $options
   * @return void
   */
  public function createMassPaymentWithPhoneNumber($options)
  {
    $result = $this->PostResult("/phone", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns mass payment using mass payment reference number.
   *
   * @param MassPaymentGetOptions $options
   * @return PaparaResult
   */
  public function getMassPaymentByReference($options)
  {
    $result = $this->GetResult("/byreference", $options);
    return new PaparaResult($result);
  }
}
