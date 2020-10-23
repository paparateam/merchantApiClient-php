<?php

/**
 * LedgerListOptions is used by account service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class LedgerListOptions
{
  /**
   * Gets or sets start date for transactions.
   *
   * @var DateTime $startDate
   */
  public $startDate;

  /**
   * Gets or sets end date for transactions.
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

  /**
   * Gets or sets merchant account number.
   *
   * @var int $accountNumber
   */
  public $accountNumber;

  /**
   * Gets or sets the requested page number. If the requested date has more than 1 page of results for the requested PageSize, use this to iterate through pages.
   *
   * @var int $page
   */
  public $page;

  /**
   * Gets or sets number of elements you want to receive per request page. Min=1, Max=50.
   *
   * @var int $pageSize
   */
  public $pageSize;
}
