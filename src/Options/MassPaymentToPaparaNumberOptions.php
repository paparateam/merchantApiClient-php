<?php

/**
 * MassPaymentToPaparaNumberOptions is used by mass payment service for providing request parameters.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Options;

class MassPaymentToPaparaNumberOptions
{
  /**
   * Gets or sets Papara account number. The 10-digit Papara number of the user who will receive the payment. It can be in the format 1234567890 or PL1234567890. Before the Papara version transition, the Papara number was called the wallet number.Old wallet numbers have been changed to Papara number. Payment can be distributed to old wallet numbers.
   *
   * @var strimg
   */
  public $accountNumber;

  /**
   * Gets or sets parse account number. Parses the account number to long type. In old papara integrations, account / wallet number was made by starting with PL. The service was written in such a way that it accepts numbers starting with PL, in order not to cause problems to the member merchants that receive the papara number from their users.
   *
   * @var int $parseAccountNumber
   */
  public $parseAccountNumber;

  /**
   * Gets or sets amount. The amount of the payment transaction. This amount will be transferred to the account of the user who received the payment. This figure plus transaction fee will be charged to the merchant account.
   *
   * @var float $amount
   */
  public $amount;

  /**
   * Gets or sets mass payment ID. Unique value sent by merchant to prevent erroneous repetition in payment transactions. If a massPaymentId that was sent previously and succeeded is sent again with a new request, the request will fail.
   *
   * @var string $massPaymentId
   */
  public $massPaymentId;

  /**
   * Gets or sets national identity number.It provides the control of the identity information sent by the user who will receive the payment, in the Papara system. In case of a conflict of credentials, the transaction will not take place.
   *
   * @var int $turkishNationalId
   */
  public $turkishNationalId;

  /**
   * Gets or sets description. Description of the transaction provided by the merchant. It is not a required field. If sent, the customer sees in the transaction descriptions.
   *
   * @var string $description
   */
  public $description;
}
