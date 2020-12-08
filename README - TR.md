# İçindekiler

<a href="#intro">Giriş</a>

<a href="#enums">Enumlar</a>

<a href="#account">Hesap Bilgileri</a>

<a href="#banking">Bankacılık</a>

<a href="#cash-deposit">Fiziksel Nokta Entegrasyonu</a>

<a href="#mass-payment">Ödeme Dağıtma</a>

<a href="#payments">Ödeme Alma</a>

<a href="#validation">Doğrulama</a>

<a href="#response-types">Geri Dönüş Tipleri</a>

# <a name="intro">Giriş</a>

Papara ile entegre olmak için aşağıdaki adımları takip edebilirsiniz;

1. API Anahtarınızı edinin. Böylece Papara doğrulama sistemi API isteklerinin kimliğini doğrulayabilir. API Anahtarınızı almak için https://merchant.test.papara.com/ URL adresine gidin. Başarıyla oturum açtıktan sonra, API Anahtarı https://merchant.test.papara.com/APIInfo adresinde görüntülenebilir.

2. Kütüphaneyi kurun. Böylece yazılımınız Papara API ile entegre olabilir. Kurulum işlemleri aşağıdaki gibidir.

# Konfigürasyon

Papara php kütüphanesi iki yol ile kullanılabilir;

Standart yol ile:

```php
require_once('PATH_TO_PAPARA/bootstrap.php');

use \Papara\PaparaClient;
$client = new PaparaClient('YOUR_PAPARA_API_KEY', true);
```

Ya da nesne yönelimli programlama ile:

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

# <a name="enums">Enumlar</a>

# CashDepositProvisionStatus

Bir para yatırma talebi yapıldığında, aşağıdaki durumlar geri dönecek ve provizyon durumunu gösterecektir.

| Anahtar         | **Değer** | **Açıklama**         |
| --------------- | --------- | -------------------- |
| Pending         | 0         | Provizyon bekleniyor |
| Complete        | 1         | Tamamlandı           |
| Cancel          | 2         | İptal edildi         |
| ReadyToComplete | 3         | Tamamlanmaya hazır   |

# Currency

API'da bulunan bütün para birimleri aşağıdaki gibidir.

| **Anahtar** | **Değer** | **Açıklama**    |
| ----------- | --------- | --------------- |
| TRY         | 0         | Türk Lirası     |
| USD         | 1         | Amerikan Doları |
| EUR         | 2         | Euro            |

# EntryType

Giriş Türleri hesap defterlerinde ve para yatırma işlemlerinde parayı takip etmek için kullanılır. Olası giriş türleri aşağıdaki gibidir.

| **Anahtar**                   | **Değer** | **Açıklama**                                                 |
| ----------------------------- | --------- | ------------------------------------------------------------ |
| BankTransfer                  | 1         | Banka Transferi: Para Yatırma veya Çekme                     |
| CorporateCardTransaction      | 2         | Papara Kurumsal Kart İşlemi: Üye iş yerine tahsis edilen kurum kartı ile gerçekleştirilen işlemdir. |
| LoadingMoneyFromPhysicalPoint | 6         | Fiziki Noktadan Para Yükleme: Anlaşmalı yerden nakit para yatırma işlemi |
| MerchantPayment               | 8         | Satıcı Ödemesi: Papara ile Ödeme                             |
| PaymentDistribution           | 9         | Ödeme Dağıtımı: Papara ile toplu ödeme                       |
| EduPos                        | 11        | Çevrimdışı ödeme. Papara üzerinden EDU POS                   |

# PaymentMethod

Kabul edilen üç ödeme yöntemi aşağıdaki gibidir.

| **Anahtar**   | **Değer** | **Açıklama**          |
| ------------- | --------- | --------------------- |
| PaparaAccount | 0         | Papara Hesap Bakiyesi |
| Card          | 1         | Tanımlı Kredi Kartı   |
| Mobile        | 2         | Mobil Ödeme           |

# PaymentStatus

Ödeme tamamlandıktan sonra API'dan aşağıdaki ödeme durumları dönecektir.

| **Anahtar** | **Değer** | **Açıklama**               |
| ----------- | --------- | -------------------------- |
| Pending     | 0         | Ödeme Bekliyor             |
| Completed   | 1         | User completed the payment |
| Refunded    | 2         | Order refunded             |

# <a name="account">Hesap Bilgileri</a>

Bu bölüm üye işyerine ait hesap ve bakiye bilgilerinin kullanımı için hazırlanan teknik entegrasyon bilgilerini içerir. Papara hesabındaki hesap ve bakiye bilgileri `Account` servisi ile alınabilir. Geliştiriciler ayrıca bakiyede değişiklik işlemlerin bir listesini içeren bakiye geçmişini de alabilirler.

## Hesap Bilgilerine Erişim

Satıcı hesabı ve bakiye bilgilerini döndürür. Bakiye bilgileri cari bakiyeyi, kullanılabilir ve blokeli bakiyeyi içerirken, hesap bilgileri satıcının marka adını ve tam unvanını içerir. Bu işlemi gerçekleştirmek için `Account` servisinde bulunan `GetAccount` methodunu kullanın.

### Account Model

`Account` sınıfı, `Account` servisi tarafından API'den dönen hesap bilgileri eşleştirmek için kullanılır ve hesap bilgilerini içerir.

| **Değişken Adı**    | **Tip**                  | **Açıklama**                                                 |
| ------------------- | ------------------------ | ------------------------------------------------------------ |
| LegalName           | string                   | Satıcının şirket unvanını alır veya belirler.                |
| BrandName           | string                   | Satıcının şirket marka adını alır veya belirler.             |
| AllowedPaymentTypes | List<AllowedPaymentType> | Satıcının şirket için kabul edilen ödeme tiplerini alır veya belirler. |
| Balances            | List<AccountBalance>     | Satıcının şirketin hesap bakiyesini alır veya belirler.      |

### AllowedPaymentType

 `AllowedPaymentType` sınıfı, `Account` servisi tarafından API'den dönen hesap bilgilerini eşleştirmek için kullanılır. `AllowPaymentType`, izin verilen ödeme türlerini gösterir.

| **Değişken Adı** | **Tip** | **Açıklama**                                                 |
| ---------------- | ------- | ------------------------------------------------------------ |
| PaymentMethod    | int     | Ödeme tipini alır veya belirler.<br />0 – Papara Hesap Bakiyesi  <br />1 – Kredi/Banka kartı <br />2 – Mobil Ödeme. |

### AccountBalance

`AccountBalance` sınıf, `Account` servisi tarafından API'den dönen hesap bakiyesi değeriyle eşleştirmek için kullanılır. Hesap bakiyesi, cari bakiye rakamlarını gösterir ve üç tür bakiye ve genel para birimini listeler.

| **Değişken Adı** | **Tip** | **Açıklama**                                |
| ---------------- | ------- | ------------------------------------------- |
| Currency         | int     | Para birimini alır veya belirler.           |
| TotalBalance     | float   | Toplam bakiyeyi alır veya belirler.         |
| LockedBalance    | float   | Blokeli bakiyeyi alır veya belirler.        |
| AvailableBalance | float   | Kullanılabilir bakiyeyi alır veya belirler. |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için hesap bilgilerini ve cari bakiyeyi getirir.

| **Method** | **Parametreler** | **Geri Dönüş Tipi**   |
| ---------- | ---------------- | --------------------- |
| GetAccount | None             | PaparaResult<Account> |

#### Kullanım Şekli

``` php
public function GetAccount()
  {
    $result = $this->client->AccountService->GetAccount();
    return $result;
  }
```

## Hesap Hareketlerini Listeleme

Satıcı hesap hareketlerini(işlem listesi) sayfalı biçimde döndürür. Bu method, her işlem için ortaya çıkan bakiye dahil olmak üzere bir satıcı için yapılan tüm işlemleri listelemek için kullanılır. Bu işlemi gerçekleştirmek için `Account` hizmetinde `ListLedgers` methodunu kullanın. `startDate` ve `endDate` bilgileri gönderilmelidir.

### AccountLedger

`AccountLedger` sınıfı, `Account` servisi tarafından API'den dönen değerleri eşleştirmek için kullanılır. Bir işlemin kendisini temsil eder.

| **Değişken Adı**    | **Tip**      | **Açıklama**                                                 |
| ------------------- | ------------ | ------------------------------------------------------------ |
| ID                  | int          | Merchant ID alır veya belirler.                              |
| CreatedAt           | DateTime     | Hesap hareketlerinin oluşma tarihinialır veya belirler.      |
| EntryType           | EntryType    | Giriş türünü alır veya belirler.                             |
| EntryTypeName       | string       | Giriş tür adını alır veya belirler.                          |
| Amount              | float        | Tutarı alır veya belirler.                                   |
| Fee                 | float        | Hizmet bedelini alır veya belirler.                          |
| Currency            | int          | Para birimini alır veya belirler.                            |
| CurrencyInfo        | CurrencyInfo | Para birimi bilgisini alır veya belirler.                    |
| ResultingBalance    | float        | Kalan bakiyeyi alır veya belirler.                           |
| Description         | string       | Açıklamayı alır veya belirler.                               |
| MassPaymentId       | string       | Toplu ödeme ID'sini alır veya belirler. Ödeme işlemlerinde mükerrer tekrarı önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Hesap hareketlerinde toplu ödeme türü işlem kayıtlarında işlemin kontrolünü sağlamak için görüntülenir. Diğer ödeme türlerinde boş olacaktır. |
| CheckoutPaymentId   | string       | Ödeme ID'sini alır veya belirler. Ödeme kaydı işleminde veri nesnesinde bulunan kimlik alanıdır. Ödeme işleminin benzersiz tanımlayıcısıdır. Hesap hareketlerinde kasa tipi işlem kayıtlarında görüntülenir. Diğer ödeme türlerinde boş olacaktır. |
| CheckoutReferenceID | string       | Checkout referans ID'ini alır veya belirler. Bu, ödeme işlemi kaydı oluşturulurken gönderilen referans kimliği alanıdır. Üye işyeri sisteminde ödeme işleminin referans bilgisidir. Hesap hareketlerinde kasa tipi işlem kayıtlarında görüntülenir. Diğer ödeme türlerinde boş olacaktır |

### CurrencyInfo

`CurrencyInfo` sınıfı, `AccountLedger` modeli tarafından API'den dönen para birimi değerlerini almak veya ayarlamak için kullanılır. Hesap hareketlerinde bulunan para birimi bilgilerini temsil eder.

| **Değişken Adı**     | **Tip**  | **Açıklama**                                                 |
| -------------------- | -------- | ------------------------------------------------------------ |
| CurrencyEnum         | Currency | Para birimi tipini alır veya belirler                        |
| Symbol               | string   | Para birimi sembolünü alır veya belirler                     |
| Code                 | string   | Para birimi kodunu alır veya belirler                        |
| PreferredDisplayCode | string   | Para biriminin tercih edilen gösterim kodunu alır veya belirler |
| Name                 | string   | Para biriminin adını alır veya belirler                      |
| IsCryptoCurrency     | bool     | Para biriminin kripto para olup olmadığını alır veya belirler |
| Precision            | int      | Para biriminin virgülden sonra kaç hane gösterileceğini alır veya belirler |
| IconUrl              | string   | Para birimi ikonu URL'ini alır veya belirler                 |

### LedgerListOptions Model

`LedgerListOptions` `Account` servisi tarafından hesap hareketleri listeleme işlemine istek parametreleri sağlamak için kullanılır

| **Değişken Adı** | **Tip**  | **Açıklama**                                                 |
| ---------------- | -------- | ------------------------------------------------------------ |
| startDate        | DateTime | İşlemlerin başlangıç tarihini alır veya belirler             |
| endDate          | DateTime | İşlemlerin bitiş tarihlerini alır veya belirler              |
| entryType        | enum     | İşlemlerin hareket tiplerini alır veya belirler              |
| accountNumber    | int      | Satıcı hesap numarasını alır veya belirler                   |
| page             | int      | İstenen sayfa numarasını alır veya belirler. İstenen tarihte, istenen PageSize için 1'den fazla sonuç sayfası varsa, bunu sayfalar arasında dönmek için kullanın |
| pageSize         | int      | Bir sayfada getirilmesi istenen kalem sayısını alır veya belirler. Min=1, Max=50 |

### Servis Methodu

#### Kullanım Amacı

etkili satıcı için hesap hareketleri listesini döndürür.

| **Method**  | **Parametreler**  | **Geri Dönüş Tipi**         |
| ----------- | ----------------- | --------------------------- |
| ListLedgers | LedgerListOptions | PaparaResult<AccountLedger> |

#### Kullanım Şekli

``` php
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

## Mutabakat Bilgilerine Erişim

Verilen süre içindeki işlemlerin sayısını ve hacmini hesaplar. Bu işlemi gerçekleştirmek için ` Account`  servisinde bulunan ` GetSettlement` methodunu kullanın. ` startDate` ve ` endDate` gönderilmelidir.

### Settlement Model

`Settlement` sınıfı, ` Account` servisi tarafından API'dan dönen mutabakat değerlerini eşleştirmek için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                       |
| ---------------- | ------- | ---------------------------------- |
| Count            | int     | İşlem sayısını alır veya belirler. |
| Volume           | int     | İşlem hacmini alır veya belirler   |

### SettlementGetOptions Model

`SettlementGetOptions` sınıfı, ` Account` servisi tarafından API'dan dönen mutabakat değerlerini eşleştirmek için kullanılır.

| **Değişken Adı** | **Tip**   | **Açıklama**                                      |
| ---------------- | --------- | ------------------------------------------------- |
| startDate        | DateTime  | İşlemlerin başlangıç tarihini alır veya belirler. |
| endDate          | DateTime  | İşlemlerin bitiş tarihini alır veya belirler.     |
| entryType        | EntryType | İşlemlerin giriş tipini alır veya belirler.       |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için mutabakat bilgilerini getirir.

| **Method**    | **Parametreler**     | **Geri Dönüş Tipi**      |
| ------------- | -------------------- | ------------------------ |
| GetSettlement | SettlementGetOptions | PaparaResult<Settlement> |

#### Kullanım Şekli

``` php
public function GetSettlement()
  {
    $options = new SettlementGetOptions;
    $options->startDate = "2020-01-01T00:00:00.000Z";
    $options->endDate = "2020-09-01T00:00:00.000Z";

    $result = $this->client->AccountService->GetSettlement($options);
    return $result;
  }
```

# <a name="banking">Bankacılık</a> 

Bu bölümde, banka hesaplarını Papara'da hızlı ve güvenli bir şekilde listelemek ve / veya banka hesaplarına para çekme talebi oluşturmak isteyen işyerleri için hazırlanmış teknik entegrasyon bilgileri yer almaktadır.

## Banka Hesap Bilgilerine Erişim

Satıcı kurumun kayıtlı banka hesaplarını getirir. Bu işlemi gerçekleştirmek için `Banking` servisinde bulunan `GetBankAccounts` methodunu kullanın.

### BankAccount Model

`BankAccount` sınıfı, `Banking` servisi tarafından API'den dönen banka hesaplarını eşleştirmek için kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                                     |
| ---------------- | ------- | ------------------------------------------------ |
| BankAccountId    | int?    | Satıcının banka hesap ID'sini alır veya belirler |
| BankName         | string  | Satıcının banka adını alır veya belirler         |
| BranchCode       | string  | Satıcının şube kodunu alır veya belirler         |
| Iban             | string  | IBAN numarasını alır veya belirler               |
| AccountCode      | string  | Satıcının hesap kodunu alır veya belirler        |
| Description      | string  | Açıklamayı alır veya belirler                    |
| Currency         | string  | Para birimini alır veya belirler                 |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için banka hesaplarını döndürür.

| **Method**      | **Parametreler** | **Geri Dönüş Tipi**       |
| --------------- | ---------------- | ------------------------- |
| GetBankAccounts |                  | PaparaResult<BankAccount> |

#### Kullanım Şekli

``` php 
public function GetBankAccounts()
  {
	$result = $this->client->BankingService->GetBankAccounts();
    return $result;
  }
```

## Para Çekim İşlemi

Satıcılar için para çekme talepleri oluşturur. Bu işlemi gerçekleştirmek için `Banking` hizmetinde `Withdrawal` methodunu kullanın.

### BankingWithdrawalOptions 

`BankingWithdrawalOptions` `Banking` servisi tarafından istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                                 |
| ---------------- | ------- | ------------------------------------------------------------ |
| bankAccountId    | int?    | Para çekme işlemi tamamlandığında hangi paranın aktarılacağı hedef banka hesap kimliğini alır veya belirler.Banka hesaplarını listeleme isteği sonucunda elde edilir. |
| amount           | float   | Çekilecek para tutarını alır veya belirler.                  |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için belirli bir banka hesabından para çekme talebi oluşturur.

| **Method** | **Parametreler**         | **Geri Dönüş Tipi** |
| ---------- | ------------------------ | ------------------- |
| Withdrawal | BankingWithdrawalOptions | PaparaResult        |

#### Kullanım Şekli

``` php
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

## Olası Hatalar ve Hata Kodları

| **Hata Kodu** | **Hata Açıklaması**                         |
| ------------- | ------------------------------------------- |
| 105           | Yetersiz bakiye                             |
| 115           | Talep edilen miktar minimum limitin altında |
| 120           | Banka hesabı bulunamadı                     |
| 247           | Satıcı hesabı aktif değil                   |

# <a name="cash-deposit">Fiziksel Nokta Entegrasyonu</a> 

Papara fiziksel nokta entegrasyonu ile son kullanıcıların Papara hesaplarına bakiye yükleyebilecekleri para yükleme noktası olabilir ve kazanç sağlayabilirsiniz. Fiziksel nokta entegrasyon yöntemleri sadece kullanıcıların Papara hesaplarına nakit yükledikleri senaryolarda kullanılmalıdır.

## Para Yatırma Bilgilerine Erişim

Nakit para yükleme bilgilerini döndürür. Bu işlemi gerçekleştirmek için `CashDeposit`  servisinde bulunan `getCashDeposit `methodunu kullanın. `id` gönderilmelidir.

### CashDeposit Model

`CashDeposit` sınıfı, `CashDeposit` servisi tarafından API'den dönen nakit para yükleme bilgilerini eşleştirmek için kullanılır.

| **Değişken Adı**  | **Tip**   | **Açıklama**                                                 |
| ----------------- | --------- | ------------------------------------------------------------ |
| MerchantReference | string    | Satıcının referans numarasını alır veya belirler.            |
| Id                | int?      | Nakit para yükleme Id'sini alır veya belirler.               |
| CreatedAt         | DateTime? | Nakit para yükleme işleminin yapıldığı alır veya belirler.   |
| Amount            | float     | Nakit para yükleme işleminin tutarını alır veya belirler.    |
| Currency          | int?      | Nakit para yükleme işleminin para birimini alır veya belirler. |
| Fee               | float     | Nakit para yükleme işleminin hizmet bedelini alır veya belirler. |
| ResultingBalance  | float     | Nakit para yükleme işleminden sonra kalan bakiyeyi alır veya belirler. |
| Description       | string    | Nakit para yükleme işleminin açıklamasını alır veya belirler. |

### CashDepositGetOptions

`CashDepositGetOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                                   |
| ---------------- | ------- | ---------------------------------------------- |
| id               | int     | Nakit para yükleme Id'sini alır veya belirler. |

### Servis Methodu

#### Kullanım Amacı

Nakit para yükleme işlemi bilgilerini döner

| **Method**     | **Parametreler**      | **Geri Dönüş Tipi**       |
| -------------- | --------------------- | ------------------------- |
| getCashDeposit | CashDepositGetOptions | PaparaResult<CashDeposit> |

####   Kullanım Şekli

``` php
public function getCashDeposit()
  {
    $cashDepositGetOptions = new CashDepositGetOptions;
    $cashDepositGetOptions->id = $result->data->id;

    $cashDepositResult = $this->client->CashDepositService->getCashDeposit($cashDepositGetOptions);
    return $result;
  }
```

## Referans Numarasına Göre Nakit Para Yükleme İşlemine Erişim

Satıcı referans bilgileri ile birlikte fiziksel noktadan para yükleme işlemine ait bilgileri döndürür. Bu işlemi gerçekleştirmek için `CashDeposit` servisinde bulunan `getByReference` methodunu kullanın. `reference` gönderillmelidir.

### CashDepositByReferenceOptions

`CashDepositByReferenceOptions`  `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                                 |
| ---------------- | ------- | ------------------------------------------------------------ |
| reference        | string  | Nakit para yükleme işleminin referans numarasını alır veya belirler. Zorunlu parametredir. |

### Servis Methodu

#### Kullanım Amacı

Satıcının benzersiz referans numarasını kullanarak bir nakit para yükleme nesnesi döndürür.

| **Method**     | **Parametreler**              | **Geri Dönüş Tipi**       |
| -------------- | ----------------------------- | ------------------------- |
| getByReference | CashDepositByReferenceOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli

``` php
public function getByReference()
  {
    $cashDepositByReferenceOptions = new CashDepositByReferenceOptions;
    $cashDepositByReferenceOptions->reference = "REFERENCE_NO";

    $result = $this->client->CashDepositService->getByReference($cashDepositByReferenceOptions);
    return $result;
  }
```

## Telefon Numarası ile Para Yükleme

Kullanıcının telefon numarasını kullanarak fiziksel noktadan kullanıcıya para yatırır. Bu işlemi gerçekleştirmek için `Cash Deposit` servisinde bulunan `createWithPhoneNumber` methodunu kullanın. `phoneNumber`, `amount` ve `merchantReference` gönderilmelidir.

### CashDepositToPhoneOptions

`CashDepositToPhoneOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| phoneNumber       | string  | Papara hesabına kayıtlı cep telefonu numarasını alır veya belirler. |
| amount            | float   | Yüklenecek para tutarını alır veya belirler. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Üye işyeri hesabından düşülecek tutar tam olarak bu sayı olacaktır. |
| merchantReference | string  | Satıcı referans numarasını alır veya belirler. Nakit yükleme işlemlerinde yanlış tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısasüre önce gönderilmiş ve başarılı bir merchantReference, yeni bir taleple yeniden gönderilirse, istek başarısız olur. Başarısız isteklerle gönderilen MerchantReference yeniden gönderilebilir. |

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının telefon numarasını kullanarak nakit para yatırma isteği oluşturur.

| **Method**            | **Parametreler**          | **Geri Dönüş Tipi**       |
| --------------------- | ------------------------- | ------------------------- |
| createWithPhoneNumber | CashDepositToPhoneOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli

``` php
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

## Papara Numarası ile Para Yükleme

Fiziksel noktadan Papara numarası ile kullanıcıya para yatırır. Bu işlemi yapmak için  `Cash Deposit` servisinde bulunan `createWithAccountNumber` methodunu kullanın. `accountNumber`, `amount` ve `merchantReference` gönderilmelidir.

### CashDepositToAccountNumberOptions

`CashDepositToAccountNumberOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| accountNumber     | int     | Hesap numarasını alır veya belirler. Nakit yükleme yapılacak kullanıcının Papara hesap numarasıdır. |
| amount            | float   | Yüklenecek para tutarını alır veya belirler. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Üye işyeri hesabından düşülecek tutar tam olarak bu sayı olacaktır. |
| merchantReference | string  | Satıcı referans numarasını alır veya belirler. Nakit yükleme işlemlerinde yanlış tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısasüre önce gönderilmiş ve başarılı bir merchantReference, yeni bir taleple yeniden gönderilirse, istek başarısız olur. Başarısız isteklerle gönderilen MerchantReference yeniden gönderilebilir. |

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının hesap numarasını kullanarak nakit para yükleme talebi oluşturur.

| **Method**              | **Parametreler**                  | **Geri Dönüş Tipi**       |
| ----------------------- | --------------------------------- | ------------------------- |
| createWithAccountNumber | CashDepositToAccountNumberOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli


```php
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

## TC Kimlik Numarası ile Para Yükleme

Fiziksel noktadan TCKN ile kullanıcıya para yatırır. Bu işlemi yapmak için  `Cash Deposit` servisinde bulunan `createWithTckn` methodunu kullanın. `tckn`, `amount` ve `merchantReference` gönderilmelidir.

### CashDepositToTcknOptions

`CashDepositToTcknOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| tckn              | long    | Nakit yükleme yapılacak kullanıcının TC kimlik numarasını alır veya belirler. |
| amount            | float   | Yüklenecek para tutarını alır veya belirler. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Üye işyeri hesabından düşülecek tutar tam olarak bu sayı olacaktır |
| merchantReference | string  | Satıcı referans numarasını alır veya belirler. Nakit yükleme işlemlerinde yanlış tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısasüre önce gönderilmiş ve başarılı bir merchantReference, yeni bir taleple yeniden gönderilirse, istek başarısız olur. Başarısız isteklerle gönderilen MerchantReference yeniden gönderilebilir. |

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının TC kimlik numarasını kullanarak nakit para yükleme talebi oluşturur..

| **Method**     | **Parametreler**         | **Geri Dönüş Tipi**       |
| -------------- | ------------------------ | ------------------------- |
| createWithTckn | CashDepositToTcknOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli

```php
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

## TC Kimlik Numarasına Ön Ödemesiz Para Yükleme

Fiziksel noktadan TCKN ile kullanıcıya ön ödemesiz olarak para yatırır. Bu işlemi yapmak için  `Cash Deposit` servisinde bulunan `createProvisionWithTckn` methodunu kullanın. `tckn`, `amount` ve `merchantReference` gönderilmelidir.

### CashDepositProvision Model

`CashDepositProvision` sınıfı `CashDeposit` servisi tarafından API'den dönen ön ödemesiz para yükleme bilgilerini eşleştirmek için kullanılır

| **Değişken Adı**  | **Tip**  | **Açıklama**                                                 |
| ----------------- | -------- | ------------------------------------------------------------ |
| Id                | int      | Ön ödemesiz para yükleme işleminin ID'sini alır veya belirler. |
| CreatedAt         | DateTime | Ön ödemesiz para yükleme işleminin oluşturulma tarihini alır veya belirler.it |
| Amount            | float    | Ön ödemesiz para yükleme işleminin tutarını alır veya belirler. |
| Currency          | int      | Ön ödemesiz para yükleme işleminin para birimini alır veya belirler. |
| MerchantReference | string   | Satıcı referans numarasını alır veya belirler.               |
| UserFullName      | string   | Kullanıcının tam adını alır veya belirler.                   |

### CashDepositToTcknOptions

`CashDepositTcknControlOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tipi** | **Açıklama**                                                 |
| ----------------- | -------- | ------------------------------------------------------------ |
| tckn              | int      | Nakit yükleme yapılacak kullanıcının TC kimlik numarasını alır veya belirler. |
| amount            | int      | Yüklenecek para tutarını alır veya belirler. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Üye işyeri hesabından düşülecek tutar tam olarak bu sayı olacaktır. |
| merchantReference | string   | Satıcı referans numarasını alır veya belirler. Nakit yükleme işlemlerinde yanlış tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısasüre önce gönderilmiş ve başarılı bir merchantReference, yeni bir taleple yeniden gönderilirse, istek başarısız olur. Başarısız isteklerle gönderilen MerchantReference yeniden gönderilebilir. |

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının TC kimlik numarasını kullanarak ön yüklemesiz nakit para yükleme talebi oluşturur.

| **Method**              | **Parametreler**              | **Geri Dönüş Tipi**                |
| ----------------------- | ----------------------------- | ---------------------------------- |
| createProvisionWithTckn | CashDepositTcknControlOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
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

## TCKN ile Ön Ödemesiz Para Yükleme Kontrolü 

Fiziksel noktadan Papara'ya kayıtlı ulusal kimlik numarası ile kullanıcıya para yatırır. Bu işlemi gerçekleştirmek için `Cash Deposit` servisinde bulunan `createProvisionWithTcknControl` methodunu kullanın. `phoneNumber`, `tckn`, `amount` ve `merchantReference` gönderilmelidir.

### CashDepositProvision Model

`CashDepositProvision` sınıfı `CashDeposit` servisi tarafından API'den dönen ön ödemesiz para yükleme bilgilerini eşleştirmek için kullanılır

| **Değişken Adı**  | **Tip**  | **Açıklama**                                                 |
| ----------------- | -------- | ------------------------------------------------------------ |
| Id                | int      | Para yükleme işleminin ID'sini alır veya belirler            |
| CreatedAt         | DateTime | Para yükleme işleminin oluşturulma tarihini alır veya belirler |
| Amount            | float    | Para yükleme işleminin tutarını alır veya belirler           |
| Currency          | int      | Para yükleme işleminin para birimini alır veya belirler      |
| MerchantReference | string   | Satıcı referans numarasın alır veya belirler                 |
| UserFullName      | string   | Kullanıcının tam adını alır veya belirler                    |

### CashDepositTcknControlOptions

`CashDepositTcknControlOptions` `Cash Deposit` servisi tarafından istek parametresi olarak kullanılır

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| phoneNumber       | string  | Kullanıcının telefon numarasını alır veya belirler. Ödemeyi alacak kullanıcının Papara'da kayıtlı cep telefonu numarasıdır. Bir ülke kodu içermeli ve + ile başlamalıdır. |
| tckn              | int     | Nakit yükleme yapılacak kullanıcının TC kimlik numarasını alır veya belirler. |
| amount            | float   | Miktarı alır veya belirler. Ödeme işleminin tutarıdır. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Bu rakam artı işlem ücreti üye işyeri hesabından tahsil edilecektir |
| merchantReference | string  | Satıcı referans numarasını alır veya belirler. Nakit yükleme işlemlerinde yanlış tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısasüre önce gönderilmiş ve başarılı bir merchantReference, yeni bir taleple yeniden gönderilirse, istek başarısız olur. Başarısız isteklerle gönderilen MerchantReference yeniden gönderilebilir. |

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının tc kimlik numarasını kullanarak ön ödeme yapmadan nakit para yükleme isteği kontrolü oluşturur.

| **Method**                     | **Parametreler**              | **Geri Dönüş Tipi**                |
| ------------------------------ | ----------------------------- | ---------------------------------- |
| createProvisionWithTcknControl | CashDepositTcknControlOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
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

## Telefon Numarası ile Ön Ödemesiz Para Yükleme

Kullanıcının telefon numarasını kullanarak fiziksel noktadan kullanıcıya ön ödemesiz olark para yatırır. Bu işlemi gerçekleştirmek için `Cash Deposit` servisinde bulunan `createProvisionWithPhoneNumber` methodunu kullanın. `phoneNumber`, `amount` ve `merchantReference` gönderilmelidir.

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının telefon numarasını kullanarak ön ödemesiz nakit para yatırma isteği oluşturur.

| **Method**                     | **Parametreler**          | **Geri Dönüş Tipi**                |
| ------------------------------ | ------------------------- | ---------------------------------- |
| CreateProvisionWithPhoneNumber | CashDepositToPhoneOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
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

## Papara Numarası ile Ön Ödemesiz Para Yükleme

Fiziksel noktadan Papara numarası ile kullanıcıya ön ödemesiz olarak para yatırır. Bu işlemi yapmak için  `Cash Deposit` servisinde bulunan `createProvisionWithAccountNumber` methodunu kullanın. `accountNumber`, `amount` ve `merchantReference` gönderilmelidir.

### Servis Methodu

#### Kullanım Amacı

Son kullanıcının hesap numarasını kullanarak ön ödemesiz nakit para yatırma isteği oluşturur.

| **Method**                       | **Parametreler**                  | **Geri Dönüş Tipi**                |
| -------------------------------- | --------------------------------- | ---------------------------------- |
| createProvisionWithAccountNumber | CashDepositToAccountNumberOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
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

## Referans Numarasına Göre Nakit Yükleme Onaylama

Kullanıcı tarafından oluşturulan referans kodu ile fiziki noktadan ön ödemesiz nakit para yükleme talebini kontrol ederek onaylanmaya hazır hale getirir. Bu işlemi gerçekleştirmek için,  `Cash Deposit` servisinde bulunan `controlProvisionByReference` methodunu kullanın. `referenceCode` ve `amount` gönderilmelidir.

### Servis Methodu

#### Kullanım Amacı

Ön ödemesiz nakit para yükleme talebini tamamlanmaya hazır hale getirir.

| **Method**                  | **Parametreler**          | **Geri Dönüş Tipi**                |
| --------------------------- | ------------------------- | ---------------------------------- |
| controlProvisionByReference | CashDepositControlOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
public function controlProvisionByReference()
  {
    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->controlProvisionByReference($options);

    return $result;
  }
```

## Referans Numarasına Göre Nakit Yükleme İşlemini Tamamlama

Kullanıcı tarafından oluşturulan referans kodu ile fiziki noktadan ön ödemesiz nakit para yükleme talebini onaylar ve bakiyeyi kullanıcıya aktarır. Bu işlemi gerçekleştirmek için `CashDeposit` servisinde bulunan `completeProvisionByReference` methodunu kullanın. `referenceCode` ve `amount` gönderilmelidir.

### Servis Methodu

#### Kullanım Amacı

Ön ödemesiz nakit yükleme işlemini tamamlar

| **Method**                   | **Parametreler**          | **Geri Dönüş Tipi**                |
| ---------------------------- | ------------------------- | ---------------------------------- |
| completeProvisionByReference | CashDepositControlOptions | PaparaResult<CashDepositProvision> |

#### Kullanım Şekli

```php
public function completeProvisionByReference()
  {
    $options = new CashDepositControlOptions;
    $options->amount = 10;
    $options->referenceCode = $result->data->merchantReference;

    $cashdeposit = $this->client->CashDepositService->completeProvisionByReference($options); 

    return $result;
  }
```



## Nakit Yükleme İşlemini Tamamlama

Bekleyen para yükleme işlemlerini tamamlamak için kullanılır. Kullanıcının hesabına paranın geçmesi için işlemin tamamlanması gerekir. Bu işlemi gerçekleştirmek için `CashDeposit` servisinde bulunan `completeProvision` methodunu kullanın. `id` ve `transactionDate` gönderilmelidir.

### CashDepositCompleteOptions

`CashDepositCompleteOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip**  | **Açıklama**                                                 |
| ---------------- | -------- | ------------------------------------------------------------ |
| id               | int      | Ön ödemesiz nakit yükleme işleminin ID'sini alır veya belirler |
| transactionDate  | DateTime | Ön ödemesiz nakit yükleme işleminin işlem tarihini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Bekleyen ön ödemesiz para yükleme işlemlerini tamamlamak için kullanılır.

| **Method**        | **Parametreler**           | **Geri Dönüş Tipi**       |
| ----------------- | -------------------------- | ------------------------- |
| completeProvision | CashDepositCompleteOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli

```php
public function completeProvision()
  {
    $cashDepositCompleteOptions = new CashDepositCompleteOptions;
    $cashDepositCompleteOptions->id = $result->data->id;
    $cashDepositCompleteOptions->transactionDate = $result->data->createdAt;

    $compilationResult = $this->client->CashDepositService->completeProvision($cashDepositCompleteOptions);
    return $result;
  }
```

## Tarihe Göre Nakit Para Yükleme Bilgilerine Erişim

Para yatırma bilgilerini tarihe göre getirir. Bu işlemi gerçekleştirmek için, `Cash Deposit` bulunan `getCashDepositByDate` methodunu kullanın. `startDate`, `endDate`, `pageIndex` ve `pageItemCount` gönderilmelidir.

### CashDepositByDateOptions

`CashDepositByDateOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip**  | **Açıklama**                                                 |
| ---------------- | -------- | ------------------------------------------------------------ |
| startDate        | DateTime | Nakit para yükleme işlemlerinin başlangıç tarihini alır veya belirler. |
| endDate          | DateTime | Nakit para yükleme işlemlerinin bitiş tarihini alır veya belirler. |
| pageIndex        | int      | Sayfa dizinini alır veya belirler. Bir sayfada gösterilmek istenen kayıt sayısına (pageItemCount) göre hesaplanan sayfalardan gösterilmek istenen sayfanın indeks numarasıdır. Not: ilk sayfa her zaman 1'dir |
| pageItemCount    | int      | Sayfa öğesi sayısını alır veya belirler. Bir sayfada gösterilmesi istenen kayıtların sayısıdir. |

### Servis Methodu

#### Kullanım Amacı

Verilen tarihler aralığındaki nakit para yükleme işlemlerine erişim için kullanılır

| **Method**           | **Parametreler**         | **Geri Dönüş Tipi**       |
| -------------------- | ------------------------ | ------------------------- |
| getCashDepositByDate | CashDepositByDateOptions | PaparaResult<CashDeposit> |

#### Kullanım Şekli

```php
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

## Mutabakatlar

Verilen tarihlerde gerçekleştirilen para yükleme işlemlerinin toplam sayısını ve hacmini döndürür. Hesaplamaya hem başlangıç hem de bitiş tarihleri dahil edilir. Bu işlemi gerçekleştirmek için, `Cash Deposit`  servisinde bulunan `settlements` methodunu kullanın. `startDate` ve `endDate` gönderilmelidir

### CashDepositSettlementOptions

`CashDepositSettlementOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip**    | **Açıklama**                                         |
| ---------------- | ---------- | ---------------------------------------------------- |
| startDate        | DateTime   | Mutabakatın başlangıç tarihini alır veya belirler    |
| endDate          | DateTime   | Mutabakatın bitiş tarihini alır veya belirler        |
| entryType        | EntryType? | Mutabakatın giriş tipini tarihini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Verilen tarihler arasındaki toplam nakit para yükleme işlem hacmini ve sayımı döndürür. Başlangıç ve bitiş tarihleri dahildir.

| **Method**  | **Parametreler**             | **Geri Dönüş Tipi**                 |
| ----------- | ---------------------------- | ----------------------------------- |
| settlements | CashDepositSettlementOptions | PaparaResult<CashDepositSettlement> |

#### Kullanım Şekli

```php
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

## Ön Ödemesiz İşlemler için Mutabakat

Verilen tarihlerde gerçekleştirilen ön ödemesiz para yükleme işlemlerin toplam sayısını ve hacmini döndürür. Hesaplamaya hem başlangıç hem de bitiş tarihleri dahil edilir. Bu işlemi gerçekleştirmek için, `Cash Deposit`  servisinde bulunan `provisionSettlements` methodunu kullanın. `startDate` ve `endDate` gönderilmelidir.

### CashDepositSettlementOptions

`CashDepositSettlementOptions` `CashDeposit` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip**    | **Açıklama**                                      |
| ---------------- | ---------- | ------------------------------------------------- |
| startDate        | DateTime   | Mutabakatın başlangıç tarihini alır veya belirler |
| endDate          | DateTime   | Mutabakatın bitiş tarihini alır veya belirler     |
| entryType        | EntryType? | Mutabakatın giriş tipini alır veya belirler       |

### Servis Methodu

#### Kullanım Amacı

Verilen tarihler arasındaki toplam ön ödemesiz nakit para yükleme işlem hacmini ve sayımı döndürür. Başlangıç ve bitiş tarihleri dahildir.

| **Method**           | **Parametreler**             | **Geri Dönüş Tipi**                 |
| -------------------- | ---------------------------- | ----------------------------------- |
| provisionSettlements | CashDepositSettlementOptions | PaparaResult<CashDepositSettlement> |

#### Kullanım Şekli

```php
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

## Olası Hatalar ve Hata Kodları

| **Hata Kodu** | **Hata Açıklaması**                                          |
| ------------- | ------------------------------------------------------------ |
| 100           | Kullanıcı bulunamadı.                                        |
| 101           | Satıcı bilgisi bulunamadı.                                   |
| 105           | Yetersiz bakiye.                                             |
| 107           | Kullanıcı bu işlem ile toplam işlem limitini aşıyor.         |
| 111           | Kullanıcı bu işlem ile aylık toplam işlem limitini aşıyor.   |
| 112           | Gönderilen tutar minimum gönderim tutarının altında.         |
| 203           | Kullanıcı hesabı blokeli.                                    |
| 997           | Nakit para yatırma yetkisi, hesabınızda tanımlanmamıştır. Müşteri temsilcinizle iletişime geçmelisiniz. |
| 998           | Gönderdiğiniz parametreler beklenen formatta değil. Örnek: zorunlu alanlardan biri sağlanmamıştır. |
| 999           | Papara sisteminde hata meydana geldi.                        |

# <a name="mass-payment">Ödeme Dağıtma</a> 

Bu bölüm, ödemelerini kullanıcılarına hızlı, güvenli ve yaygın bir şekilde Papara üzerinden dağıtmak isteyen işyerleri için hazırlanmış teknik entegrasyon bilgilerini içerir.

## Ödeme Dağıtım Bilgilerine Erişim

Ödeme dağıtım işlemi hakkında bilgileri döner. Bu işlemi yamak için `MassPayment` servisinde bulunan `getMassPayment` methodunu kullanın. `id` gönderilmelidir.

### Mass Payment Model

`MassPayment` sınıfı, `MassPayment` servisi tarafından API'den dönen ödeme dağıtım bilgilerini eşleştirmek için kullanılır.

| **Değişken Adı** | **Tip**  | **Açıklama**                                                 |
| ---------------- | -------- | ------------------------------------------------------------ |
| massPaymentId    | string   | Ödeme ID'sini alır veya belirler.                            |
| id               | int?     | Ödeme yapıldıktan sonra oluşan ID'yi alır veya belirler.     |
| createdAt        | DateTime | Ödeme tarihini alır veya belirler.                           |
| amount           | float    | Ödeme tutarını alır veya belirler.                           |
| currency         | int?     | Ödeme yapılan para birmini alır veya belirler. Değerler "1","2" veya "3" olabilir. |
| fee              | float    | Hizmet bedelini alır veya belirler.                          |
| resultingBalance | float    | Kalan bakiyeyi alır veya belirler.                           |
| description      | string   | Açıklamayı alır veya belirler.                               |

### MassPaymentGetOptions

`MassPaymentGetOptions` `MassPayment` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                     |
| ---------------- | ------- | -------------------------------- |
| id               | long    | Ödeme ID'sini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için ödeme dağıtım bilgisine erişmek için kullanılır

| **Method**     | **Parametreler**      | **Geri Dönüş Tipi**       |
| -------------- | --------------------- | ------------------------- |
| getMassPayment | MassPaymentGetOptions | PaparaResult<MassPayment> |

#### Kullanım Şekli

```php
public function getMassPayment()
  {
    $getMassPaymentGetOptions = new MassPaymentGetOptions;
    $getMassPaymentGetOptions->id = "MASS_PAYMENT_ID";
  
    $result = $this->client->MassPaymentService->getMassPayment($getMassPaymentGetOptions);
    return $result;
  }
```

## Referans Numarasına Göre Ödeme Dağıtım Bilgilerine Erişim

Referans numarası kullanarak ödeme dağıtım süreci hakkında bilgi verir. Bu işlemi gerçekleştirmek için `MassPayment` servisinde bulunan `getMassPaymentByReference` methodunu kullanın. `reference` gönderilmelidir.

### Mass Payment Model

`MassPayment` sınıfı, `Mass Payment` servisi tarafından API'den dönen ödeme dağıtma bilgilerini eşleştirmek için kullanılır.

### MassPaymentByReferenceOptions

`MassPaymentByReferenceOptions` `MassPayment` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                           |
| ---------------- | ------- | ------------------------------------------------------ |
| reference        | string  | Ödeme işleminin referans numarasını alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için ödeme bilgisine erişmek için kullanılır

| **Method**                | **Parametreler**              | **Geri Dönüş Tipi**       |
| ------------------------- | ----------------------------- | ------------------------- |
| getMassPaymentByReference | MassPaymentByReferenceOptions | PaparaResult<MassPayment> |

#### Kullanım Şekli

```php
public function getMassPaymentByReference()
  {
    $getMassPaymentByReferenceOptions = new MassPaymentByReferenceOptions;
    $getMassPaymentByReferenceOptions->reference = "MASS_PAYMENT_REFERENCE";
  
    $result = $this->client->MassPaymentService->getMassPaymentByReference($getMassPaymentByReferenceOptions);
    return $result;
  }
```


## Hesap Numarasına Ödeme Gönderme

Papara numarasına para gönderin. Bu işlemi gerçekleştirmek için `MassPayment` servisinde bulunan `createMassPaymentWithAccountNumber` methodunu kullanın. `accountNumber`, `amount` ve `massPaymentId` gönderilmelidir.

### MassPaymentToPaparaNumberOptions

`MassPaymentToPaparaNumberOptions` `MassPayment` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                              |
| ------------------ | -------- | ------------------------------------------------------------ |
| accountNumber     | string   | Papara hesap numarasını alır veya belirler. Ödemeyi alacak kullanıcının 10 haneli Papara numarası. 1234567890 veya PL1234567890 biçiminde olabilir. Papara sürüm geçişinden önce Papara numarasına cüzdan numarası deniyordu, eski cüzdan numaraları Papara numarası olarak değiştirildi. Ödeme eski cüzdan numaralarına dağıtılabilir. |
| parseAccountNumber | int?     | Ayrıştırma hesap numarasını alır veya belirler. Hesap numarasını long tip olarak ayrıştırır. Eski papara entegrasyonlarında PL ile başlanarak hesap / cüzdan numarası yapılıyordu. Hizmet, kullanıcılarından papara numarasını alan üye işyerlerine sorun yaşatmaması için PL ile başlayan numaraları kabul edecek şekilde yazılmıştır. |
| amount            | float | Miktarı alır veya belirler. Ödeme işleminin tutarıdır. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Bu rakam artı işlem ücreti üye işyeri hesabından tahsil edilecektir. |
| massPaymentId     | string   | Ödeme ID'sini alır veya belirler. Ödeme işlemlerinde hatalı tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısa süre önce gönderilmiş ve başarılı olan bir massPaymentId yeni bir taleple tekrar gönderilirse, istek başarısız olur. |
| turkishNationalId | long     | TC kimlik numarasını alır veya belirler. Ödemeyi alacak kullanıcının gönderdiği kimlik bilgilerinin Papara sisteminde kontrolünü sağlar. Kimlik bilgilerinde bir çelişki olması durumunda işlem gerçekleşmeyecektir. |
| description       | string   | Açıklamayı alır veya ayarlar. Satıcı tarafından sağlanan işlemin açıklamasıdır. Zorunlu bir alan değildir. Gönderilirse işlem açıklamalarında alıcı tarafından görülür. |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için verilen hesap numarasına ödeme göndermek için kullanılır

| **Method**                         | **Parametreler**                 | **Geri Dönüş Tipi**       |
| ---------------------------------- | -------------------------------- | ------------------------- |
| createMassPaymentWithAccountNumber | MassPaymentToPaparaNumberOptions | PaparaResult<MassPayment> |

#### Kullanım Şekli

```php
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

## E-Posta Adresine Ödeme Gönderme

Papara'da kayıtlı e-posta adresine para gönderin. Bu işlemi gerçekleştirmek için `MassPayment` servisinde bulunan `createMassPaymentWithEmail` methodunu kullanın. `email`, `amount` ve `massPaymentId` gönderilmelidir.

### MassPaymentToEmailOptions

`MassPaymentToEmailOptions` `MassPayment` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| email             | string  | Hedef e-posta adresini alır veya belirler.                   |
| amount            | float   | Miktarı alır veya belirler. Ödeme işleminin tutarıdır. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Bu rakam artı işlem ücreti üye işyeri hesabından tahsil edilecektir |
| massPaymentId     | string  | Ödeme ID'sini alır veya belirler. Ödeme işlemlerinde hatalı tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısa süre önce gönderilmiş ve başarılı olan bir massPaymentId yeni bir taleple tekrar gönderilirse, istek başarısız olur. |
| turkishNationalId | long    | TC kimlik numarasını alır veya belirler. Ödemeyi alacak kullanıcının gönderdiği kimlik bilgilerinin Papara sisteminde kontrolünü sağlar. Kimlik bilgilerinde bir çelişki olması durumunda işlem gerçekleşmeyecektir. |
| description       | string  | Açıklamayı alır veya ayarlar. Satıcı tarafından sağlanan işlemin açıklamasıdır. Zorunlu bir alan değildir. Gönderilirse işlem açıklamalarında alıcı tarafından görülür. |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için verilen e-posta adresine ödeme göndermek için kullanılır

| **Method**                 | **Parametreler**          | **Geri Dönüş Tipi**       |
| -------------------------- | ------------------------- | ------------------------- |
| createMassPaymentWithEmail | MassPaymentToEmailOptions | PaparaResult<MassPayment> |

#### Kullanım Şekli

```php
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

## Telefon Numarasına Ödeme Gönderme

Papara'da kayıtlı telefon numarasına para gönderin. Bu işlemi gerçekleştirmek için `MassPayment` servisinde bulunan `createMassPaymentWithPhoneNumber` methodunu kullanın. `phoneNumber`, `amount` ve `massPaymentId` gönderilmelidir.

### MassPaymentToPhoneNumberOptions

`MassPaymentToPhoneNumberOptions` `MassPayment` servisine istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| phoneNumber       | string  | Kullanıcının telefon numarasını alır veya belirler. Ödemeyi alacak kullanıcının Papara'da kayıtlı cep telefonu numarasıdır. Bir ülke kodu içermeli ve + ile başlamalıdır. |
| amount            | float   | Miktarı alır veya belirler. Ödeme işleminin tutarıdır. Bu tutar ödemeyi alan kullanıcının hesabına aktarılacaktır. Bu rakam artı işlem ücreti üye işyeri hesabından tahsil edilecektir |
| massPaymentId     | string  | Ödeme ID'sini alır veya belirler. Ödeme işlemlerinde hatalı tekrarları önlemek için üye işyeri tarafından gönderilen benzersiz değerdir. Kısa süre önce gönderilmiş ve başarılı olan bir massPaymentId yeni bir taleple tekrar gönderilirse, istek başarısız olur. |
| turkishNationalId | long    | TC kimlik numarasını alır veya belirler. Ödemeyi alacak kullanıcının gönderdiği kimlik bilgilerinin Papara sisteminde kontrolünü sağlar. Kimlik bilgilerinde bir çelişki olması durumunda işlem gerçekleşmeyecektir. |
| description       | string  | Açıklamayı alır veya ayarlar. Satıcı tarafından sağlanan işlemin açıklamasıdır. Zorunlu bir alan değildir. Gönderilirse işlem açıklamalarında alıcı tarafından görülür. |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için verilen telefon numarasına ödeme göndermek için kullanılır

| **Method**                       | **Parametreler**                | **Geri Dönüş Tipi**       |
| -------------------------------- | ------------------------------- | ------------------------- |
| createMassPaymentWithPhoneNumber | MassPaymentToPhoneNumberOptions | PaparaResult<MassPayment> |

#### Kullanım Şekli

```php
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

## Olası Hatalar ve Hata Kodları

| **Hata Kodu** | **Hata Açıklaması**                                          |
| ------------- | ------------------------------------------------------------ |
| 100           | Kullanıcı bulunamadı                                         |
| 105           | Yetersiz bakiye                                              |
| 107           | Alıcı bakiye limitini aşıyor. Basit hesaplar için mümkün olan en yüksek bakiye 750 TL'dir. |
| 111           | Alıcı aylık işlem limitini aşıyor. Basit hesaplar tanımlı kaynaktan aylık toplam 2000 TL ödeme alabilir. |
| 133           | MassPaymentID yakın zamanda kullanıldı.                      |
| 997           | Ödemeleri dağıtma yetkiniz yok. Müşteri temsilcinizle iletişime geçebilir ve satıcı hesabınıza bir ödeme dağıtım tanımı talep edebilirsiniz. |
| 998           | Gönderdiğiniz parametreler beklenen formatta değil. Örnek: Müşteri numarası 10 haneden az. Bu durumda, hata mesajı format hatasının ayrıntılarını içerir. |
| 999           | Papara sisteminde bir hata oluştu.                           |

# <a name="payments">Ödeme Alma</a> 

Ödeme alma, oluşturma veya listeleme ve geri ödeme için ödeme hizmeti kullanılacaktır. Ödeme butonunu kullanıcılara göstermeden önce üye işyeri Papara'da bir ödeme işlemi oluşturmalıdır. Ödeme kayıtları zamana bağlıdır. Son kullanıcı tarafından tamamlanmayan ve ödenmeyen işlem kayıtları 1 saat sonra Papara sisteminden silinir. Tamamlanan ödeme kayıtları asla silinmez ve her zaman API ile sorgulanabilir.

## Ödeme Bilgilerine Erişim

Ödeme bilgilerini döndürür. Bu işlemi gerçekleştirmek için `Payment` servisinde bulunan `getPayment` methodunu kullanın. `id` gönderilmelidir.

### Payment Model

`Payment` sınıfı, `Payment` servisi tarafından API'den dönen ödeme değerlerini eşleştirmek için kullanılır.

| **Değişken Adı**         | **Tip**  | **Açıklama**                                                 |
| ------------------------ | -------- | ------------------------------------------------------------ |
| merchant                 | Account  | Satıcıyı alır veya belirler                                  |
| id                       | string   | ID'yi alır veya belirler                                     |
| CreatedAt                | DateTime | Ödemenin oluşturulma tarihini alır veya belirler             |
| merchantId               | string   | Satıcı ID'sini alır veya belirler                            |
| userId                   | string   | Kullanıcı ID'sini alır veya belirler                         |
| paymentMethod            | int      | Ödeme Yöntemini alır veya belirler. <br />0 - Kullanıcı, mevcut Papara bakiyesiyle işlemi tamamladı <br />1 - Kullanıcı, işlemi daha önce tanımlanmış bir banka / kredi kartı ile tamamladı. <br />2 - Kullanıcı, mobil ödeme yoluyla işlemi tamamladı. |
| paymentMethodDescription | string   | Ödeme yöntemi açıklamasını alır veya belirler.               |
| referenceId              | string   | Referans numarasını alır veya belirler.                      |
| orderDescription         | string   | Sipariş açıklamasını alır veya belirler.                     |
| status                   | int      | Ödeme durumunu alır veya belirler.<br /> 0 - Bekleniyor, ödeme henüz yapılmadı. <br />1 - Ödeme yapıldı, işlem tamamlandı. 2 - İşlemler üye işyeri tarafından iade edildi. |
| statusDescription        | string   | Ödeme durumu açıklamasını alır veya belirler                 |
| amount                   | float    | Ödeme tutarını alır veya belirler                            |
| fee                      | float    | Ödeme hizmet bedelini alır veya belirler                     |
| currency                 | int      | Ödeme yapılacak para birimini alır veya belirler. Değerler “0”,  “1”, “2” veya  “3” olabilir. |
| notificationUrl          | string   | Bildirim URL'ini alır veya belirler.                         |
| notificationDone         | bool     | Bildirimin yapılıp yapılmadığını alır veya belirler.         |
| redirectUrl              | string   | Yönlendirme URL'ini alır veya belirler.                      |
| raymentUrl               | string   | Ödeme URL'ini alır veya belirler.                            |
| merchantSecretKey        | string   | Satıcı gizli anahtarını alır veya belirler.                  |
| returningRedirectUrl     | string   | Geri dönen yönlendirme URL'ini alır veya belirler.           |
| turkishNationalId        | long     | TC kimlik numarasını alır veya belirler.                     |

### PaymentGetOptions

`PaymentGetOptions` ödeme bilgilerine ulaşırken parametre olarak kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                               |
| ---------------- | ------- | ------------------------------------------ |
| id               | string  | Benzersiz ödeme ID'sini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Ödeme ve bakiye bilgilerine erişmek için kullanılır

| **Method** | **Parametreler**  | **Geri Dönüş Tipi**   |
| ---------- | ----------------- | --------------------- |
| getPayment | PaymentGetOptions | PaparaResult<Payment> |

#### Kullanım Şekli

```php
public function getPayment()
  {
    $paymentGetOptions = new PaymentGetOptions;
    $paymentGetOptions->id = "PAYMENT_ID";

    $result = $this->client->PaymentService->getPayment($paymentGetOptions);
    return $result;
  }
```

## Referans Numarasına Göre Ödeme Bilgilerine Erişim

Ödeme bilgilerini döndürür. Bu işlemi gerçekleştirmek için `Payment` servisinde bulunan `getPaymentByReference` methodunu kullanın. `referenceId` gönderilmelidir.


### PaymentGetByReferenceOptions

`PaymentGetByReferenceOptions` ödeme bilgilerine ulaşırken parametre olarak kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                |
| ----------------- | -------- | ------------------------------ |
| referenceId                | string   | Ödeme referans numarasını alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için ödeme ve bakiye bilgilerine erişmek istenildiğinde kullanılır.

| **Method** | **Parametreler**  | **Geri Dönüş Tipi**   |
| ---------- | ----------------- | --------------------- |
| getPaymentByReference | PaymentGetByReferenceOptions | PaparaResult<Payment> |

#### Kullanım Şekli

```php
public function getPaymentByReference()
  {
    $paymentByReferenceOptions = new PaymentGetByReferenceOptions;
    $paymentByReferenceOptions->referenceId = "PAYMENT_REFERENCE_NUMBER";

    $result = $this->client->PaymentService->getPaymentByReference($paymentByReferenceOptions);
    return $result;
  }
```


## Ödeme Oluşturma

Yeni bir ödeme kaydı oluşturur. Bu işlemi gerçekleştirmek için `Payment` servisinde bulunan `createPayment`  methodunu kullanın. `amount`, `referenceId`, `orderDescription`, `notificationUrl` ve `redirectUrl` sağlanmalıdır.

### PaymentCreateOptions

`PaymentCreateOptions` ödeme oluştururken parametre olarak kullanılır

| **Değişken Adı**  | **Tip** | **Açıklama**                                                 |
| ----------------- | ------- | ------------------------------------------------------------ |
| amount            | float   | Ödeme yapılacak miktarı alır veya belirler. Ödeme işleminin tutarı. Tam olarak bu tutar ödemeyi yapan kullanıcının hesabından alınacak ve bu tutar ödeme ekranında kullanıcıya gösterilecektir. Miktar alanı minimum 1.00, maksimum 500000.00 olabilir |
| referenceId       | string  | Referans ID'sini alır veya belirler. Üye işyeri sistemindeki ödeme işleminin referans bilgileridir. İşlem, Papara'ya gönderildiği gibi sonuç bildirimlerinde değiştirilmeden üye işyerine iade edilecektir. 100 karakterden fazla olmamalıdır. Bu alanın benzersiz olması gerekmez ve Papara böyle bir kontrol yapmaz |
| orderDescription  | string  | Sipariş açıklamasını alır veya belirler. Ödeme işleminin açıklamasıdır. Gönderilen bilgi, Papara ödeme sayfasında kullanıcıya gösterilecektir. Kullanıcı tarafından başlatılan işlemi doğru bir şekilde bildiren bir tanıma sahip olmak, başarılı ödeme şansını artıracaktır. |
| notificationUrl   | string  | Bildirim URL'sini alır veya belirler. Ödeme bildirim isteklerinin (IPN) gönderileceği URL'dir.  "NotificationUrl" ile gönderilen URL'ye Papara, ödeme tamamlandıktan hemen sonra bir HTTP POST isteği ile ödemenin tüm bilgilerini içeren bir ödeme nesnesi gönderecektir. Üye işyeri bu talebe 200 OK döndürürse tekrar bildirim yapılmayacaktır. Üye işyeri bu bildirime 200 OK dönmezse, Papara, üye işyeri 200 OK'e dönene kadar 24 saat boyunca ödeme bildirimi (IPN) talepleri yapmaya devam edecektir. |
| redirectUrl       | string  | Yönlendirme URL'sini alır veya belirler. İşlemin sonunda kullanıcının yönlendirileceği URL |
| turkishNationalId | long    | TC kimlik numarasını alır veya belirler. Ödemeyi alacak kullanıcının gönderdiği kimlik bilgilerinin Papara sisteminde kontrolünü sağlar. Kimlik bilgilerinde bir çelişki olması durumunda işlem gerçekleşmeyecektir. |

### Servis Methodu

#### Kullanım Amacı

Ödeme oluşturmak için kullanılacaktır.

| **Method**    | **Parametreler**     | **Geri Dönüş Tipi**   |
| ------------- | -------------------- | --------------------- |
| createPayment | PaymentCreateOptions | PaparaResult<Payment> |

#### Kullanım Şekli

```php
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

## İade İşlemi 

Satıcının ödeme ID'siyle tamamlanmış bir ödemesini iade etmesini sağlar. Bu işlemi gerçekleştirmek için `Payment` servisinde bulunan `refund` yöntemini kullanın. `id` gönderilmelidir.

### PaymentRefundOptions

`PaymentRefundOptions` iade oluştururken parametre olarak kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                     |
| ---------------- | ------- | -------------------------------- |
| id               | string  | Ödeme ID'sini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcı için bir ödemenin iade edileceği durumlarda kullanılır.

| **Method** | **Parametreler**     | **Geri Dönüş Tipi** |
| ---------- | -------------------- | ------------------- |
| refund     | PaymentRefundOptions | PaparaResult        |

#### Kullanım Şekli

```php
public function refund()
  {
    $paymentRefundOptions = new PaymentRefundOptions;
    $paymentRefundOptions->id = "PAYMENT_ID";

    $result = $this->client->PaymentService->refund($paymentRefundOptions);
    return $result;
  }
```

## List Payments

Satıcının tamamlanan ödemelerini sıralı bir şekilde listeler. Bu işlemi gerçekleştirmek için `Payment` servisinde buluan `list` methodunu kullanın. `pageIndex`ve `pageItemCount ` gönderilmelidir.

### PaymentListOptions

`PaymentListOptions` sınıfı `Payment` servisi tarafından API'den dönen liste bilgilerini eşleştirmek için kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                                                 |
| ---------------- | ------- | ------------------------------------------------------------ |
| pageIndex        | int     | Sayfa dizinini alır veya belirler. Bir sayfada gösterilmek istenen kayıt sayısına (pageItemCount) göre hesaplanan sayfalardan gösterilmek istenen sayfanın indeks numarasıdır. Not: ilk sayfa her zaman 1'dir |
| pageItemCount    | Int     | Sayfa öğesi sayısını alır veya belirler. Bir sayfada gösterilmesi istenen kayıtların sayısıdır. |

### PaymentListItem

`PaymentListItem` is used by payment service to match returning completed payment list values list API.

| **Değişken Adı**         | **Tip**  | **Açıklama**                                                 |
| ------------------------ | -------- | ------------------------------------------------------------ |
| Id                       | string   | Ödeme ID'sini alır veya belirler.                            |
| CreatedAt                | DateTime | Ödemenin yapıldığı tarihi alır veya belirler.                |
| MerchantId               | string   | Satıcı ID'sini alır veya belirler.                           |
| UserId                   | string   | Kullanıcı ID'sini alır veya belirler                         |
| PaymentMethod            | int      | Ödeme Yöntemini alır veya belirler<br />0 - Kullanıcı, mevcut Papara bakiyesiyle işlemi tamamladı <br />1 - Kullanıcı, işlemi daha önce tanımlanmış bir banka / kredi kartı ile tamamladı. <br />2 - Kullanıcı, mobil ödeme yoluyla işlemi tamamladı. |
| PaymentMethodDescription | string   | Ödeme açıklamasını alır veya belirler.                       |
| ReferenceId              | string   | Referans ID'yi alır veya belirler.                           |
| OrderDescription         | string   | Sipariş açıklamasını alır veya belirler.                     |
| Status                   | int      | Ödeme durumunu alır veya belirler. <br />0 - Bekleniyor, ödeme henüz yapılmadı. <br />1 - Ödeme yapıldı, işlem tamamlandı. <br />2 - İşlemler üye işyeri tarafından iade edilir. |
| StatusDescription        | string   | Ödeme durum açıklamasını alır veya belirler.                 |
| Amount                   | float    | Ödeme tutarını alır veya belirler.                           |
| Fee                      | float    | Hizmet bedelini alır veya belirler.                          |
| Currency                 | int      | Ödemenin yapıldığı para birimini alır veya belirler. Olabilecek değerler “0”,  “1”, “2” veya “3” |
| NotificationUrl          | string   | Bildirim URL'ini alır veya belirler                          |
| NotificationDone         | bool     | Bildirimin yapılıp yapılmadığını alır veya belirler          |
| RedirectUrl              | string   | Yönlendirme URL'ini alır veya belirler                       |
| PaymentUrl               | string   | Ödeme URL'ini alır veya belirler                             |
| MerchantSecretKey        | string   | Satıcı gizli anahtarını alır veya belirler                   |
| ReturningRedirectUrl     | string   | Geri dönüş URL'ini alır veya belirler                        |
| TurkishNationalId        | long     | TC Kimlik numarasını alır veya belirler                      |

### Servis Methodu

#### Kullanım Amacı

Yetkili satıcılar için tamamlanmış ödemeleri yeniden eskiye doğru sıralayacal bir şekilde görüntülemek için kullanılır

| **Method** | **Parametreler**   | **Geri Dönüş Tipi**           |
| ---------- | ------------------ | ----------------------------- |
| list       | PaymentListOptions | PaparaResult<PaymentListItem> |

#### Kullanım Şekli

```php
public function list()
  {
    $paymentListOptions = new PaymentListOptions;
    $paymentListOptions->pageIndex = 1;
    $paymentListOptions->pageItemCount = 20;

    $paymentListResult = $this->client->PaymentService->list($paymentListOptions);
    return $result;
  }
```

## Olası Hatalar ve Hata Kodları

| **Hata Kodu** | **Hata Açıklaması**                                          |
| ------------- | ------------------------------------------------------------ |
| 997           | Ödemeleri kabul etme yetkiniz yok. Müşteri temsilcinizle iletişime geçmelisiniz. |
| 998           | Gönderdiğiniz parametreler beklenen formatta değil. Örnek: zorunlu alanlardan biri sağlanmamıştır. |
| 999           | Papara sisteminde bir hata oluştu.                           |

# <a name="validation">Doğrulama</a> 

Bir son kullanıcıyı doğrulamak için doğrulama servisi kullanılacaktır. Doğrulama, hesap numarası, e-posta adresi, telefon numarası, ulusal kimlik numarası ile yapılabilir.

## Kullanıcı ID'si ile Doğrulama

Papara kullanıcı ID'si ile kullanıcıları doğrulamak için kullanılır. Bu işlemi gerçekleştirmek için `Validation` servisinde bulunan `validateById`methodunu kullanın. `userId` gönderilmelidir.

### Validation Model           

`Validation` sınıfı, `Validation` servisi tarafından API'den dönen kullanıcı değerini eşleştirmek için kullanılır

| **Değişken Adı** | **Tip** | **Açıklama**                                          |
| ---------------- | ------- | ----------------------------------------------------- |
| UserId           | string  | Kullanıcı ID'sini alır veya belirler.                 |
| FirstName        | string  | Kullanıcının ismini alır veya belirler.               |
| LastName         | string  | Kullanıcının soyismini alır veya belirler.            |
| Email            | string  | Kullanıcının e-posta adresini alır veya belirler.     |
| PhoneNumber      | string  | Kullanıcının telefon numarasını alır veya belirler.   |
| Tckn             | Long    | Kullanıcının TC kimlik numarasını alır veya belirler. |
| AccountNumber    | int?    | Kullanıcının hesap numarasını alır veya belirler.     |

### ValidationByIdOptions 

`ValidationByIdOptions` `Validation` servisi tarafından istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                            |
| :--------------- | ------- | --------------------------------------- |
| userId           | string  | Kullanıcının ID'sini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Kullanıcı ID'si ile doğrulama yapılmak istenildiğinde kullanılır

| **Method**   | **Parametreler**      | **Geri Dönüş Tipi**      |
| ------------ | --------------------- | ------------------------ |
| validateById | ValidationByIdOptions | PaparaResult<Validation> |

#### Kullanım Şekli

```php
public function ValidateById()
  {
    $validationByIdOptions = new ValidationByIdOptions;
    $validationByIdOptions->userId = $this->config['PersonalAccountId'];

    $result = $this->client->ValidationService->ValidateById($validationByIdOptions);
    return $result;
  }
```

## Hesap Numarası ile Doğrulama

Papara hesap numarası ile kullanıcıları doğrulamak için kullanılır. Bu işlemi gerçekleştirmek için `Validation` servisinde bulunan `validateByAccountNumber` methodunu kullanın. `accountNumber` gönderilmelidir.

### ValidationByAccountNumberOptions

`ValidationByAccountNumberOptions` `Validation ` servisi tarafından istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                |
| ---------------- | ------- | ------------------------------------------- |
| accountNumber    | long    | Papara hesap numarasını alır veya belirler. |

### Servis Methodu

#### Kullanım Amacı

Papara hesap numarası ile doğrulama yapılmak istenildiğinde kullanılır

| **Method**              | **Parametreler**                 | **Geri Dönüş Tipi**      |
| ----------------------- | -------------------------------- | ------------------------ |
| validateByAccountNumber | ValidationByAccountNumberOptions | PaparaResult<Validation> |

#### Kullanım Şekli

```php
public function ValidateByAccountNumber()
  {
    $validationByAccountNumberOptions = new ValidationByAccountNumberOptions;
    $validationByAccountNumberOptions->accountNumber = $this->config['PersonalAccountNumber'];

    $result = $this->client->ValidationService->ValidateByAccountNumber($validationByAccountNumberOptions);
    return $result;
  }
```

## Telefon Numarası ile Doğrulama

Paparaya kayıtlı telefon numarası ile kullanıcıları doğrulamak için kullanılır. Bu işlemi gerçekleştirmek için `Validation` servisinde bulunan `validateByPhoneNumber`methodunu kullanın. `phoneNumber` gönderilmelidir.

### ValidationByPhoneNumberOptions

`ValidationByPhoneNumberOptions` `Validation` servisi tarafından istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                                |
| ---------------- | ------- | ----------------------------------------------------------- |
| phoneNumber      | string  | Paparaya kayıtlı olan telefon numarasını alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Paparaya kayıtlı telefon numarası ile doğrulama yapılmak istenildiğinde kullanılır

| **Method**            | **Parametreler**               | **Geri Dönüş Tipi**            |
| --------------------- | ------------------------------ | ------------------------------ |
| validateByPhoneNumber | ValidationByPhoneNumberOptions | PaparaSingleResult<Validation> |

#### Kullanım Şekli

```php
public function ValidateByPhoneNumber()
  {
    $validationByPhoneNumberOptions = new ValidationByPhoneNumberOptions;
    $validationByPhoneNumberOptions->phoneNumber = $this->config['PersonalPhoneNumber'];

    $result = $this->client->ValidationService->ValidateByPhoneNumber($validationByPhoneNumberOptions);
    return $result;
  }
```

## E-Posta Adresi ile Doğrulama

Paparaya kayıtlı e-posta adresi ile kullanıcıları doğrulamak için kullanılır. Bu işlemi gerçekleştirmek için `Validation` servisinde bulunan `validateByEmail`methodunu kullanın. `email` gönderilmelidir.

### ValidationByEmailOptions

`ValidationByEmailOptions` `Validation`servisi tarafından istek parametrelerini sağlamak için kullanılır.

| **Değişken Adı** | **Tip** | **Açıklama**                                              |
| ---------------- | ------- | --------------------------------------------------------- |
| email            | string  | Paparaya kayıtlı olan e-posta adresini alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Paparaya kayıtlı e-posta adresi ile doğrulama yapılmak istenildiğinde kullanılır

| **Method**      | **Parametreler**         | **Geri Dönüş Tipi**      |
| --------------- | ------------------------ | ------------------------ |
| validateByEmail | ValidationByEmailOptions | PaparaResult<Validation> |

#### Kullanım Şekli

```php
  public function ValidateByEmail()
  {
    $ValidationByEmailOptions = new ValidationByEmailOptions;
    $ValidationByEmailOptions->email = $this->config['PersonalEmail'];

    $result = $this->client->ValidationService->ValidateByEmail($ValidationByEmailOptions);
    return $result;
  }
```

## Validate By National Identity Number

Paparaya kayıtlı TC kimlik numarası ile kullanıcıları doğrulamak için kullanılır. Bu işlemi gerçekleştirmek için `Validation` servisinde bulunan `validateByTckn`methodunu kullanın. `tckn` gönderilmelidir.

### ValidationByTcknOptions

`ValidationByPhoneNumberOptions` is used by validation service for providing request parameters.

| **Değişken Adı** | **Tip** | **Açıklama**                            |
| ---------------- | ------- | --------------------------------------- |
| tckn             | long    | TC Kimlik numarasını alır veya belirler |

### Servis Methodu

#### Kullanım Amacı

Returns end user information for validation by given user national identity number

| **Method**     | **Parametreler**        | **Geri Dönüş Tipi**      |
| -------------- | ----------------------- | ------------------------ |
| validateByTckn | ValidationByTcknOptions | PaparaResult<Validation> |

#### Kullanım Şekli

```php
public function ValidateByTckn()
  {
    $validationByTcknOptions = new ValidationByTcknOptions;
    $validationByTcknOptions->tckn = $this->config['TCKN'];

    $result = $this->client->ValidationService->ValidateByTckn($validationByTcknOptions);
    return $result;
  }
```



# <a name="response-types">Geri Dönüş Tipleri</a>

Bu bölüm, API'den dönüş değerleri hakkında teknik bilgiler içerir.

## PaparaServiceResult

Papara Single Result tipi. API'ye gönderilen ve API'den dönen nesne veri tiplerini işler.

| **Değişken Adı** | **Tip**              | **Açıklama**                                                 |
| ---------------- | -------------------- | ------------------------------------------------------------ |
| data             | bool                 | Genel nesne dönüş tipi. Verilen nesne tipi değerini döndürür |
| succeeded        | bool                 | İşlemin başarıyla sonuçlanıp sonuçlanmadığını gösteren bir değer alır veya belirler |
| error            | ServiceResultError   | İşlemin başarısız olup olmadığını gösteren bir değer alır veya belirler |
| result           | ServiceResultSuccess | Başarılı olan işlem sonucunu alır veya belirler.             |

## ServiceResultError

Papara Service Error Result type. Error responses returning from API.

| **Değişken Adı** | **Tip** | **Açıklama**                     |
| ---------------- | ------- | -------------------------------- |
| message          | string  | Hata mesajını alır veya belirler |
| code             | int     | Hata kodunu alır veya belirler   |

## ServiceResultSuccess

Papara Service Success Result type. Success responses returning from API.

| **Değişken Adı** | **Tip** | **Açıklama**                                     |
| ---------------- | ------- | ------------------------------------------------ |
| Message          | string  | Başarılı işlem sonuç mesajını alır veya belirler |
| Code             | int     | Başarılı işlem sonuç kodunu alır veya belirler   |

 