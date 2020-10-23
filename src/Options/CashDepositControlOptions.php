<?php

/**
 * CashDepositControlOptions is used by cash deposit service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class CashDepositControlOptions
{
  /**
   * Gets or sets reference number of cash deposit request.
   *
   * @var string $referenceCode
   */
  public $referenceCode;

  /**
   * Gets or sets cash deposit amount.
   *
   * @var float $amount
   */
  public $amount;
}
