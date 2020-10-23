<?php

/**
 * CashDepositByDateOptions is used by cash deposit service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class CashDepositByDateOptions
{
  /**
   * Gets or sets start date of cash deposit.
   *
   * @var DateTime $startDate
   */
  public $startDate;

  /**
   * Gets or sets end date of cash deposit.
   *
   * @var DateTime $endDate
   */
  public $endDate;

  /**
   * Gets or sets page index. It is the index number of the page that is wanted to display from the pages calculated on the basis of the number of records (pageItemCount) desired to be displayed on a page. Note: the first page is always 1.
   *
   * @var int $pageIndex
   */
  public $pageIndex;

  /**
   * Gets or sets page item count. The number of records that are desired to be displayed on a page.
   *
   * @var int $pageItemCount
   */
  public $pageItemCount;
}
