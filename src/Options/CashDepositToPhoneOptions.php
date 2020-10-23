<?php

/**
 * CashDepositToPhoneOptions is used by cash deposit service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class CashDepositToPhoneOptions
{
  /**
   * Gets or sets phone number. The mobile phone number registered in the Papara account of the user to be loaded with cash.
   *
   * @var string $phoneNumber
   */
  public $phoneNumber;

  /**
   * Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number.
   *
   * @var float $amount
   */
  public $amount;

  /**
   * Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions.
   * If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail.
   * MerchantReference sent with failed requests can be resubmitted.
   *
   * @var string $merchantReference
   */
  public $merchantReference;
}
