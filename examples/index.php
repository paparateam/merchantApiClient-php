<?php

require_once('../bootstrap.php');

use Papara\Options\CashDepositGetOptions;
use \Papara\PaparaClient;

$id = 59580;

$client = new PaparaClient('PSiMO0fmR7kTZr/V3kyqsPchydlTr6fETA83sUlhBCwrIp9MgINxABFup8IUYsUV/Sd5Qaw7J1g9rLHmPEtJhA==', true);

$cashDepositGetOptions = new CashDepositGetOptions;
$cashDepositGetOptions->id = $id;

$cashDepositResult = $client->CashDepositService->getCashDeposit($cashDepositGetOptions);

print_r($cashDepositResult);