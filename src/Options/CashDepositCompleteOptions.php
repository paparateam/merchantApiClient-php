<?php

/**
 * CashDepositCompleteOptions is used by cash deposit service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class CashDepositCompleteOptions
{
  /**
   * Gets or sets ID of cash deposit request.
   *
   * @var int $id
   */
  public $id;

  /**
   * Gets or sets date of cash deposit transaction.
   *
   * @var DateTime $transactionDate
   */
  public $transactionDate;
}
