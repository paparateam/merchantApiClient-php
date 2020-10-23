<?php

use Papara\Options\MassPaymentByReferenceOptions;
use Papara\Options\MassPaymentToEmailOptions;
use Papara\Options\MassPaymentToPaparaNumberOptions;
use Papara\Options\MassPaymentToPhoneNumberOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class MassPaymentServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the MassPaymentServiceTests class.
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
   * Test Case: Creating mass payment with account number
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testPostMassPayment()
  {
    $massPaymentToPaparaNumberOptions = new MassPaymentToPaparaNumberOptions;
    $massPaymentToPaparaNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];
    $massPaymentToPaparaNumberOptions->amount = 1;
    $massPaymentToPaparaNumberOptions->description = 'Php Unit Test: MassPaymentToPaparaNumber';
    $massPaymentToPaparaNumberOptions->massPaymentId = uniqid();
    $massPaymentToPaparaNumberOptions->parseAccountNumber = 1;
    $massPaymentToPaparaNumberOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithAccountNumber($massPaymentToPaparaNumberOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Creating mass payment with email
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testPostMassPaymentToEmail()
  {
    $massPaymentToEmailOptions = new MassPaymentToEmailOptions;
    $massPaymentToEmailOptions->amount = 1;
    $massPaymentToEmailOptions->description = 'Php Unit Test: MassPaymentToEmail';
    $massPaymentToEmailOptions->massPaymentId = uniqid();
    $massPaymentToEmailOptions->email = $this->config['PersonalEmail'];
    $massPaymentToEmailOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithEmail($massPaymentToEmailOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Creating mass payment with phone number
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testPostMassPaymentToPhoneNumber()
  {
    $massPaymentToPhoneNumberOptions = new MassPaymentToPhoneNumberOptions;
    $massPaymentToPhoneNumberOptions->amount = 1;
    $massPaymentToPhoneNumberOptions->description = 'Php Unit Test: MassPaymentToEmail';
    $massPaymentToPhoneNumberOptions->massPaymentId = uniqid();
    $massPaymentToPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $massPaymentToPhoneNumberOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithPhoneNumber($massPaymentToPhoneNumberOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Get mass payment by mass payment reference number
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetMassPaymentByReference()
  {
    $massPaymentToPhoneNumberOptions = new MassPaymentToPhoneNumberOptions;
    $massPaymentToPhoneNumberOptions->amount = 1;
    $massPaymentToPhoneNumberOptions->description = 'Php Unit Test: MassPaymentToEmail';
    $massPaymentToPhoneNumberOptions->massPaymentId = uniqid();
    $massPaymentToPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $massPaymentToPhoneNumberOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithPhoneNumber($massPaymentToPhoneNumberOptions);

    $this->assertTrue($result->succeeded);

    $getMassPaymentByReferenceOptions = new MassPaymentByReferenceOptions;
    $getMassPaymentByReferenceOptions->reference = $result->data->massPaymentId;
  
    $masspayment = $this->client->MassPaymentService->getMassPaymentByReference($getMassPaymentByReferenceOptions);

    $this->assertTrue($masspayment->succeeded);
  }
}
