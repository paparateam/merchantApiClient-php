<?php

/**
 * BankingWithdrawalOptions is used by banking service for providing request parameters. 
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class BankingWithdrawalOptions
{
  /**
   * Gets or sets target bank account id which money will be transferred to when withdrawal is completed.It will be obtained as a result of the request to list bank accounts.
   *
   * @var int bankAccountId 
   */
  public $bankAccountId;

  /**
   * Gets or sets withdrawal amount.
   *
   * @var float $amount
   */
  public $amount;
}
