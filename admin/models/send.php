<?php
namespace models;


class Send {
  public static function Telegram($message, $userID)
  {
    include('../config.php');
    include('../strategies/strategies.php');

    $serviceMess = $message;
    $serviceUserID = $userID;
    
    include('../bot-telegram.php');
  }
}