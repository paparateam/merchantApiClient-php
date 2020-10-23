<?php

namespace Papara\Services;

use Papara\PaparaService;
use PaparaResult;

class ApplicationService extends PaparaService
{
  public function __construct($apiKey = "", $isTest = false)
  {
    parent::__construct($apiKey, $isTest);
    $this->basePath = "/application";
  }
}
