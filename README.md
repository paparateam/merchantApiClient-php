# Table of Contents

<a href="#intro">Intro</a>

<a href="#enums">Enums</a>

<a href="#account">Account</a>

<a href="#banking">Banking</a>

<a href="#cash-deposit">Cash Deposit</a>

<a href="#mass-payment">Mass Payment</a>

<a href="#payments">Payments</a>

<a href="#validation">Validation</a>

<a href="#response-types">Response Types</a>

# <a name="intro">Intro</a>

Integrating Papara into your software requires following;

1. Obtain your API Key. So Papara can authenticate integration’s API requests. To obtain your API Key, follow https://merchant.test.papara.com/ URL. After sucessfully logged in, API Key can be viewed on https://merchant.test.papara.com/APIInfo

2. Install client library. So your integration can interact with the Papara API. Install operations are like following.

# Configuration

Client can be created with class or standard way;

Standard way:

```php

require_once('PATH_TO_PAPARA/bootstrap.php');

use \Papara\PaparaClient;
$client = new PaparaClient('YOUR_PAPARA_API_KEY', true);
```

Or object oriented way:

```php
use Papara\PaparaClient;

class AccountServiceTests {
  private PaparaClient $client;

  public function __construct()
  {
    $this->client = new PaparaClient($this->config['ApiKey'], true);
  }
}
```

## Composer operations

```bash
# Install via composer
composer require papara/papara
```

# <a name="enums">Enums</a>

# CashDepositProvisionStatus

When a cash deposit request was made, following statuses will return and display the status of provision.

| **Key**         | **Value** | **Description**                      |
| --------------- | --------- | ------------------------------------ |
| Pending         | 0         | Cash deposit is pending provision.   |
| Complete        | 1         | Cash Deposit is completed            |
| Cancel          | 2         | Cash Deposit is cancelled            |
| ReadyToComplete | 3         | Cash Deposit is ready for completion |

# Currency

All currencies on the API are listed below.

| **Key** | **Value** | **Description** |
| ------- | --------- | --------------- |
| TRY     | 0         | Turkish Lira    |
| USD     | 1         | U.S Dollar      |
| EUR     | 2         | Euro            |

# EntryType

Entry types are used in ledgers and cash deposits in order to track the money in the operation. Possible entry types are listed below.

| **Key**                       | **Value** | **Description**                                                                                            |
| ----------------------------- | --------- | ---------------------------------------------------------------------------------------------------------- |
| BankTransfer                  | 1         | Bank Transfer: Cash deposit or withdrawal                                                                  |
| CorporateCardTransaction      | 2         | Papara Corporate Card Transaction: Transaction which was operated by corporation card assigned to merchant |
| LoadingMoneyFromPhysicalPoint | 6         | Loading Money From Physical Point: Cash deposit operation from contracted location                         |
| MerchantPayment               | 8         | Merchant Payment: Checkout via Papara                                                                      |
| PaymentDistribution           | 9         | Payment Distribution: Masspayment via papara                                                               |
| EduPos                        | 11        | Offline payment. EDU POS via Papara                                                                        |

# PaymentMethod

Three types of payment is accepted in the system. Possible payment methods are listed below.

| **Key**       | **Value** | **Description**        |
| ------------- | --------- | ---------------------- |
| PaparaAccount | 0         | Papara Account Balance |
| Card          | 1         | Registered Credit Card |
| Mobile        | 2         | Mobile Payment         |

# PaymentStatus

After a payment was done, API returns the payment status which are shown below.

| **Key**   | **Value** | **Description**            |
| --------- | --------- | -------------------------- |
| Pending   | 0         | Payment waiting            |
| Completed | 1         | User completed the payment |
| Refunded  | 2         | Order refunded             |

# <a name="account">Account</a>

This part contains the technical integration information prepared for the use of the account and balance information of the merchant. Account and balance information on Papara account can be retrieved by Account service. Developers can also retrieve the balance history, which contains a list of transactions that contributed to the balance.

## Get Account Information

Returns the merchant account and balance information. Balance information contains current balance, available funds and unavailable funds, whilst account information contains brand name and full title of the merchant. To perform this operation use `GetAccount` method on `Account` service.

### Account Model

`Account` contains account information that returns from API.

| **Variable Name**   | **Type**                 | **Description**                   |
| ------------------- | ------------------------ | --------------------------------- |
| LegalName           | string                   | Returns merchant’s company title. |
| BrandName           | string                   | Returns brand name.               |
| AllowedPaymentTypes | List<AllowedPaymentType> | Returns allowed payment types.    |
| Balances            | List<AccountBalance>     | Returns account balances          |

### AllowedPaymentType

`AllowedPaymentType` displays allowed payment types.

| **Variable Name** | **Type** | **Description**                                                                                                        |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------- |
| PaymentMethod     | int      | Returns payment method.<br />0 - Papara Account Balance <br />1 - Credit/Debit Card <br />2 - Mobile - Mobile Payment. |

### AccountBalance

`AccountBalance` shows current balance figures and lists three types of balances and general currency.

| **Variable Name** | **Type** | **Description**           |
| ----------------- | -------- | ------------------------- |
| Currency          | int      | Returns currency          |
| TotalBalance      | float    | Returns total balance     |
| LockedBalance     | float    | Returns locked balance    |
| AvailableBalance  | float    | Returns available balance |

### Service Method

#### Purpose

Return account information and current balance for authorized merchant.

| **Method** | **Params** | **Return Type**       |
| ---------- | ---------- | --------------------- |
| GetAccount | None       | PaparaResult<Account> |

#### Usage

```php
  /**
   * Returns account information and current balance for authorized merchant.
   *
   * @return PaparaResult
   */
  public function GetAccount()
  {
    $result = $this->client->AccountService->GetAccount();
    return $result;
  }
```

## List Ledgers

Returns the merchant account history (list of transactions) in paged format. This method is used for listing all transactions made for a merchant including resulting balance for each transaction. To perform this operation use `ListLedgers` method on `Account` service. `startDate` and `endDate` should be provided.

### AccountLedger

`AccountLedger` represents a transaction itself that returns from API.

| **Variable Name**   | **Type**     | **Description**                                                                                                                                                                                                                                                                                                            |
| ------------------- | ------------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| ID                  | int          | Returns merchant ID                                                                                                                                                                                                                                                                                                        |
| CreatedAt           | DateTime     | Returns created date of a ledger                                                                                                                                                                                                                                                                                           |
| EntryType           | EntryType    | Returns entry type                                                                                                                                                                                                                                                                                                         |
| EntryTypeName       | string       | Returns entry type name                                                                                                                                                                                                                                                                                                    |
| Amount              | float        | Returns amount                                                                                                                                                                                                                                                                                                             |
| Fee                 | float        | Returns fee                                                                                                                                                                                                                                                                                                                |
| Currency            | int          | Returns currency                                                                                                                                                                                                                                                                                                           |
| CurrencyInfo        | CurrencyInfo | Returns currency information                                                                                                                                                                                                                                                                                               |
| ResultingBalance    | float        | Returns resulting balance                                                                                                                                                                                                                                                                                                  |
| Description         | string       | Returns description                                                                                                                                                                                                                                                                                                        |
| MassPaymentId       | string       | Returns mass payment Id. It is the unique value sent by the merchant to prevent duplicate repetition in payment transactions. It is displayed in transaction records of masspayment type in account transactions to ensure control of the transaction. It will be null in other payment types.                             |
| CheckoutPaymentId   | string       | Returns checkout payment ID. It is the ID field in the data object in the payment record transaction. It is the unique identifier of the payment transaction. It is displayed in transaction records of checkout type in account transactions. It will be null in other payment types.                                     |
| CheckoutReferenceID | string       | Returns checkout reference ID. This is the referenceId field sent when creating the payment transaction record. It is the reference information of the payment transaction in the merchant system. It is displayed in transaction records of checkout type in account transactions. It will be null in other payment types |

### CurrencyInfo

`CurrencyInfo` represents the currency information available in a ledger that returns from API.

| **Variable Name**    | **Type** | **Description**                           |
| -------------------- | -------- | ----------------------------------------- |
| CurrencyEnum         | Currency | Returns currency type.                    |
| Symbol               | string   | Returns currency symbol                   |
| Code                 | string   | Returns currency code                     |
| PreferredDisplayCode | string   | Returns currency's preferred display code |
| Name                 | string   | Returns currency name                     |
| IsCryptoCurrency     | bool     | Returns if it is a cryptocurrency or not  |
| Precision            | int      | Returns currency precision                |
| IconUrl              | string   | Returns currency icon URL                 |

### LedgerListOptions Model

`LedgerListOptions` is used by account service for providing request parameters for ledger listing operation.

| **Variable Name** | **Type** | **Description**                                                                                                                                             |
| ----------------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| startDate         | DateTime | Gets or sets start date for transactions                                                                                                                    |
| endDate           | DateTime | Gets or sets end date for transactions                                                                                                                      |
| entryType         | enum     | Gets or sets entry types                                                                                                                                    |
| accountNumber     | int      | Gets or sets merchant account number                                                                                                                        |
| page              | int      | Gets or sets the requested page number. If the requested date has more than 1 page of results for the requested PageSize, use this to iterate through pages |
| pageSize          | int      | Gets or sets number of elements you want to receive per request page. Min=1, Max=50                                                                         |

### Service Method

#### Purpose

Returns list of ledgers for authorized merchant.

| **Method**  | **Params**        | **Return Type**             |
| ----------- | ----------------- | --------------------------- |
| ListLedgers | LedgerListOptions | PaparaResult<AccountLedger> |

#### Usage

```php
  /**
   * Returns list of ledgers for authorized merchant.
   *
   * @param LedgerListOptions $options
   * @return PaparaResult
   */
  public function ListLedgers()
  {
    $options = new LedgerListOptions;
    $options->startDate = "2020-01-01T00:00:00.000Z";
    $options->endDate = "2020-09-01T00:00:00.000Z";
    $options->page = 1;
    $options->pageSize = 20;

    $result = $this->client->AccountService->ListLedgers($options);
    return $result;
  }
```

## Get Settlement

Calculates the count and volume of transactions within the given time period. To perform this operation use `GetSettlement` method on `Account` service. `startDate` and `endDate` should be provided.

### Settlement Model

`Settlement` is used by account service to match returning settlement values API.

| **Variable Name** | **Type** | **Description**            |
| ----------------- | -------- | -------------------------- |
| Count             | int      | Returns transaction count  |
| Volume            | int      | Returns transaction volume |

### SettlementGetOptions Model

`SettlementGetOptions` is used by account service for providing settlement request parameters.

| **Variable Name** | **Type**  | **Description**                          |
| ----------------- | --------- | ---------------------------------------- |
| startDate         | DateTime  | Gets or sets start date for transactions |
| endDate           | DateTime  | Gets or sets end date for transactions   |
| entryType         | EntryType | Gets or sets entry types                 |

### Service Method

#### Purpose

Returns settlement for authorized merchant.

| **Method**    | **Params**           | **Return Type**          |
| ------------- | -------------------- | ------------------------ |
| GetSettlement | SettlementGetOptions | PaparaResult<Settlement> |

#### Usage

```php
  /**
   * Returns settlement for authorized merchant.
   *
   * @param SettlementGetOptions $options
   * @return PaparaResult
   */
  public function GetSettlement()
  {
    $options = new SettlementGetOptions;
    $options->startDate = "2020-01-01T00:00:00.000Z";
    $options->endDate = "2020-09-01T00:00:00.000Z";

    $result = $this->client->AccountService->GetSettlement($options);
    return $result;
  }
```

# <a name="banking">Banking</a>

This part contains technical integration information prepared for merchants those who want to quickly and securely list their bank accounts with Papara and/or create a withdrawal request to their bank accounts.

## Get Bank Accounts

Retrieves registered bank accounts of the merchant. To perform this operation use `GetBankAccounts` method on `Banking` service.

### BankAccount Model

`BankAccount` contains bank account information.

| **Variable Name** | **Type** | **Description**                    |
| ----------------- | -------- | ---------------------------------- |
| BankAccountId     | int?     | Returns merchant's bank account ID |
| BankName          | string   | Returns merchant bank name         |
| BranchCode        | string   | Returns merchant branch code       |
| Iban              | string   | Returns IBAN Number                |
| AccountCode       | string   | Returns merchant account code      |
| Description       | string   | Returns description                |
| Currency          | string   | Returns currency                   |

### Service Method

#### Purpose

Returns bank accounts for authorized merchant.

| **Method**      | **Params** | **Return Type**           |
| --------------- | ---------- | ------------------------- |
| GetBankAccounts |            | PaparaResult<BankAccount> |

#### Usage

```php
  /**
   * Returns bank accounts for authorized merchant.
   *
   * @return PaparaResult
   */
  public function GetBankAccounts()
  {
	$result = $this->client->BankingService->GetBankAccounts();
    return $result;
  }
```

## Withdrawal

Generates withdrawal requests for merchants. To perform this operation use `Withdrawal` method on `Banking` service.

### BankingWithdrawalOptions

`BankingWithdrawalOptions` is used by banking service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                           |
| ----------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| bankAccountId     | int?     | Gets or sets target bank account id which money will be transferred to when withdrawal is completed.It will be obtained as a result of the request to list bank accounts. |
| amount            | float    | Gets or sets withdrawal amount                                                                                                                                            |

### Service Method

#### Purpose

Creates a withdrawal request from given bank account for authorized merchant.

| **Method** | **Params**               | **Return Type** |
| ---------- | ------------------------ | --------------- |
| Withdrawal | BankingWithdrawalOptions | PaparaResult    |

#### Usage

```php
  /**
   * Creates a withdrawal request from given bank account for authorized merchant.
   *
   * @param BankingWithdrawalOptions $options
   * @return PaparaResult
   */
  public function Withdrawal()
  {
    $bankAccountResult = $this->client->BankingService->GetBankAccounts();

    $bankAccount = $bankAccountResult->data[0];

    $options = new BankingWithdrawalOptions;
    $options->amount = 10;
    $options->bankAccountId = $bankAccount->bankAccountId;

    $result = $this->client->BankingService->Withdrawal($options);
    return $result;
  }
```

## Possible Errors and Error Codes

| **Error Code** | **Error Description**                         |
| -------------- | --------------------------------------------- |
| 105            | Insufficient funds.                           |
| 115            | Requested amount is lower then minimum limit. |
| 120            | Bank account not found.                       |
| 247            | Merchant's account is not active.             |

# <a name="cash-deposit">Cash Deposit</a>

With the integration of Papara physical point, you can become a money loading point and earn money from which end users can load balance to their Papara accounts. Physical point integration methods should only be used in scenarios where users load cash to Papara accounts.

## Get Cash Deposit Information

Returns cash deposit information. To perform this operation use `getCashDeposit` method on `Cash Deposit` service. `id` should be provided.

### CashDeposit Model

`CashDeposit` is used by cash deposit service to match returning cash deposit values from API

| **Variable Name** | **Type**  | **Description**                                 |
| ----------------- | --------- | ----------------------------------------------- |
| MerchantReference | string    | Returns merchant reference code                 |
| Id                | int?      | Returns cash deposit ID                         |
| CreatedAt         | DateTime? | Returns created date of cash deposit            |
| Amount            | float     | Returns amount of cash deposit                  |
| Currency          | int?      | Returns currency of cash deposit                |
| Fee               | float     | Returns fee of cash deposit                     |
| ResultingBalance  | float     | Returns resulting balance in merchant's account |
| Description       | string    | Returns description                             |

### CashDepositGetOptions

`CashDepositGetOptions` is used by cash deposit service for providing request parameters

| **Variable Name** | **Type** | **Description**              |
| ----------------- | -------- | ---------------------------- |
| id                | int      | Gets or sets cash deposit ID |

### Service Method

#### Purpose

Returns a cash deposit information

| **Method**     | **Params**            | **Return Type**           |
| -------------- | --------------------- | ------------------------- |
| getCashDeposit | CashDepositGetOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Returns a cash deposit information.
   *
   * @param CashDepositGetOptions $options
   * @return PaparaResult
   */
  public function getCashDeposit()
  {
    $cashDepositGetOptions = new CashDepositGetOptions;
    $cashDepositGetOptions->id = $result->data->id;

    $cashDepositResult = $this->client->CashDepositService->getCashDeposit($cashDepositGetOptions);
    return $result;
  }
```

## Get Cash Deposit By Reference

Returns the information of the money loading process from the physical point with the merchant reference information. To perform this operation use `getByReference` method on `Cash Deposit` service. `reference` should be provided.

### CashDepositByReferenceOptions

`CashDepositByReferenceOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                   |
| ----------------- | -------- | ----------------------------------------------------------------- |
| reference         | string   | Gets or sets cash deposit reference no. Reference no is required. |

### Service Method

#### Purpose

Returns a cash deposit object using merchant's unique reference number.

| **Method**     | **Params**                    | **Return Type**           |
| -------------- | ----------------------------- | ------------------------- |
| getByReference | CashDepositByReferenceOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Returns a cash deposit object using merchant's unique reference number.
   *
   * @param CashDepositByReferenceOptions $options
   * @return PaparaResult
   */
  public function getByReference()
  {
    $cashDepositByReferenceOptions = new CashDepositByReferenceOptions;
    $cashDepositByReferenceOptions->reference = "REFERENCE_NO";

    $result = $this->client->CashDepositService->getByReference($cashDepositByReferenceOptions);
    return $result;
  }
```

## Create Cash Deposit With Phone Number

It deposits money to the user from the physical point. using user’s phone number. To perform this operation use `createWithPhoneNumber` method on `Cash Deposit` service. `phoneNumber`, `amount` and `merchantReference` should be provided.

### CashDepositToPhoneOptions

`CashDepositToPhoneOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                        |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| phoneNumber       | string   | Gets or sets phone number. The mobile phone number registered in the Papara account of the user to be loaded with cash.                                                                                                                                                                                                |
| amount            | float    | Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number.                                                                                             |
| merchantReference | string   | Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions. If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail. MerchantReference sent with failed requests can be resubmitted. |

### Service Method

#### Purpose

Creates a cash deposit request using end users's phone number.

| **Method**            | **Params**                | **Return Type**           |
| --------------------- | ------------------------- | ------------------------- |
| createWithPhoneNumber | CashDepositToPhoneOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Creates a cash deposit request using end users's phone number.
   *
   * @param CashDepositToPhoneOptions $options
   * @return PaparaResult
   */
  public function createWithPhoneNumber()
  {
    $cashDepositToPhoneOptions = new CashDepositToPhoneOptions;
    $cashDepositToPhoneOptions->amount = 10;
    $cashDepositToPhoneOptions->merchantReference = uniqid();
    $cashDepositToPhoneOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->CashDepositService->createWithPhoneNumber($cashDepositToPhoneOptions);
    return $result;
  }
```

## Create Cash Deposit With Account Number

Deposits money to the user with Papara number from the physical point. To perform this operation use `createWithAccountNumber` on `Cash Deposit` service. `accountNumber`, `amount` and `merchantReference` should be provided.

### CashDepositToAccountNumberOptions

`CashDepositToAccountNumberOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                        |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| accountNumber     | int      | Gets or sets account number. Papara account number of the user who will be loaded with cash.                                                                                                                                                                                                                           |
| amount            | float    | Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number.                                                                                             |
| merchantReference | string   | Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions. If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail. MerchantReference sent with failed requests can be resubmitted. |

### Service Method

#### Purpose

Creates a cash deposit request using end user's account number.

| **Method**              | **Params**                        | **Return Type**           |
| ----------------------- | --------------------------------- | ------------------------- |
| createWithAccountNumber | CashDepositToAccountNumberOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Creates a cash deposit request using end user's account number.
   *
   * @param CashDepositToAccountNumberOptions $options
   * @return PaparaResult
   */
  public function createWithAccountNumber()
  {
    $cashDepositToAccountNumberOptions = new CashDepositToAccountNumberOptions;
    $cashDepositToAccountNumberOptions->amount = 10;
    $cashDepositToAccountNumberOptions->merchantReference = uniqid();
    $cashDepositToAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->CashDepositService->createWithAccountNumber($cashDepositToAccountNumberOptions);
    return $result;
  }
```

## Create Cash Deposit With National Identity Number

Deposits money to the user with national identity number registered in Papara from the physical point. To perform this operation use `createWithTckn` on `Cash Deposit` service. `tckn`, `amount` and `merchantReference` should be provided.

### CashDepositToTcknOptions

`CashDepositToTcknOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                       |
| ----------------- | -------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| tckn              | long     | Gets or sets national identity number which is linked to user's Papara account                                                                                                                                                                                                                                        |
| amount            | float    | Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number                                                                                             |
| merchantReference | string   | Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions. If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail. MerchantReference sent with failed requests can be resubmitted |

### Service Method

#### Purpose

Creates a cash deposit request using end users's national identity number.

| **Method**     | **Params**               | **Return Type**           |
| -------------- | ------------------------ | ------------------------- |
| createWithTckn | CashDepositToTcknOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Creates a cash deposit request using end users's national identity number.
   *
   * @param CashDepositToTcknOptions $options
   * @return PaparaResult
   */
  public function createWithTckn()
  {
    $cashDepositToTcknOptions = new CashDepositToTcknOptions;
    $cashDepositToTcknOptions->amount = 10;
    $cashDepositToTcknOptions->merchantReference = uniqid();
    $cashDepositToTcknOptions->tckn = $this->config['TCKN'];

    $result = $this->client->CashDepositService->createWithTckn($cashDepositToTcknOptions);
    return $result;
  }
```

## Create Cash Deposit Provision Control With National Identity Number

Creates a request to deposit money from the physical point using national identity number registered in Papara without provision. To perform this operation use `createProvisionWithTcknControl` on `Cash Deposit` service. `phoneNumber`, `tckn`, `amount` and `merchantReference` should be provided.

### CashDepositProvision Model

`CashDepositProvision` is used by cash deposit service to match returning cash deposit provision values from API.

| **Variable Name** | **Type** | **Description**                      |
| ----------------- | -------- | ------------------------------------ |
| Id                | int      | Returns cash deposit ID              |
| CreatedAt         | DateTime | Returns created date of cash deposit |
| Amount            | float    | Amount                               |
| Currency          | int      | Currency                             |
| MerchantReference | string   | Returns merchant reference code      |
| UserFullName      | string   | Returns end user's full name         |

### CashDepositTcknControlOptions

`CashDepositTcknControlOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                        |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| phoneNumber       | string   | Gets or sets user's phone number. The phone number of the user to be sent money, including the country code and "+".                                                                                                                                                                                                   |
| tckn              | int      | Gets or sets national identity number which is linked to user's Papara account                                                                                                                                                                                                                                         |
| amount            | float    | Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number.                                                                                             |
| merchantReference | string   | Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions. If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail. MerchantReference sent with failed requests can be resubmitted. |

### Service Method

#### Purpose

Creates a cash deposit request without upfront payment using end user's national identity number.

| **Method**                     | **Params**                    | **Return Type**                    |
| ------------------------------ | ----------------------------- | ---------------------------------- |
| createProvisionWithTcknControl | CashDepositTcknControlOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Creates a cash deposit request without upfront payment using end user's national identity number.
   *
   * @param CashDepositTcknControlOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithTcknControl()
  {
    $cashDepositTcknControlOptions = new CashDepositTcknControlOptions;
    $cashDepositTcknControlOptions->amount = 10;
    $cashDepositTcknControlOptions->merchantReference = uniqid();
    $cashDepositTcknControlOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $cashDepositTcknControlOptions->tckn = $this->config['TCKN'];

    $result = $this->client->CashDepositService->createProvisionWithTcknControl($cashDepositTcknControlOptions);
    return $result;
  }
```

## Create Cash Deposit Provision With National Identity Number

Creates a request to deposit money from the physical point using national identity number registered in Papara without provision. To perform this operation use `createProvisionWithTckn` on `Cash Deposit` service. `phoneNumber`, `tckn`, `amount` and `merchantReference` should be provided.

### CashDepositProvision Model

`CashDepositProvision` is used by cash deposit service to match returning cash deposit provision values from API.

| **Variable Name** | **Type** | **Description**                      |
| ----------------- | -------- | ------------------------------------ |
| Id                | int      | Returns cash deposit ID              |
| CreatedAt         | DateTime | Returns created date of cash deposit |
| Amount            | float    | Amount                               |
| Currency          | int      | Currency                             |
| MerchantReference | string   | Returns merchant reference code      |
| UserFullName      | string   | Returns end user's full name         |

### CashDepositTcknControlOptions

`CashDepositTcknControlOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                        |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| phoneNumber       | string   | Gets or sets user's phone number. The phone number of the user to be sent money, including the country code and "+".                                                                                                                                                                                                   |
| tckn              | int      | Gets or sets national identity number which is linked to user's Papara account                                                                                                                                                                                                                                         |
| amount            | float    | Gets or sets amount. The amount of the cash deposit. This amount will be transferred to the account of the user who received the payment. The amount to be deducted from the merchant account will be exactly this number.                                                                                             |
| merchantReference | string   | Gets or sets merchant reference. The unique value sent by the merchant to prevent false repetitions in cash loading transactions. If a previously submitted and successful merchantReference is resubmitted with a new request, the request will fail. MerchantReference sent with failed requests can be resubmitted. |

### Service Method

#### Purpose

Creates a cash deposit request without upfront payment using end user's national identity number.

| **Method**              | **Params**                    | **Return Type**                    |
| ----------------------- | ----------------------------- | ---------------------------------- |
| createProvisionWithTckn | CashDepositTcknControlOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Creates a cash deposit request without upfront payment using end user's national identity number.
   *
   * @param CashDepositTcknControlOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithTckn()
  {
    $cashDepositTcknControlOptions = new CashDepositTcknControlOptions;
    $cashDepositTcknControlOptions->amount = 10;
    $cashDepositTcknControlOptions->merchantReference = uniqid();
    $cashDepositTcknControlOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $cashDepositTcknControlOptions->tckn = $this->config['TCKN'];

    $result = $this->client->CashDepositService->createProvisionWithTckn($cashDepositTcknControlOptions);
    return $result;
  }
```

## Create Cash Deposit Provision With Phone Number

Creates a request to deposit money from the physical point using phone number registered in Papara without provision. To perform this operation use `createProvisionWithPhoneNumber` on `Cash Deposit` service. `phoneNumber`, `amount` and `merchantReference` should be provided.

### Service Method

#### Purpose

Creates a cash deposit request without upfront payment using end users's phone number.

| **Method**                     | **Params**                | **Return Type**                    |
| ------------------------------ | ------------------------- | ---------------------------------- |
| CreateProvisionWithPhoneNumber | CashDepositToPhoneOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Creates a cash deposit request without upfront payment using end users's phone number.
   *
   * @param CashDepositToPhoneOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithPhoneNumber()
  {
    $cashDepositToPhoneOptions = new CashDepositToPhoneOptions;
    $cashDepositToPhoneOptions->amount = 10;
    $cashDepositToPhoneOptions->merchantReference = uniqid();
    $cashDepositToPhoneOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->CashDepositService->createProvisionWithPhoneNumber($cashDepositToPhoneOptions);
    return $result;
  }
```

## Create Cash Deposit Provision With Account Number

Creates a request to deposit money from the physical point using Papara number without provision. To perform this operation use `createProvisionWithAccountNumber` on `Cash Deposit` service. `accountNumber`, `amount` and `merchantReference` should be provided.

### Service Method

#### Purpose

Creates a cash deposit request without upfront payment using end users's phone number.

| **Method**                       | **Params**                        | **Return Type**                    |
| -------------------------------- | --------------------------------- | ---------------------------------- |
| createProvisionWithAccountNumber | CashDepositToAccountNumberOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Creates a cash deposit request without upfront payment using merchant's account number.
   *
   * @param CashDepositToAccountNumberOptions $options
   * @return PaparaResult
   */
  public function createProvisionWithAccountNumber()
  {
    $cashDepositToAccountNumberOptions = new CashDepositToAccountNumberOptions;
    $cashDepositToAccountNumberOptions->amount = 10;
    $cashDepositToAccountNumberOptions->merchantReference = uniqid();
    $cashDepositToAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->CashDepositService->createProvisionWithAccountNumber($cashDepositToAccountNumberOptions);

    return $result;
  }
```

## Cash Deposit Provision Control By Reference Code

With the reference code created by the user, it checks the deposit request without prepayment from the physical point and makes it ready to be approved. To perform this operation, use `controlProvisionByReference` on `Cash Deposit` service. `referenceCode` and `amount` should be provided.

### Service Method

#### Purpose

Makes a cash deposit request ready to be completed without upfront payment.

| **Method**                  | **Params**                | **Return Type**                    |
| --------------------------- | ------------------------- | ---------------------------------- |
| controlProvisionByReference | CashDepositControlOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Makes a cash deposit ready to be complete
   *
   * @param CashDepositControlOptions $options
   * @return PaparaResult
   */
  public function controlProvisionByReference()
  {
    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->controlProvisionByReference($options);

    return $result;
  }
```

## Complete Cash Deposit Provision By Reference Code

With the reference code created by the user, it approves the deposit request without prepayment from the physical point and transfers the balance to the user. To perform this operation, use `completeProvisionByReference` on `Cash Deposit` service. `referenceCode` and `amount` should be provided.

### Service Method

#### Purpose

Completes a cash deposit request without upfront payment.

| **Method**                   | **Params**                | **Return Type**                    |
| ---------------------------- | ------------------------- | ---------------------------------- |
| completeProvisionByReference | CashDepositControlOptions | PaparaResult<CashDepositProvision> |

#### Usage

```php
  /**
   * Completes a cash deposit request without upfront payment
   *
   * @param CashDepositControlOptions $options
   * @return PaparaResult
   */
  public function completeProvisionByReference()
  {
    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->completeProvisionByReference($options);

    return $result;
  }
```

## Cash Deposit Completion

Confirms the deposit request created from the physical point to the user without prepayment. To perform this operation, use `completeProvision` on `Cash Deposit` service. `id` and `transactionDate` should be provided.

### CashDepositCompleteOptions

`CashDepositCompleteOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                               |
| ----------------- | -------- | --------------------------------------------- |
| id                | int      | Gets or sets ID of cash deposit request       |
| transactionDate   | DateTime | Gets or sets date of cash deposit transaction |

### Service Method

#### Purpose

Completes a cash deposit request without upfront payment.

| **Method**        | **Params**                 | **Return Type**           |
| ----------------- | -------------------------- | ------------------------- |
| completeProvision | CashDepositCompleteOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Completes a cash deposit request without upfront payment.
   *
   * @param CashDepositCompleteOptions $options
   * @return PaparaResult
   */
  public function completeProvision()
  {
    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);
    return $result;
  }
```

## Get Cash Deposit By Date

Retrieves information of money deposits from the physical point. To perform this operation, use `getCashDepositByDate` on `Cash Deposit` service. `startDate`, `endDate`, `pageIndex` and `pageItemCount` should be provided.

### CashDepositByDateOptions

`CashDepositByDateOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                           |
| ----------------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| startDate         | DateTime | Gets or sets start date of cash deposit                                                                                                                                                                                                   |
| endDate           | DateTime | Gets or sets end date of cash deposit                                                                                                                                                                                                     |
| pageIndex         | int      | Gets or sets page index. It is the index number of the page that is wanted to display from the pages calculated on the basis of the number of records (pageItemCount) desired to be displayed on a page. Note: the first page is always 1 |
| pageItemCount     | int      | Gets or sets page item count. The number of records that are desired to be displayed on a page                                                                                                                                            |

### Service Method

#### Purpose

Returns a cash deposit information by given date.

| **Method**           | **Params**               | **Return Type**           |
| -------------------- | ------------------------ | ------------------------- |
| getCashDepositByDate | CashDepositByDateOptions | PaparaResult<CashDeposit> |

#### Usage

```php
  /**
   * Returns a cash deposit information by given date.
   *
   * @param CashDepositByDateOptions $options
   * @return PaparaResult
   */
  public function getCashDepositByDate()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositByDateOptions = new CashDepositByDateOptions;
    $cashDepositByDateOptions->startDate = $startDate->format('c');
    $cashDepositByDateOptions->endDate = $endDate->format('c');
    $cashDepositByDateOptions->pageIndex = 1;
    $cashDepositByDateOptions->pageItemCount = 20;

    $result = $this->client->CashDepositService->getCashDepositByDate($cashDepositByDateOptions);
    return $result;
  }
```

## Settlements

Returns the total number and volume of transactions performed within the given dates. Both start and end dates are included in the calculation. To perform this operation, use `settlements` on `Cash Deposit` service. `startDate` and `endDate` should be provided.

### CashDepositSettlementOptions

`CashDepositSettlementOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type**   | **Description**                        |
| ----------------- | ---------- | -------------------------------------- |
| startDate         | DateTime   | Gets or sets start date for settlement |
| endDate           | DateTime   | Gets or sets end date for settlement   |
| entryType         | EntryType? | Gets or sets entry type for settlement |

### Service Method

#### Purpose

Returns total transaction volume and count between given dates. Start and end dates are included.

| **Method**  | **Params**                   | **Return Type**                     |
| ----------- | ---------------------------- | ----------------------------------- |
| settlements | CashDepositSettlementOptions | PaparaResult<CashDepositSettlement> |

#### Usage

```php
  /**
   * Returns total transaction volume and count between given dates. Start and end dates are included.
   *
   * @param CashDepositSettlementOptions $options
   * @return PaparaResult
   */
  public function provisionSettlements()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositSettlementOptions = new CashDepositSettlementOptions;
    $cashDepositSettlementOptions->startDate = $startDate->format('c');
    $cashDepositSettlementOptions->endDate = $endDate->format('c');
    $cashDepositSettlementOptions->entryType = 1;

    $result = $this->client->CashDepositService->settlements($cashDepositSettlementOptions);
    return $result;
  }
```

## Provision Settlements

Returns the total number and volume of transactions performed within the given dates. Both start and end dates are included in the calculation. To perform this operation, use `provisionSettlements` on `Cash Deposit` service. `startDate` and `endDate` should be provided.

### CashDepositSettlementOptions

`CashDepositSettlementOptions` is used by cash deposit service for providing request parameters.

| **Variable Name** | **Type**   | **Description**                        |
| ----------------- | ---------- | -------------------------------------- |
| startDate         | DateTime   | Gets or sets start date for settlement |
| endDate           | DateTime   | Gets or sets end date for settlement   |
| entryType         | EntryType? | Gets or sets entry type for settlement |

### Service Method

#### Purpose

Returns total transaction volume and count between given dates. Start and end dates are included.

| **Method**           | **Params**                   | **Return Type**                     |
| -------------------- | ---------------------------- | ----------------------------------- |
| provisionSettlements | CashDepositSettlementOptions | PaparaResult<CashDepositSettlement> |

#### Usage

```php
  /**
   * Returns total transaction volume and count between given dates. Start and end dates are included.
   *
   * @param CashDepositSettlementOptions $options
   * @return PaparaResult
   */
  public function provisionSettlements()
  {
    $startDate = new DateTime('2020-01-01');
    $endDate = new DateTime('2020-09-01');

    $cashDepositSettlementOptions = new CashDepositSettlementOptions;
    $cashDepositSettlementOptions->startDate = $startDate->format('c');
    $cashDepositSettlementOptions->endDate = $endDate->format('c');
    $cashDepositSettlementOptions->entryType = 1;

    $result = $this->client->CashDepositService->provisionSettlements($cashDepositSettlementOptions);
    return $result;
  }
```

## Possible Errors and Error Codes

| **Error Code** | **Error Description**                                                                                                     |
| -------------- | ------------------------------------------------------------------------------------------------------------------------- |
| 100            | User not found.                                                                                                           |
| 101            | Merchant information could not found.                                                                                     |
| 105            | Insufficient funds.                                                                                                       |
| 107            | The user exceeds the balance limit with this transaction.                                                                 |
| 111            | The user exceeds the monthly transaction limit with this transaction                                                      |
| 112            | An amount has been sent below the minimum deposit limit.                                                                  |
| 203            | The user account is blocked.                                                                                              |
| 997            | The authorization to make a cash deposit is not defined in your account. You should contact your customer representative. |
| 998            | The parameters you submitted are not in the expected format. Example: one of the mandatory fields is not provided.        |
| 999            | An error occurred in the Papara system.                                                                                   |

# <a name="mass-payment">Mass Payment</a>

This part is the technical integration statement prepared for merchants those want to distribute payments to their users quickly, safely and widely through Papara.

## Get Mass Payment

Returns information about the payment distribution process. To perform this operation use `getMassPayment` method on `MassPayment` service. `id` should be provided.

### Mass Payment Model

`MassPayment` is used by mass payment service to match returning mass payment values from API.

| **Variable Name** | **Type** | **Description**                                   |
| ----------------- | -------- | ------------------------------------------------- |
| massPaymentId     | string   | Returns mass payment ID                           |
| id                | int?     | Returns ID which is created after payment is done |
| createdAt         | DateTime | Returns created date                              |
| amount            | float    | Returns amount of payment                         |
| currency          | int?     | Returns currency. Values are “0”, “1”, “2”, “3”   |
| fee               | float    | Returns fee                                       |
| resultingBalance  | float    | Returns resulting balance                         |
| description       | string   | Returns description                               |

### MassPaymentGetOptions

`MassPaymentGetOptions` is used by mass payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**              |
| ----------------- | -------- | ---------------------------- |
| id                | long     | Gets or sets mass payment ID |

### Service Method

#### Purpose

Returns mass payment information for authorized merchant.

| **Method**     | **Params**            | **Return Type**           |
| -------------- | --------------------- | ------------------------- |
| getMassPayment | MassPaymentGetOptions | PaparaResult<MassPayment> |

#### Usage

```php
  /**
   * Returns mass payment information for authorized merchant.
   *
   * @param MassPaymentGetOptions $options
   * @return PaparaResult
   */
  public function getMassPayment()
  {
    $getMassPaymentGetOptions = new MassPaymentGetOptions;
    $getMassPaymentGetOptions->id = "MASS_PAYMENT_ID";

    $result = $this->client->MassPaymentService->getMassPayment($getMassPaymentGetOptions);
    return $result;
  }
```

## Get Mass Payment By Mass Payment Reference Number

Returns mass payment using mass payment reference number To perform this operation use `getMassPaymentByReference` method on `MassPayment` service. `reference` should be provided.

### Mass Payment Model

`MassPayment` is used by mass payment service to match returning mass payment values from API.

### MassPaymentByReferenceOptions

`MassPaymentByReferenceOptions` is used by mass payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**              |
| ----------------- | -------- | ---------------------------- |
| reference         | string   | Gets or sets mass payment ID |

### Service Method

#### Purpose

Returns mass payment information for authorized merchant.

| **Method**                | **Params**                    | **Return Type**           |
| ------------------------- | ----------------------------- | ------------------------- |
| getMassPaymentByReference | MassPaymentByReferenceOptions | PaparaResult<MassPayment> |

#### Usage

```php
  /**
   * Returns mass payment information for authorized merchant.
   *
   * @param MassPaymentGetOptions $options
   * @return PaparaResult
   */
  public function getMassPaymentByReference()
  {
    $getMassPaymentByReferenceOptions = new MassPaymentByReferenceOptions;
    $getMassPaymentByReferenceOptions->reference = "MASS_PAYMENT_REFERENCE";

    $result = $this->client->MassPaymentService->getMassPaymentByReference($getMassPaymentByReferenceOptions);
    return $result;
  }
```

## Create Mass Payment To Account Number

Send money to Papara number. To perform this operation use `createMassPaymentWithAccountNumber` method on `MassPayment` service. `accountNumber`, `amount` and `massPaymentId` should be provided.

### MassPaymentToPaparaNumberOptions

`MassPaymentToPaparaNumberOptions` is used by mass payment service for providing request parameters.

| **Variable Name**  | **Type** | **Description**                                                                                                                                                                                                                                                                                                                                            |
| ------------------ | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| accountNumber      | string   | Gets or sets Papara account number. The 10-digit Papara number of the user who will receive the payment. It can be in the format 1234567890 or PL1234567890. Before the Papara version transition, the Papara number was called the wallet number.Old wallet numbers have been changed to Papara number. Payment can be distributed to old wallet numbers. |
| parseAccountNumber | int?     | Gets or sets parse account number. Parses the account number to long type. In old papara integrations, account / wallet number was made by starting with PL. The service was written in such a way that it accepts numbers starting with PL, in order not to cause problems to the member merchants that receive the papara number from their users.       |
| amount             | float    | Gets or sets amount. The amount of the payment transaction. This amount will be transferred to the account of the user who received the payment. This figure plus transaction fee will be charged to the merchant account.                                                                                                                                 |
| massPaymentId      | string   | Gets or sets mass payment ID. Unique value sent by merchant to prevent erroneous repetition in payment transactions. If a massPaymentId that was sent previously and succeeded is sent again with a new request, the request will fail.                                                                                                                    |
| turkishNationalId  | long     | Gets or sets national identity number.It provides the control of the identity information sent by the user who will receive the payment, in the Papara system. In case of a conflict of credentials, the transaction will not take place.                                                                                                                  |
| description        | string   | Gets or sets description. Description of the transaction provided by the merchant. It is not a required field. If sent, the customer sees in the transaction descriptions.                                                                                                                                                                                 |

### Service Method

#### Purpose

Creates a mass payment to given account number for authorized merchant.

| **Method**                         | **Params**                       | **Return Type**           |
| ---------------------------------- | -------------------------------- | ------------------------- |
| createMassPaymentWithAccountNumber | MassPaymentToPaparaNumberOptions | PaparaResult<MassPayment> |

#### Usage

```php
  /**
   * Creates a mass payment to given account number for authorized merchant.
   *
   * @param MassPaymentToPaparaNumberOptions $options
   * @return PaparaResult
   */
  public function createMassPaymentWithAccountNumber()
  {
    $massPaymentToPaparaNumberOptions = new MassPaymentToPaparaNumberOptions;
    $massPaymentToPaparaNumberOptions->accountNumber= $this->config['PersonalAccountNumber'];
    $massPaymentToPaparaNumberOptions->amount= 1;
    $massPaymentToPaparaNumberOptions->description= 'Php Unit Test: MassPaymentToPaparaNumber';
    $massPaymentToPaparaNumberOptions->massPaymentId= uniqid();
    $massPaymentToPaparaNumberOptions->parseAccountNumber= 1;
    $massPaymentToPaparaNumberOptions->turkishNationalId= $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithAccountNumber($massPaymentToPaparaNumberOptions);
    return $result;
  }
```

## Create Mass Payment To E-Mail Address

Send money to e-mail address registered in Papara. To perform this operation use `createMassPaymentWithEmail` method on `MassPayment` service. `email`, `amount` and `massPaymentId` should be provided.

### MassPaymentToEmailOptions

`MassPaymentToEmailOptions` is used by mass payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                          |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| email             | string   | Gets or sets e-mail address. Registered email address of the user receiving the payment.                                                                                                                                                 |
| amount            | float    | Gets or sets amount. The amount of the payment transaction. This amount will be transferred to the account of the user who received the payment. This figure plus transaction fee will be charged to the merchant account.               |
| massPaymentId     | string   | Gets or setsmass payment ID. Unique value sent by merchant to prevent erroneous repetition in payment transactions. If a massPaymentId that was sent previously and succeeded is sent again with a new request, the request will fail.   |
| turkishNationalId | long     | Gets or setsnational identity number.It provides the control of the identity information sent by the user who will receive the payment, in the Papara system. In case of a conflict of credentials, the transaction will not take place. |
| description       | string   | Gets or sets description. Description of the transaction provided by the merchant. It is not a required field. If sent, the customer sees in the transaction descriptions.                                                               |

### Service Method

#### Purpose

Creates a mass payment to given e-mail address for authorized merchant.

| **Method**                 | **Params**                | **Return Type**           |
| -------------------------- | ------------------------- | ------------------------- |
| createMassPaymentWithEmail | MassPaymentToEmailOptions | PaparaResult<MassPayment> |

#### Usage

```php
  /**
   * Creates a mass payment to given e-mail address for authorized merchant.
   *
   * @param MassPaymentToEmailOptions $options
   * @return void
   */
  public function createMassPaymentWithEmail()
  {
    $massPaymentToEmailOptions = new MassPaymentToEmailOptions;
    $massPaymentToEmailOptions->amount = 1;
    $massPaymentToEmailOptions->description = 'Php Unit Test: MassPaymentToEmail';
    $massPaymentToEmailOptions->massPaymentId = uniqid();
    $massPaymentToEmailOptions->email = $this->config['PersonalEmail'];
    $massPaymentToEmailOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithEmail($massPaymentToEmailOptions);
    return $result;
  }
```

## Create Mass Payment To Phone Number

Send money to phone number registered in Papara. To perform this operation use `createMassPaymentWithPhoneNumber` method on `MassPayment` service. `phoneNumber`, `amount` and `massPaymentId` should be provided.

### MassPaymentToPhoneNumberOptions

`MassPaymentToPhoneNumberOptions` is used by mass payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                          |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| phoneNumber       | string   | Gets or sets user's phone number. The mobile number of the user who will receive the payment, registered in Papara. It should contain a country code and start with +                                                                    |
| amount            | float    | Gets or sets amount. The amount of the payment transaction. This amount will be transferred to the account of the user who received the payment. This figure plus transaction fee will be charged to the merchant account                |
| massPaymentId     | string   | Gets or sets mass payment ID. Unique value sent by merchant to prevent erroneous repetition in payment transactions. If a MassPaymentId that was sent previously and succeeded is sent again with a new request, the request will fail   |
| turkishNationalId | long     | Gets or sets national identity number.It provides the control of the identity information sent by the user who will receive the payment, in the Papara system. In case of a conflict of credentials, the transaction will not take place |
| description       | string   | Gets or sets description. Description of the transaction provided by the merchant. It is not a required field. If sent, the customer sees in the transaction descriptions                                                                |

### Service Method

#### Purpose

Creates a mass payment to given phone number for authorized merchant.

| **Method**                       | **Params**                      | **Return Type**           |
| -------------------------------- | ------------------------------- | ------------------------- |
| createMassPaymentWithPhoneNumber | MassPaymentToPhoneNumberOptions | PaparaResult<MassPayment> |

#### Usage

```php
  /**
   * Creates a mass payment to given phone number for authorized merchant.
   *
   * @param MassPaymentToPhoneNumberOptions $options
   * @return void
   */
  public function createMassPaymentWithPhoneNumber()
  {
    $massPaymentToPhoneNumberOptions = new MassPaymentToPhoneNumberOptions;
    $massPaymentToPhoneNumberOptions->amount = 1;
    $massPaymentToPhoneNumberOptions->description = 'Php Unit Test: MassPaymentToEmail';
    $massPaymentToPhoneNumberOptions->massPaymentId = uniqid();
    $massPaymentToPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];
    $massPaymentToPhoneNumberOptions->turkishNationalId = $this->config['TCKN'];

    $result = $this->client->MassPaymentService->createMassPaymentWithPhoneNumber($massPaymentToPhoneNumberOptions);
    return $result;
  }
```

## Possible Errors and Error Codes

| **Error Code** | **Error Description**                                                                                                                                                            |
| -------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 100            | User not found.                                                                                                                                                                  |
| 105            | Insufficient funds                                                                                                                                                               |
| 107            | Receiver exceeds balance limit. The highest possible balance for simple accounts is 750 TL.                                                                                      |
| 111            | Receiver exceeds monthly transaction limit. Simple accounts can receive payments from a total of 2000 TL of defined resources per month.                                         |
| 133            | MassPaymentID was used recently.                                                                                                                                                 |
| 997            | You are not authorized to distribute payments. You can contact your customer representative and request a payment distribution definition to your merchant account.              |
| 998            | The parameters you submitted are not in the expected format. Example: Customer number less than 10 digits. In this case, the error message contains details of the format error. |
| 999            | An error occurred in the Papara system.                                                                                                                                          |

# <a name="payments">Payments</a>

Payment service will be used for getting, creating or listing payments and refunding. Before showing the payment button to users, the merchant must create a payment transaction on Papara. Payment records are time dependent. Transaction records that are not completed and paid by the end user are deleted from Papara system after 1 hour. Completed payment records are never deleted and can always be queried with the API.

## Get Payment

Returns payment information. To perform this operation use `getPayment` method on `Payment` service. `id` should be provided.

### Payment Model

`Payment` is used by payment service to match returning payment values from API.

| **Variable Name**        | **Type** | **Description**                                                                                                                                                                                                                   |
| ------------------------ | -------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| merchant                 | Account  | Returns merhcant                                                                                                                                                                                                                  |
| id                       | string   | Returns ID                                                                                                                                                                                                                        |
| CreatedAt                | DateTime | Returns created date                                                                                                                                                                                                              |
| merchantId               | string   | Returns merchant ID                                                                                                                                                                                                               |
| userId                   | string   | Returns user ID                                                                                                                                                                                                                   |
| paymentMethod            | int      | Returns payment Method. 0 - User completed transaction with existing Papara balance 1 - User completed the transaction with a debit / credit card that was previously defined. 2 - User completed transaction via mobile payment. |
| paymentMethodDescription | string   | Returns payment method description                                                                                                                                                                                                |
| referenceId              | string   | Returns referance ID                                                                                                                                                                                                              |
| orderDescription         | string   | Returns order description                                                                                                                                                                                                         |
| status                   | int      | Returns status. 0 - Awaiting, payment is not done yet. 1 - Payment is done, transaction is completed. 2 - Transactions is refunded by merchant.                                                                                   |
| statusDescription        | string   | Returns status description                                                                                                                                                                                                        |
| amount                   | float    | Returns amount                                                                                                                                                                                                                    |
| fee                      | float    | Returns fee                                                                                                                                                                                                                       |
| currency                 | int      | Returns currency. Values are “0”, “1”, “2”, “3”                                                                                                                                                                                   |
| notificationUrl          | string   | Returns notification URL                                                                                                                                                                                                          |
| notificationDone         | bool     | Returns if notification was made                                                                                                                                                                                                  |
| redirectUrl              | string   | Returns redirect URL                                                                                                                                                                                                              |
| raymentUrl               | string   | Returns payment URL                                                                                                                                                                                                               |
| merchantSecretKey        | string   | Returns merchant secret key                                                                                                                                                                                                       |
| returningRedirectUrl     | string   | Returns returning Redirect URL                                                                                                                                                                                                    |
| turkishNationalId        | long     | Returns national identity number                                                                                                                                                                                                  |

### PaymentGetOptions

`PaymentGetOptions` will be used as parameter while acquiring payment information.

| **Variable Name** | **Type** | **Description**                |
| ----------------- | -------- | ------------------------------ |
| id                | string   | Gets or sets unique payment ID |

### Service Method

#### Purpose

Returns payment and balance information for authorized merchant.

| **Method** | **Params**        | **Return Type**       |
| ---------- | ----------------- | --------------------- |
| getPayment | PaymentGetOptions | PaparaResult<Payment> |

#### Usage

```php
  /**
   * Returns payment and balance information for authorized merchant.
   *
   * @param PaymentGetOptions $options
   * @return PaparaResult
   */
  public function getPayment()
  {
    $paymentGetOptions = new PaymentGetOptions;
    $paymentGetOptions->id = "PAYMENT_ID";

    $result = $this->client->PaymentService->getPayment($paymentGetOptions);
    return $result;
  }
```

## Get Payment By Payment Reference Number

Returns payment information. To perform this operation use `getPayment` method on `getPaymentByReference` service. `referenceId` should be provided.

### PaymentGetByReferenceOptions

`PaymentGetByReferenceOptions` will be used as parameter while acquiring payment information.

| **Variable Name** | **Type** | **Description**                              |
| ----------------- | -------- | -------------------------------------------- |
| referenceId       | string   | Gets or sets unique payment reference number |

### Service Method

#### Purpose

Returns payment and balance information for authorized merchant.

| **Method**            | **Params**                   | **Return Type**       |
| --------------------- | ---------------------------- | --------------------- |
| getPaymentByReference | PaymentGetByReferenceOptions | PaparaResult<Payment> |

#### Usage

```php
  /**
   * Returns payment and balance information for authorized merchant.
   *
   * @param PaymentGetOptions $options
   * @return PaparaResult
   */
  public function getPaymentByReference()
  {
    $paymentByReferenceOptions = new PaymentGetByReferenceOptions;
    $paymentByReferenceOptions->referenceId = "PAYMENT_REFERENCE_NUMBER";

    $result = $this->client->PaymentService->getPaymentByReference($paymentByReferenceOptions);
    return $result;
  }
```

## Create Payment

Creates a new payment record. To perform this operation use `createPayment` method on `Payment` service. `amount`, `referenceId`, `orderDescription`, `notificationUrl` and `redirectUrl` should be provided.

### PaymentCreateOptions

`PaymentCreateOptions` is used by payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| ----------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| amount            | float    | Gets or sets amount. The amount of the payment transaction. Exactly this amount will be taken from the account of the user who made the payment, and this amount will be displayed to the user on the payment screen. Amount field can be minimum 1.00 and maximum 500000.00                                                                                                                                                                                                                                                                                                                                                                               |
| referenceId       | string   | Gets or sets reference ID. Reference information of the payment transaction in the merchant system. The transaction will be returned to the merchant without being changed in the result notifications as it was sent to Papara. Must be no more than 100 characters. This area does not have to be unique and Papara does not make such a check                                                                                                                                                                                                                                                                                                           |
| orderDescription  | string   | Gets or sets order description. Description of the payment transaction. The sent value will be displayed to the user on the Papara checkout page. Having a description that accurately identifies the transaction initiated by the user, will increase the chance of successful payment                                                                                                                                                                                                                                                                                                                                                                    |
| notificationUrl   | string   | Gets or sets notification URL. The URL to which payment notification requests (IPN) will be sent. With this field, the URL where the POST will be sent to the payment merchant must be sent. To the URL sent with "notificationUrl", Papara will send a payment object containing all information of the payment with an HTTP POST request immediately after the payment is completed. If the merchant returns 200 OK to this request, no notification will be made again. If the merchant does not return 200 OK to this notification, Papara will continue to make payment notification (IPN) requests for 24 hours until the merchant returns to 200 OK |
| redirectUrl       | string   | Gets or sets redirect URL. URL to which the user will be redirected at the end of the process                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| turkishNationalId | long     | Gets or sets national identity number.It provides the control of the identity information sent by the user who will receive the payment, in the Papara system. In case of a conflict of credentials, the transaction will not take place                                                                                                                                                                                                                                                                                                                                                                                                                   |

### Service Method

#### Purpose

Creates a payment for authorized merchant.

| **Method**    | **Params**           | **Return Type**       |
| ------------- | -------------------- | --------------------- |
| createPayment | PaymentCreateOptions | PaparaResult<Payment> |

#### Usage

```php
  /**
   * Creates a payment for authorized merchant.
   *
   * @param PaymentCreateOptions $options
   * @return PaparaResult
   */
  public function createPayment()
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
    return $result;
  }
```

## Refund

Refunds a completed payment of the merchant with the provided payment ID .To perform this operation use `refund` method on `Payment` service. `id` should be provided.

### PaymentRefundOptions

`PaymentRefundOptions` is used by payment service for providing request parameters.

| **Variable Name** | **Type** | **Description**         |
| ----------------- | -------- | ----------------------- |
| id                | string   | Gets or sets payment ID |

### Service Method

#### Purpose

Creates a refund for a completed payment for authorized merchant.

| **Method** | **Params**           | **Return Type** |
| ---------- | -------------------- | --------------- |
| refund     | PaymentRefundOptions | PaparaResult    |

#### Usage

```php
  /**
   * Creates a refund for a completed payment for authorized merchant.
   *
   * @param PaymentRefundOptions $options
   * @return PaparaResult
   */
  public function refund()
  {
    $paymentRefundOptions = new PaymentRefundOptions;
    $paymentRefundOptions->id = "PAYMENT_ID";

    $result = $this->client->PaymentService->refund($paymentRefundOptions);
    return $result;
  }
```

## List Payments

Lists the completed payments of the merchant in a sequential order. To perform this operation use `list` method on `Payment` service. `pageIndex` and `pageItemCount` should be provided.

### PaymentListOptions

`PaymentListOptions` is used by payment service for providing request parameters

| **Variable Name** | **Type** | **Description**                                                                                                                                                                                                                           |
| ----------------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| pageIndex         | int      | Gets or sets page index. It is the index number of the page that is wanted to display from the pages calculated on the basis of the number of records (pageItemCount) desired to be displayed on a page. Note: the first page is always 1 |
| pageItemCount     | Int      | Gets or sets page item count. The number of records that are desired to be displayed on a page                                                                                                                                            |

### PaymentListItem

`PaymentListItem` is used by payment service to match returning completed payment list values list API.

| **Variable Name**        | **Type** | **Description**                                                                                                                                                                                                                   |
| ------------------------ | -------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Id                       | string   | Returns payment ID                                                                                                                                                                                                                |
| CreatedAt                | DateTime | Returns created date                                                                                                                                                                                                              |
| MerchantId               | string   | Returns merchant ID                                                                                                                                                                                                               |
| UserId                   | string   | Returns user ID                                                                                                                                                                                                                   |
| PaymentMethod            | int      | Returns payment Method. 0 - User completed transaction with existing Papara balance 1 - User completed the transaction with a debit / credit card that was previously defined. 2 - User completed transaction via mobile payment. |
| PaymentMethodDescription | string   | Returns payment method description                                                                                                                                                                                                |
| ReferenceId              | string   | Returns reference ID                                                                                                                                                                                                              |
| OrderDescription         | string   | Returns order description                                                                                                                                                                                                         |
| Status                   | int      | Returns status. 0 - Awaiting, payment is not done yet. 1 - Payment is done, transaction is completed. 2 - Transactions is refunded by merchant.                                                                                   |
| StatusDescription        | string   | Returns status description                                                                                                                                                                                                        |
| Amount                   | float    | Returns amount                                                                                                                                                                                                                    |
| Fee                      | float    | Returns fee                                                                                                                                                                                                                       |
| Currency                 | int      | Returns currency. Values are “0”, “1”, “2”, “3”                                                                                                                                                                                   |
| NotificationUrl          | string   | Returns notification URL                                                                                                                                                                                                          |
| NotificationDone         | bool     | Returns if notification was made                                                                                                                                                                                                  |
| RedirectUrl              | string   | Returns redirect URL                                                                                                                                                                                                              |
| PaymentUrl               | string   | Returns payment URL                                                                                                                                                                                                               |
| MerchantSecretKey        | string   | Returns merchant secret key                                                                                                                                                                                                       |
| ReturningRedirectUrl     | string   | Returns returning Redirect URL                                                                                                                                                                                                    |
| TurkishNationalId        | long     | Returns national identity number                                                                                                                                                                                                  |

### Service Method

#### Purpose

Returns a list of completed payments sorted by newest to oldest for authorized merchant.

| **Method** | **Params**         | **Return Type**               |
| ---------- | ------------------ | ----------------------------- |
| list       | PaymentListOptions | PaparaResult<PaymentListItem> |

#### Usage

```php
  /**
   * Returns a list of completed payments sorted by newest to oldest for authorized merchant.
   *
   * @param PaymentListOptions $options
   * @return PaparaResult
   */
  public function list()
  {
    $paymentListOptions = new PaymentListOptions;
    $paymentListOptions->pageIndex = 1;
    $paymentListOptions->pageItemCount = 20;

    $paymentListResult = $this->client->PaymentService->list($paymentListOptions);
    return $result;
  }
```

## Possible Errors and Error Codes

| **Error Code** | **Error Description**                                                                                              |
| -------------- | ------------------------------------------------------------------------------------------------------------------ |
| 997            | You are not authorized to accept payments. You should contact your customer representative.                        |
| 998            | The parameters you submitted are not in the expected format. Example: one of the mandatory fields is not provided. |
| 999            | An error occurred in the Papara system.                                                                            |

# <a name="validation">Validation</a>

Validation service will be used for validating an end user. Validation can be performed by account number, e-mail address, phone number, national identity number.

## Validate By Id

It is used to validate users with Papara UserId. To perform this operation use `validateById` method on `Validation` service. `userId` should be provided.

### Validation Model

`Validation` is used by validation service to match returning user value from API

| **Variable Name** | **Type** | **Description**                       |
| ----------------- | -------- | ------------------------------------- |
| UserId            | string   | Returns unique User ID                |
| FirstName         | string   | Returns user first name               |
| LastName          | string   | Returns user last name                |
| Email             | string   | Returns user e-mail address           |
| PhoneNumber       | string   | Returns user phone number             |
| Tckn              | Long     | Returns user national identity number |
| AccountNumber     | int?     | Returns user account number           |

### ValidationByIdOptions

`ValidationByIdOptions` is used by validation service for providing request parameters.

| **Variable Name** | **Type** | **Description**             |
| :---------------- | -------- | --------------------------- |
| userId            | string   | Gets or sets Papara User ID |

### Service Method

#### Purpose

Returns end user information for validation by given user ID.

| **Method**   | **Params**            | **Return Type**          |
| ------------ | --------------------- | ------------------------ |
| validateById | ValidationByIdOptions | PaparaResult<Validation> |

#### Usage

```php
  /**
   * Returns end user information for validation by given user ID.
   *
   * @param ValidationByIdOptions $options
   * @return PaparaResult
   */
  public function ValidateById()
  {
    $validationByIdOptions = new ValidationByIdOptions;
    $validationByIdOptions->userId = $this->config['PersonalAccountId'];

    $result = $this->client->ValidationService->ValidateById($validationByIdOptions);
    return $result;
  }
```

## Validate By Account Number

It is used to validate users with Papara account number. To perform this operation use `validateByAccountNumber` method on `Validation` service. `accountNumber` should be provided.

### ValidationByAccountNumberOptions

`ValidationByAccountNumberOptions` is used by validation service for providing request parameters

| **Variable Name** | **Type** | **Description**                    |
| ----------------- | -------- | ---------------------------------- |
| accountNumber     | long     | Gets or sets Papara account number |

### Service Method

#### Purpose

Returns end user information for validation by given user account number.

| **Method**              | **Params**                       | **Return Type**          |
| ----------------------- | -------------------------------- | ------------------------ |
| validateByAccountNumber | ValidationByAccountNumberOptions | PaparaResult<Validation> |

#### Usage

```php
  /**
   * Returns end user information for validation by given user account number.
   *
   * @param ValidationByAccountNumberOptions $options
   * @return PaparaResult
   */
  public function ValidateByAccountNumber()
  {
    $validationByAccountNumberOptions = new ValidationByAccountNumberOptions;
    $validationByAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->ValidationService->ValidateByAccountNumber($validationByAccountNumberOptions);
    return $result;
  }
```

## Validate By Phone Number

It is used to validate users with phone number registered in Papara. To perform this operation use `validateByPhoneNumber` method on `Validation` service. `phoneNumber` should be provided.

### ValidationByPhoneNumberOptions

`ValidationByPhoneNumberOptions` is used by validation service for providing request parameters

| **Variable Name** | **Type** | **Description**                                |
| ----------------- | -------- | ---------------------------------------------- |
| phoneNumber       | string   | Gets or sets phone number registered to Papara |

### Service Method

#### Purpose

Returns end user information for validation by given user phone number.

| **Method**            | **Params**                     | **Return Type**                |
| --------------------- | ------------------------------ | ------------------------------ |
| validateByPhoneNumber | ValidationByPhoneNumberOptions | PaparaSingleResult<Validation> |

#### Usage

```php
/**
   * Returns end user information for validation by given phone number.
   *
   * @param ValidationByPhoneNumberOptions $options
   * @return PaparaResult
   */
  public function ValidateByPhoneNumber()
  {
    $validationByPhoneNumberOptions = new ValidationByPhoneNumberOptions;
    $validationByPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->ValidationService->ValidateByPhoneNumber($validationByPhoneNumberOptions);
    return $result;
  }
```

## Validate By E-Mail Address

It is used to validate users with e-mail address registered in Papara. To perform this operation use `validateByEmail` method on `Validation` service. `email` should be provided.

### ValidationByEmailOptions

`ValidationByEmailOptions` is used by validation service for providing request parameters

| **Variable Name** | **Type** | **Description**                                  |
| ----------------- | -------- | ------------------------------------------------ |
| email             | string   | Gets or sets e-mail address registered to Papara |

### Service Method

#### Purpose

Returns end user information for validation by given user e-mail address

| **Method**      | **Params**               | **Return Type**          |
| --------------- | ------------------------ | ------------------------ |
| validateByEmail | ValidationByEmailOptions | PaparaResult<Validation> |

#### Usage

```php
  /**
   * Returns end user information for validation by given user e-mail address.
   *
   * @param ValidationByEmailOptions $options
   * @return PaparaResult
   */
  public function ValidateByEmail()
  {
    $ValidationByEmailOptions = new ValidationByEmailOptions;
    $ValidationByEmailOptions->email = $this->config['PersonalEmail'];

    $result = $this->client->ValidationService->ValidateByEmail($ValidationByEmailOptions);
    return $result;
  }
```

## Validate By National Identity Number

It is used to validate users with national identity number registered in Papara. To perform this operation use `validateByTckn` method on `Validation` service. `tckn` should be provided.

### ValidationByTcknOptions

`ValidationByPhoneNumberOptions` is used by validation service for providing request parameters.

| **Variable Name** | **Type** | **Description**                  |
| ----------------- | -------- | -------------------------------- |
| tckn              | long     | Returns national identity number |

### Service Method

#### Purpose

Returns end user information for validation by given user national identity number

| **Method**     | **Params**              | **Return Type**          |
| -------------- | ----------------------- | ------------------------ |
| validateByTckn | ValidationByTcknOptions | PaparaResult<Validation> |

#### Usage

```php
  /**
   * Returns end user information for validation by given user national identity number.
   *
   * @param ValidationByTcknOptions $options
   * @return PaparaResult
   */
  public function ValidateByTckn()
  {
    $validationByTcknOptions = new ValidationByTcknOptions;
    $validationByTcknOptions->tckn = $this->config['TCKN'];

    $result = $this->client->ValidationService->ValidateByTckn($validationByTcknOptions);
    return $result;
  }
```

# <a name="response-types">Response Types</a>

This part contains technical information about return values from API.

## PaparaServiceResult

Papara Service Result type. Handles response data types returning from API.

| **Variable Name** | **Type**             | **Description**                                                                |
| ----------------- | -------------------- | ------------------------------------------------------------------------------ |
| data              | bool                 | Gets or sets single result data.                                               |
| succeeded         | bool                 | Gets or sets a value indicating whether operation resulted successfully or not |
| error             | ServiceResultError   | Gets or sets a value indicating whether operation failed or not                |
| result            | ServiceResultSuccess | Gets or sets success result                                                    |

## ServiceResultError

Papara Service Error Result type. Error responses returning from API.

| **Variable Name** | **Type** | **Description**        |
| ----------------- | -------- | ---------------------- |
| message           | string   | Returns error messages |
| code              | int      | Returns error codes    |

## ServiceResultSuccess

Papara Service Success Result type. Success responses returning from API.

| **Variable Name** | **Type** | **Description**          |
| ----------------- | -------- | ------------------------ |
| Message           | string   | Returns success messages |
| Code              | int      | Returns success codes    |
