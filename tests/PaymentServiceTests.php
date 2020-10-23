<?php

use Papara\Options\PaymentGetByReferenceOptions;
use Papara\Options\PaymentGetOptions;
use Papara\Options\PaymentListOptions;
use Papara\Options\PaymentRefundOptions;
use Papara\PaparaClient;
use PHPUnit\Framework\TestCase;

final class PaymentServiceTests extends TestCase
{
  private PaparaClient $client;
  private $config;

  /**
   * Initializes a new instance of the PaymentServiceTests class.
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
   * Test Case: Get payment by payment id
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetPayment()
  {
    $paymentListOptions = new PaymentListOptions;
    $paymentListOptions->pageIndex = 1;
    $paymentListOptions->pageItemCount = 20;

    $result = $this->client->PaymentService->list($paymentListOptions);

    $this->assertTrue($result->succeeded);

    if (count($result->data->items) > 0) {
      $payment = $result->data->items[0];

      $paymentGetOptions = new PaymentGetOptions;
      $paymentGetOptions->id = $payment->id;

      $paymentResult = $this->client->PaymentService->getPayment($paymentGetOptions);

      $this->assertTrue($paymentResult->succeeded);
    }
  }

  /**
   * Test Case: Create payment
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testCreatePayment()
  {
    $referenceId  = uniqid();

    $paymentCreateOptions = new PaymentCreateOptions;
    $paymentCreateOptions->amount = 1;
    $paymentCreateOptions->notificationUrl = "https://testmerchant.com/notification";
    $paymentCreateOptions->orderDescription = "Payment Unit Test";
    $paymentCreateOptions->redirectUrl = "https://testmerchant.com/userredirect";
    $paymentCreateOptions->referenceId = $referenceId;
    $paymentCreateOptions->turkishNationalId = $this->config["TCKN"];

    $result = $this->client->PaymentService->createPayment($paymentCreateOptions);

    $this->assertTrue($result->succeeded);
    $this->assertEquals($referenceId, $result->data->referenceId);
  }

  /**
   * Test Case: Refund a payment
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testRefund()
  {
    $paymentListOptions = new PaymentListOptions;
    $paymentListOptions->pageIndex = 1;
    $paymentListOptions->pageItemCount = 20;

    $paymentListResult = $this->client->PaymentService->list($paymentListOptions);

    $this->assertTrue($paymentListResult->succeeded);

    if (count($paymentListResult->data->items) > 0) {
      $payment = $paymentListResult->data->items[0];

      $paymentRefundOptions = new PaymentRefundOptions;
      $paymentRefundOptions->id = $payment->id;

      $result = $this->client->PaymentService->refund($paymentRefundOptions);

      $this->assertTrue($result->succeeded);
    }
  }

  /**
   * Test Case: list payments 
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testListPayments()
  {
    $paymentListOptions = new PaymentListOptions;
    $paymentListOptions->pageIndex = 1;
    $paymentListOptions->pageItemCount = 20;

    $paymentListResult = $this->client->PaymentService->list($paymentListOptions);

    $this->assertTrue($paymentListResult->succeeded);
  }

  /**
   * Test Case: Get payment by payment reference number
   * Results;
   *        should be succeeded
   *        should not have error
   * @return void
   */
  public function testGetPaymentByReference()
  {
    $referenceId  = uniqid();

    $paymentCreateOptions = new PaymentCreateOptions;
    $paymentCreateOptions->amount = 1;
    $paymentCreateOptions->notificationUrl = "https://testmerchant.com/notification";
    $paymentCreateOptions->orderDescription = "Payment Unit Test";
    $paymentCreateOptions->redirectUrl = "https://testmerchant.com/userredirect";
    $paymentCreateOptions->referenceId = $referenceId;
    $paymentCreateOptions->turkishNationalId = $this->config["TCKN"];

    $result = $this->client->PaymentService->createPayment($paymentCreateOptions);

    $this->assertTrue($result->succeeded);
    $this->assertEquals($referenceId, $result->data->referenceId);

    $paymentByReferenceOptions = new PaymentGetByReferenceOptions;
    $paymentByReferenceOptions->referenceId = $result->data->referenceId;

    $payment = $this->client->PaymentService->getPaymentByReference($paymentByReferenceOptions);

    $this->assertTrue($payment->succeeded);
  }
}
