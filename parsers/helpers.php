<?php
namespace parsers;

class Helpers
{
	/* Вынести в отдельный класс!!! */
	public static function prepareLink($link)
	{
		$link = str_replace('%YEAR%', date('Y'), $link);	
		$link = str_replace('%NEXTYEAR%', date('Y', strtotime('+1 year')), $link);	

		return $link;
	}

	public static function clearString($str)
	{
		$str = htmlspecialchars_decode($str);
		$str = str_replace('&hellip;', '...', $str);
		
		$str = trim($str);
		$str = preg_replace("/\s+/", " ", $str);

		return $str; 
	} 

	public static function isDateInRange($startDate, $endDate, $dateFromUser, $format)
	{

	  // var_dump($startDate, $endDate, $dateFromUser);
	  // echo "<br>";
	  $start_ts = date_create_from_format($format, $startDate)->getTimestamp();
	  $end_ts = date_create_from_format($format, $endDate)->getTimestamp();
	  $user_ts = date_create_from_format($format, $dateFromUser)->getTimestamp();

	  // var_dump(($user_ts >= $start_ts) && ($user_ts <= $end_ts));

	  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
	}

	public static function exportVar($var)
	{
		echo "<pre>";
		var_export($var);
		echo "</pre>";
	}
}