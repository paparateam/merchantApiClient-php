<?php
require __DIR__ . '/src/Papara.php';
require __DIR__ . '/src/PaparaClient.php';
require __DIR__ . '/src/PaparaResult.php';
require __DIR__ . '/src/PaparaService.php';
require __DIR__ . '/src/RequestOptions.php';

// Client
require __DIR__ . '/src/Client/PaparaServiceClient.php';

// Services
require __DIR__ . '/src/Services/AccountService.php';
require __DIR__ . '/src/Services/ApplicationService.php';
require __DIR__ . '/src/Services/BankingService.php';
require __DIR__ . '/src/Services/CashDepositService.php';
require __DIR__ . '/src/Services/MassPaymentService.php';
require __DIR__ . '/src/Services/PaymentService.php';
require __DIR__ . '/src/Services/ValidationService.php';

// Options
require __DIR__ . '/src/Options/ApplicationOptions.php';
require __DIR__ . '/src/Options/BankingWithdrawalOptions.php';
require __DIR__ . '/src/Options/CashDepositByDateOptions.php';
require __DIR__ . '/src/Options/CashDepositByReferenceOptions.php';
require __DIR__ . '/src/Options/CashDepositCompleteOptions.php';
require __DIR__ . '/src/Options/CashDepositControlOptions.php';
require __DIR__ . '/src/Options/CashDepositGetOptions.php';
require __DIR__ . '/src/Options/CashDepositSettlementOptions.php';
require __DIR__ . '/src/Options/CashDepositTcknControlOptions.php';
require __DIR__ . '/src/Options/CashDepositToAccountNumberOptions.php';
require __DIR__ . '/src/Options/CashDepositToPhoneOptions.php';
require __DIR__ . '/src/Options/CashDepositToTcknOptions.php';
require __DIR__ . '/src/Options/LedgerListOptions.php';
require __DIR__ . '/src/Options/MassPaymentByReferenceOptions.php';
require __DIR__ . '/src/Options/MassPaymentGetOptions.php';
require __DIR__ . '/src/Options/MassPaymentToEmailOptions.php';
require __DIR__ . '/src/Options/MassPaymentToPaparaNumberOptions.php';
require __DIR__ . '/src/Options/MassPaymentToPhoneNumberOptions.php';
require __DIR__ . '/src/Options/PaymentCreateOptions.php';
require __DIR__ . '/src/Options/PaymentGetOptions.php';
require __DIR__ . '/src/Options/PaymentListOptions.php';
require __DIR__ . '/src/Options/PaymentRefundOptions.php';
require __DIR__ . '/src/Options/SettlementGetOptions.php';
require __DIR__ . '/src/Options/ValidationByAccountNumberOptions.php';
require __DIR__ . '/src/Options/ValidationByIdOptions.php';
require __DIR__ . '/src/Options/ValidationByPhoneNumberOptions.php';
require __DIR__ . '/src/Options/ValidationByTcknOptions.php';
