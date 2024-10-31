<?php
namespace respek_nature\Components;

if ( !class_exists( 'respek_nature\Components\Respek_ApiClient' ) ) :
  class Respek_ApiClient
  {
    public function __construct(){}

    public function getSpek($data){
      return $this->doRespekCall('POST', '/orders', $data);
    }

    public function getMerchant($data){
      return $this->doRespekCall('POST', '/merchants', $data);
    }

    public function deactivateMerchant($data){
      return $this->doRespekCall('DELETE', '/merchants', $data);
    }

    public function setMerchantConfig($data){
      return $this->doRespekCall('POST', '/merchants/config', $data);
    }

    private static function doRespekCall($action, $url, $data)
    {

      $args = array(
        'method' => $action,
        'body'        => $data,
        'timeout'     => '30',
        'redirection' => '10',
        'httpversion' => '1.1',
        'blocking'    => true,
        'headers'     => array(
          "accept-encoding" => "gzip, deflate",
          "cache-control" => "no-cache",
          "Authorization" => "Basic ".get_option('respek_auth_token','ZXBocmFpbSthQGdvc2VhbWxlc3MuY28uemE6dGVzdDE='),
        )
      );
      $url = get_option('respek_api_url', 'https://www.respeknature.org/api/v1').$url;

      $response = wp_remote_request( $url, $args );

      if (is_wp_error( $response )){
        error_log(print_r($response, true)); 
        return  $response;
      } else {
        $body = wp_remote_retrieve_body($response);
        
        if (strpos($body, 'error')) { 
          error_log($url);
          error_log(print_r($body, true)); 
        }

        return json_decode($body);
      }
    }
  }

endif;