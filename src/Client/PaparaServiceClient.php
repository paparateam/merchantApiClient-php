<?php

/**
 *  PaparaServiceClient handles HTTP requests and responses made to API.
 * 
 *  @author Burak Serpici <burak.serpici@crosstech.com.tr>
 *  @version 0.0.1
 *  @since 0.0.1
 */

namespace Papara\Client;

use Exception;
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
  public function Request($method, $path, $options, $requestOptions)
  {
    /**
     * Initialize CURL
     */
    $ch = curl_init();

    /**
     * Set request URL
     */
    $url = $requestOptions->isTest ? $this->test_url : $this->live_url;
    $ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';

    /**
     * Set CURL Options
     */
    curl_setopt($ch, CURLOPT_URL, "{$url}{$path}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json",
      'Content-Type: application/json; charset=utf-8',
      "ApiKey: " . $requestOptions->apiKey,
    ));

    if ($requestOptions->isTest) {
      curl_setopt($ch, CURLOPT_VERBOSE, true);
    }

    /**
     * Set CURL options according to $method type
     */
    if ("GET" == $method) {
      curl_setopt($ch, CURLOPT_POST, 0);

      if ($options != null) {
        $payload = http_build_query($options);
        curl_setopt($ch, CURLOPT_URL, "{$url}/{$path}?{$payload}");
      }
    } else if ("PUT" == $method) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

      if ($options != null) {
        $payload = http_build_query($options);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      }
    } else {
      $payload = json_encode($options);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    /**
     * Execute CURL request
     */
    $output = curl_exec($ch);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // if ($method == "PUT") {
    //   $info = curl_getinfo($ch);
    //   // print_r($info);
    // }

    /**
     * Close CURL connection
     */
    curl_close($ch);

    if ($httpcode == 403) {
      // throw new Exception("Unautorized");
    }

    /**
     * Return request result.
     */
    return $output;
  }
}
