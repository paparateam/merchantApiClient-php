<?php

/**
 * Cash deposit service will be used for deposit operations for an end user.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\CashDepositByDateOptions;
use Papara\Options\CashDepositByReferenceOptions;
use Papara\Options\CashDepositCompleteOptions;
use Papara\Options\CashDepositControlOptions;
use Papara\Options\CashDepositGetOptions;
use Papara\Options\CashDepositSettlementOptions;
use Papara\Options\CashDepositTcknControlOptions;
use Papara\Options\CashDepositToAccountNumberOptions;
use Papara\Options\CashDepositToPhoneOptions;
use Papara\Options\CashDepositToTcknOptions;
use Papara\PaparaResult;
use Papara\PaparaService;


/**
 * CashDeposit Service Class 
 * @see PaparaService
 */
class CashDepositService extends PaparaService
{

  /**
   * Initializes a new instance of the AccountService class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/cashdeposit";
  }

  /**
   * Returns a cash deposit information.
   *
   * @param CashDepositGetOptions $options
   * @return PaparaResult
   */
  public function getCashDeposit($options)
  {
    $result = $this->GetResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request using end users's phone number.
   *
   * @param CashDepositToPhoneOptions $options
   * @return PaparaResult
   */
  public function createWithPhoneNumber($options)
  {
    $result = $this->PostResult("/", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request using end user's account number.
   *
   * @param CashDepositToAccountNumberOptions $options
   * @return PaparaResult
   */
  public function createWithAccountNumber($options)
  {
    $result = $this->PostResult("/accountnumber", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request using end users's national identity number.
   *
   * @param CashDepositToTcknOptions $options
   * @return PaparaResult
   */
  public function createWithTckn($options)
  {
    $result = $this->PostResult("/tckn", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request without upfront payment using end user's national identity number.
   *
   * @param CashDepositTcknControlOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithTcknControl($options)
  {
    $result = $this->PostResult("/provision/withtckncontrol", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request without upfront payment using end users's phone number.
   *
   * @param CashDepositToPhoneOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithPhoneNumber($options)
  {
    $result = $this->PostResult("/provision/phonenumber", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request without upfront payment using user's account number.
   *
   * @param CashDepositToAccountNumberOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithAccountNumber($options)
  {
    $result = $this->PostResult("/provision/accountnumber", $options);
    return new PaparaResult($result);
  }

  /**
   * Creates a cash deposit request provision using end user's national identity(TCKN) number
   *
   * @param CashDepositToTcknOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithTckn($options)
  {
    $result = $this->PostResult("/provision/tckn", $options);
    return new PaparaResult($result);
  }

  /**
   * Makes a cash deposit ready to be complete
   *
   * @param CashDepositControlOptions $options
   * @return PaparaResult
   */
  public function controlProvisionByReference($options)
  {
    $result = $this->PostResult("/provisionbyreference/control", $options);
    return new PaparaResult($result);
  }

  /**
   * Completes a cash deposit request without upfront payment.
   *
   * @param CashDepositControlOptions $options
   * @return PaparaResult
   */
  public function completeProvisionByReference($options)
  {
    $result = $this->PostResult("/provisionbyreference/complete", $options);
    return new PaparaResult($result);
  }

  /**
   * Completes a cash deposit request without upfront payment.
   *
   * @param CashDepositControlOptions $options
   * @return PaparaResult
   */
  public function completeProvision($options)
  {
    $result = $this->PostResult("/provision/complete", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns a cash deposit information by given date.
   *
   * @param CashDepositByDateOptions $options
   * @return PaparaResult
   */
  public function getCashDepositByDate($options)
  {
    $result = $this->GetResult("/bydate", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns the total number and volume of transactions made within the given dates. Both start and end dates are included in the calculation.
   *
   * @param CashDepositSettlementOptions $options
   * @return PaparaResult
   */
  public function settlements($options)
  {
    $result = $this->PostResult("/settlement", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns the total number and volume of transactions made within the given dates. Both start and end dates are included in the calculation.
   *
   * @param CashDepositSettlementOptions $options
   * @return PaparaResult
   */
  public function provisionSettlements($options)
  {
    $result = $this->PostResult("/provision/settlement", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns a cash deposit object using merchant's unique reference number.
   *
   * @param CashDepositByReferenceOptions $options
   * @return PaparaResult
   */
  public function getByReference($options)
  {
    $result = $this->GetResult("/byreference", $options);
    return new PaparaResult($result);
  }
}
