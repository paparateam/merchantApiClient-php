<?php

/**
 * CashDepositSettlementOptions is used by cash deposit service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class CashDepositSettlementOptions
{
  /**
   * Gets or sets start date for settlement.
   *
   * @var DateTime $startDate
   */
  public $startDate;

  /**
   * Gets or sets end date for settlement.  
   *
   * @var DateTime $endDate
   */
  public $endDate;

  /**
   * Gets or sets entry type for settlement.
   *  Entry type. 1: Bank Transfer(Deposits/Withdrawals) 6: Cash Deposit 8: Received Payment(Checkout) 9: Sent Payment (MassPayment) = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17'].
   *
   * @var int $entryType
   */
  public $entryType;
}
