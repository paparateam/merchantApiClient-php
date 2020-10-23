<?php

/**
 * Validation service unit tests.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

use Papara\Options\ValidationByAccountNumberOptions;
use Papara\Options\ValidationByEmailOptions;
use Papara\Options\ValidationByIdOptions;
use Papara\Options\ValidationByPhoneNumberOptions;
use Papara\Options\ValidationByTcknOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class ValidationServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the ValidationServiceTests class.
   *
   */
  public function __construct()
  {
    parent::__construct();

    /**
     * Get environment variable, API Key and test variables from config,
     */
    $this->config = include 'config.php';

    /**
     * Set API key to client.
     */
    $this->client = new PaparaClient($this->config['ApiKey'], true);
  }

  /**
   * Test Case: Validating account with account ID
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testValidateById()
  {
    $validationByIdOptions = new ValidationByIdOptions;
    $validationByIdOptions->userId = $this->config['PersonalAccountId'];

    $result = $this->client->ValidationService->ValidateById($validationByIdOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Validating account with account number
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testValidateByAccountNumber()
  {
    $validationByAccountNumberOptions = new ValidationByAccountNumberOptions;
    $validationByAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->ValidationService->ValidateByAccountNumber($validationByAccountNumberOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Validating account with phone number
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testValidateByPhoneNumber()
  {
    $validationByPhoneNumberOptions = new ValidationByPhoneNumberOptions;
    $validationByPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->ValidationService->ValidateByPhoneNumber($validationByPhoneNumberOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Validating account with email
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testValidateByEmail()
  {
    $ValidationByEmailOptions = new ValidationByEmailOptions;
    $ValidationByEmailOptions->email = $this->config['PersonalEmail'];

    $result = $this->client->ValidationService->ValidateByEmail($ValidationByEmailOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Validating Papara account with Turkish identity number (TCKN)
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testValidateByTckn()
  {
    $validationByTcknOptions = new ValidationByTcknOptions;
    $validationByTcknOptions->tckn = $this->config['TCKN'];

    $result = $this->client->ValidationService->ValidateByTckn($validationByTcknOptions);

    $this->assertTrue($result->succeeded);
  }
}
