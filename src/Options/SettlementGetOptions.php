<?php

/**
 * SettlementGetOptions is used by account service for providing settlement request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class SettlementGetOptions
{
  /**
   * Gets or sets start date filter for transactions.
   *
   * @var DateTime $startDate
   */
  public $startDate;

  /**
   * Gets or sets end date filter for transactions.
   *
   * @var DateTime $endDate
   */
  public $endDate;

  /**
   * Gets or sets entry types.
   * BankTransfer = 1
   * CorporateCardTransaction = 2,
   * LoadingMoneyFromPhysicalPoint = 6,
   * MerchantPayment = 8,
   * PaymentDistribution = 9,
   * EduPos = 11.
   *
   * @var int $entryType
   */
  public $entryType;
}
