<?php

/**
 * Account service unit tests.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

use Papara\Options\LedgerListOptions;
use Papara\Options\SettlementGetOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class AccountServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the AccountServiceTests class.
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
   * Test Case: Getting account information from account service
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetAccountReturnsAccount()
  {
    $result = $this->client->AccountService->GetAccount();
    $this->assertEquals(true, $result->succeeded);
  }

  /**
   * Test Case:Listing ledgers from account service
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testListLedgersReturnsListOfLedgers()
  {
    $options = new LedgerListOptions;
    $options->startDate = "2020-01-01T00:00:00.000Z";
    $options->endDate = "2020-09-01T00:00:00.000Z";
    $options->page = 1;
    $options->pageSize = 20;

    $result = $this->client->AccountService->ListLedgers($options);

    $this->assertEquals(true, $result->succeeded);
  }
  
  /**
   * Test Case: Getting settlement from account service
   *
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetSettlementReturnsSettlement()
  {
    $options = new SettlementGetOptions;
    $options->startDate = "2020-01-01T00:00:00.000Z";
    $options->endDate = "2020-09-01T00:00:00.000Z";

    $result = $this->client->AccountService->GetSettlement($options);

    $this->assertEquals(true, $result->succeeded);
  }
}
