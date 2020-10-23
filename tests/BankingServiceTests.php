<?php

/**
 * Banking service unit tests.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

use Papara\Options\BankingWithdrawalOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class BankingServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the BankingServiceTests class.
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
   * Test Case: Getting bank account from banking service
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetBankAccountsReturnsBankAccount()
  {
    $result = $this->client->BankingService->GetBankAccounts();

    $this->assertTrue($result->succeeded);
  }

  /**
   * Test Case: Withdrawal to bank account
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testWithdrawalReturnsSuccess()
  {
    $bankAccountResult = $this->client->BankingService->GetBankAccounts();
    
    $this->assertTrue($bankAccountResult->succeeded);
    $this->assertNotEquals(0, count($bankAccountResult->data), "Merchant must define at least 1 bank account from Papara portal.");

    $bankAccount = $bankAccountResult->data[0];

    $options = new BankingWithdrawalOptions;
    $options->amount = 10;
    $options->bankAccountId = $bankAccount->bankAccountId;

    $result = $this->client->BankingService->Withdrawal($options);

    $this->assertTrue($result->succeeded);
  }
}
