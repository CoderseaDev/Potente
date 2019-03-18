<?php
namespace App\Services;

Class Fetcher
{
    public function get($url)
    {

      $result = file_get_contents($url) ;
      //return "Get from API :" . $url;
        return json_decode($result, true);
    }
}