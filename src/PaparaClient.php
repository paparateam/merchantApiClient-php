<?php

/**
 * Papara merchant service facade.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara;

use Papara\Services\AccountService;
use Papara\Services\BankingService;
use Papara\Services\CashDepositService;
use Papara\Services\MassPaymentService;
use Papara\Services\PaymentService;
use Papara\Services\ValidationService;

class PaparaClient
{
  public AccountService $AccountService;
  public BankingService $BankingService;
  public CashDepositService $CashDepositService;
  public MassPaymentService $MassPaymentService;
  public PaymentService $PaymentService;
  public ValidationService $ValidationService;


  /**
   * Constructs service classes according to given API and environment variables.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production.
   */
  function __construct($apiKey, $isTest = false)
  {
    $this->AccountService = new AccountService($apiKey, $isTest);
    $this->BankingService = new BankingService($apiKey, $isTest);
    $this->CashDepositService = new CashDepositService($apiKey, $isTest);
    $this->MassPaymentService = new MassPaymentService($apiKey, $isTest);
    $this->PaymentService = new PaymentService($apiKey, $isTest);
    $this->ValidationService = new ValidationService($apiKey, $isTest);
  }
}
