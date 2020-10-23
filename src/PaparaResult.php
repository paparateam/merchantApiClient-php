<?php

/**
 * PaparaResult class is used by service classes to handle http requests from Papara API.
 * 
 * @author Burak Serpici <burak.serpici@crosstech.com.tr>
 * @version 0.0.1
 * @since 0.0.1
 */

namespace Papara;

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
      foreach ($data as $key => $value) {
        $this->{$key} = $value;
      }  
    } catch (\Throwable $th) {
    }
  }
}
