<?php

/**
 * PaymentListOptions is used by payment service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class PaymentListOptions
{
  /**
   * Gets or sets page index. It is the index number of the page that is wanted to display from the pages calculated on the basis of the number of records (pageItemCount) desired to be displayed on a page. Note: the first page is always 1.
   *
   * @var int $pageItemCount
   */
  public $pageIndex;

  /**
   * Gets or sets page item count. The number of records that are desired to be displayed on a page.
   *
   * @var int $pageItemCount
   */
  public $pageItemCount;
}
