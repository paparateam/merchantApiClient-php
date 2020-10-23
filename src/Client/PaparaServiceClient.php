<?php

/**
 *  PaparaServiceClient handles HTTP requests and responses made to API.
 * 
 *  @author Burak Serpici <burak.serpici@crosstech.com.tr>
 *  @version 0.0.1
 *  @since 0.0.1
 */

namespace Papara\Client;

use Papara\RequestOptions;

class PaparaServiceClient
{
  private $live_url = "https://merchant-api.papara.com";
  private $test_url = "https://merchant.test.api.papara.com";


  /**
   * Undocumented function
   *
   * @param string $method Method type
   * @param string $path Path of service request e.g "/account/ledgers
   * @param [type] $options
   * @param RequestOptions $requestOptions
   * @return void
   */
  public function Request($method, $path, $options, RequestOptions $requestOptions)
  {
    /**
     * Initialize CURL
     */
    $ch = curl_init();

    /**
     * Set request URL
     */
    $url = $requestOptions->isTest ? $this->test_url : $this->live_url;

    /**
     * Set CURL Options
     */
    curl_setopt($ch, CURLOPT_URL, "{$url}{$path}");

    /**
     * Set CURL options according to $method type
     */
    if ("GET" == $method) 
    {
      curl_setopt($ch, CURLOPT_POST, 0);

      if ($options != null) {
        $payload = http_build_query($options);
        curl_setopt($ch, CURLOPT_URL, "{$url}/{$path}?{$payload}");
      }
    }
    else if ("PUT" == $method)
    {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

      if ($options != null) {
        $payload = http_build_query($options);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      }
    }
    else 
    {
      $payload = json_encode($options);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json",
      'Content-Type: application/json; charset=utf-8',
      "ApiKey: " . $requestOptions->apiKey,
    ));

    /**
     * Execute CURL request
     */
    $output = curl_exec($ch);

    if ($method == "PUT") 
    {
      $info = curl_getinfo($ch);
      print_r($info);
    }

    /**
     * Close CURL connection
     */
    curl_close($ch);

    /**
     * Return request result.
     */ 
    return $output;
  }
}
