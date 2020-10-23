<?php

/**
 * Validation service will be used for validating an end user. Validation can be performed by account number, e-mail address, phone number, national identity number.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara\Services;

use Papara\Options\ValidationByAccountNumberOptions;
use Papara\Options\ValidationByEmailOptions;
use Papara\Options\ValidationByIdOptions;
use Papara\Options\ValidationByPhoneNumberOptions;
use Papara\Options\ValidationByTcknOptions;
use Papara\PaparaResult;
use Papara\PaparaService;

class ValidationService extends PaparaService
{
  /**
   * Initializes a new instance of the ValidationService class.
   *
   * @param string $apiKey Merchant API Key
   * @param boolean $isTest Select whether the environment will be test or production
   */
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/validation";
  }

  /**
   * Returns end user information for validation by given user ID.
   *
   * @param ValidationByIdOptions $options
   * @return PaparaResult
   */
  public function ValidateById(ValidationByIdOptions $options)
  {
    $result = $this->GetResult("/id", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns end user information for validation by given user account number.
   *
   * @param ValidationByAccountNumberOptions $options
   * @return PaparaResult
   */
  public function ValidateByAccountNumber(ValidationByAccountNumberOptions $options)
  {
    $result = $this->GetResult("/accountNumber", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns end user information for validation by given phone number.
   *
   * @param ValidationByPhoneNumberOptions $options
   * @return PaparaResult
   */
  public function ValidateByPhoneNumber(ValidationByPhoneNumberOptions $options)
  {
    $result = $this->GetResult("/phoneNumber", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns end user information for validation by given user e-mail address.
   *
   * @param ValidationByEmailOptions $options
   * @return PaparaResult
   */
  public function ValidateByEmail(ValidationByEmailOptions $options)
  {
    $result = $this->GetResult("/email", $options);
    return new PaparaResult($result);
  }

  /**
   * Returns end user information for validation by given user national identity number.
   *
   * @param ValidationByTcknOptions $options
   * @return PaparaResult
   */
  public function ValidateByTckn(ValidationByTcknOptions $options)
  {
    $result = $this->GetResult("/tckn", $options);
    return new PaparaResult($result);
  }
}
