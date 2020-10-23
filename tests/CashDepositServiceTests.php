<?php

/**
 * Cash Deposit service unit tests.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

use Papara\Options\CashDepositByDateOptions;
use Papara\Options\CashDepositByReferenceOptions;
use Papara\Options\CashDepositCompleteOptions;
use Papara\Options\CashDepositControlOptions;
use Papara\Options\CashDepositGetOptions;
use Papara\Options\CashDepositSettlementOptions;
use Papara\Options\CashDepositTcknControlOptions;
use Papara\Options\CashDepositToAccountNumberOptions;
use Papara\Options\CashDepositToPhoneOptions;
use Papara\Options\CashDepositToTcknOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class CashDepositServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the CashDepositServiceTests class.
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
   * Test Case: Creating cash deposit with phone number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateWithPhoneNumber()
  {
    $cashDepositToPhoneOptions = new CashDepositToPhoneOptions;
    $cashDepositToPhoneOptions->amount = 10;
    $cashDepositToPhoneOptions->merchantReference = uniqid();
    $cashDepositToPhoneOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->CashDepositService->createWithPhoneNumber($cashDepositToPhoneOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositGetOptions = new CashDepositGetOptions;
    $cashDepositGetOptions->id = $result->data->id;

    $cashDepositResult = $this->client->CashDepositService->getCashDeposit($cashDepositGetOptions);

    $this->assertTrue($cashDepositResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit with account number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateWithAccountNumber()
  {
    $cashDepositToAccountNumberOptions = new CashDepositToAccountNumberOptions;
    $cashDepositToAccountNumberOptions->amount = 10;
    $cashDepositToAccountNumberOptions->merchantReference = uniqid();
    $cashDepositToAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->CashDepositService->createWithAccountNumber($cashDepositToAccountNumberOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositGetOptions = new CashDepositGetOptions;
    $cashDepositGetOptions->id = $result->data->id;

    $cashDepositResult = $this->client->CashDepositService->getCashDeposit($cashDepositGetOptions);

    $this->assertTrue($cashDepositResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit with Turkish Identity (TCKN) number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateWithTckn()
  {
    $cashDepositToTcknOptions = new CashDepositToTcknOptions;
    $cashDepositToTcknOptions->amount = 10;
    $cashDepositToTcknOptions->merchantReference = uniqid();
    $cashDepositToTcknOptions->tckn = $this->config['TCKN'];

    $result = $this->client->CashDepositService->createWithTckn($cashDepositToTcknOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositGetOptions = new CashDepositGetOptions;
    $cashDepositGetOptions->id = $result->data->id;

    $cashDepositResult = $this->client->CashDepositService->getCashDeposit($cashDepositGetOptions);

    $this->assertTrue($cashDepositResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit provision with Turkish Identity (TCKN) number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateProvisionWithTcknControl()
  {
    $cashDepositTcknControlOptions = new CashDepositTcknControlOptions;
    $cashDepositTcknControlOptions->amount = 10;
    $cashDepositTcknControlOptions->merchantReference = uniqid();
    $cashDepositTcknControlOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $cashDepositTcknControlOptions->tckn = $this->config['TCKN'];

    $result = $this->client->CashDepositService->createProvisionWithTcknControl($cashDepositTcknControlOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);

    $this->assertTrue($compilationResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit provision with phone number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateProvisionWithPhoneNumber()
  {
    $cashDepositToPhoneOptions = new CashDepositToPhoneOptions;
    $cashDepositToPhoneOptions->amount = 10;
    $cashDepositToPhoneOptions->merchantReference = uniqid();
    $cashDepositToPhoneOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->CashDepositService->createProvisionWithPhoneNumber($cashDepositToPhoneOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);

    $this->assertTrue($compilationResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit provision with account number.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateProvisionWithAccountNumber()
  {
    $cashDepositToAccountNumberOptions = new CashDepositToAccountNumberOptions;
    $cashDepositToAccountNumberOptions->amount = 10;
    $cashDepositToAccountNumberOptions->merchantReference = uniqid();
    $cashDepositToAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->CashDepositService->createProvisionWithAccountNumber($cashDepositToAccountNumberOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);

    $this->assertTrue($compilationResult->succeeded);
  }

  /**
   * Test Case: Creating cash deposit provision with end user's national identity number(TCKN).
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreateProvisionWithTckn()
  {
    $cashDepositToTcknOptionsOptions = new CashDepositToTcknOptions;
    $cashDepositToTcknOptionsOptions->amount = 10;
    $cashDepositToTcknOptionsOptions->merchantReference = uniqid();
    $cashDepositToTcknOptionsOptions->tckn = $this->config["TCKN"];

    $result = $this->client->CashDepositService->createProvisionWithTckn($cashDepositToTcknOptionsOptions);

    $this->assertTrue($result->succeeded);

    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);

    $this->assertTrue($compilationResult->succeeded);
  }

  /**
   * Test Case: Control cash deposit provision by merchant reference code.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testControlProvisionByReference()
  {
    $cashDepositToAccountNumberOptions = new CashDepositToAccountNumberOptions;
    $cashDepositToAccountNumberOptions->amount = 10;
    $cashDepositToAccountNumberOptions->merchantReference = uniqid();
    $cashDepositToAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->CashDepositService->createProvisionWithAccountNumber($cashDepositToAccountNumberOptions);

    print_r($result);

    $this->assertTrue($result->succeeded);

    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->controlProvisionByReference($options);

    print_r($cashdeposit);

    $this->assertTrue($cashdeposit->succeeded);
  }

  /**
   * Test Case: Complete cash deposit provision by merchant reference code.
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCompleteProvisionByReference()
  {
    $cashDepositToTcknOptionsOptions = new CashDepositToTcknOptions;
    $cashDepositToTcknOptionsOptions->amount = 10;
    $cashDepositToTcknOptionsOptions->merchantReference = uniqid();
    $cashDepositToTcknOptionsOptions->tckn = $this->config["TCKN"];

    $result = $this->client->CashDepositService->createProvisionWithTckn($cashDepositToTcknOptionsOptions);

    $this->assertTrue($result->succeeded);

    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->completeProvisionByReference($options);

    $this->assertTrue($cashdeposit->succeeded);
  }

  public function testGetCashDepositByReference()
  {
    $cashDepositByReferenceOptions = new CashDepositByReferenceOptions;
    $cashDepositByReferenceOptions->reference = "78cadfb9-71d1-42dd-9793-84e90af53b07";

    $result = $this->client->CashDepositService->getByReference($cashDepositByReferenceOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Getting cash deposits with date range
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetCashDepositByDate()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositByDateOptions = new CashDepositByDateOptions;
    $cashDepositByDateOptions->startDate = $startDate->format('c');
    $cashDepositByDateOptions->endDate = $endDate->format('c');
    $cashDepositByDateOptions->pageIndex = 1;
    $cashDepositByDateOptions->pageItemCount = 20;

    $result = $this->client->CashDepositService->getCashDepositByDate($cashDepositByDateOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Getting cash deposit settlements with date range
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testSettlements()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositSettlementOptions = new CashDepositSettlementOptions;
    $cashDepositSettlementOptions->startDate = $startDate->format('c');
    $cashDepositSettlementOptions->endDate = $endDate->format('c');
    $cashDepositSettlementOptions->entryType = 1;

    $result = $this->client->CashDepositService->settlements($cashDepositSettlementOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Getting cash deposit settlements with date range
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testProvisionSettlements()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositSettlementOptions = new CashDepositSettlementOptions;
    $cashDepositSettlementOptions->startDate = $startDate->format('c');
    $cashDepositSettlementOptions->endDate = $endDate->format('c');
    $cashDepositSettlementOptions->entryType = 1;

    $result = $this->client->CashDepositService->provisionSettlements($cashDepositSettlementOptions);

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Getting cash deposit by merchant reference code
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetByReference()
  {
    $options = new CashDepositByReferenceOptions;
    $options->reference = "78cadfb9-71d1-42dd-9793-84e90af53b07";

    $cashdeposit = $this->client->CashDepositService->getByReference($options);

    $this->assertTrue($cashdeposit->succeeded);
  }
}
