<?php

/**
 * Account service will be used for obtaining account information, settlements and ledgers.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\LedgerListOptions;
use Papara\Options\SettlementGetOptions;
use Papara\PaparaResult;
use Papara\PaparaService;

/**
 * Account Service Class 
 * @see PaparaService
 */
class AccountService extends PaparaService
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
    $this->basePath = "/account";
  }

  /**
   * Returns account information and current balance for authorized merchant.
   *
   * @return PaparaResult
   */
  public function GetAccount()
  {
    $result = $this->GetResult("/", null);
    return new PaparaResult($result);
  }

  /**
   * Returns list of ledgers for authorized merchant.
   *
   * @param LedgerListOptions $options
   * @return PaparaResult
   */
  public function ListLedgers(LedgerListOptions $options)
  {
    $result = $this->PostResult("/ledgers", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns settlement for authorized merchant.
   * 
   * @param SettlementGetOptions $options
   * @return PaparaResult
   */
  public function GetSettlement(SettlementGetOptions $options)
  {
    $result = $this->PostResult("/settlement", $options);
    return new PaparaResult($result);
  }
}
