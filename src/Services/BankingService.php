<?php

/**
 * Banking service will be used for listing merchant's bank accounts and making withdrawal operations. 
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\BankingWithdrawalOptions;
use Papara\PaparaResult;
use Papara\PaparaService;

/**
 * Banking Service Class 
 * @see PaparaService
 */
class BankingService extends PaparaService
{
  /**
   * Initializes a new instance of the BankingService class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/banking";
  }

  /**
   * Returns bank accounts for authorized merchant.
   *
   * @return PaparaResult
   */
  public function GetBankAccounts()
  {
    $result = $this->GetResult("/bankaccounts");
    return new PaparaResult($result);
  }

  /**
   * Creates a withdrawal request from given bank account for authorized merchant.
   * 
   * Error codes:
   * 105 - Insufficient funds.
   * 115 - Requested amount is lower then minimum limit.
   * 120 - Bank account not found.
   * 247 - Merchant's account is not active.
   *
   * @param BankingWithdrawalOptions $options
   * @return PaparaResult
   */
  public function Withdrawal($options)
  {
    $result = $this->PostResult("/withdrawal", $options);
    return new PaparaResult($result);
  }
}
