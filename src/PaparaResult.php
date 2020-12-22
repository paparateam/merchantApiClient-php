<?php

/**
 * PaparaResult class is used by service classes to handle http requests from Papara API.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara;

use Exception;

class PaparaResult
{
  public $data;
  public $succeeded;
  public $error;
  public $result;

  public function __construct($json)
  {
    $this->resolve(json_decode($json));
  }

  private function resolve($data)
  {
    try {
      if ($data) {
        foreach ($data as $key => $value) {
          $this->{$key} = $value;
        }
      }
      else {
        throw new Exception("Invalid result");
      }
    } catch (\Throwable $th) {
    }
  }
}
