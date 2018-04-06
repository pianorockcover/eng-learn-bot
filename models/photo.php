<?php
namespace models;

class Photo
{
	public static function get($vkLink)
	{
		// $vkLink = 'https://vk.com/njdshop_bot?z=photo-152589705_456239029%2Fwall-152589705_6';

		$attachment = substr($vkLink, strpos($vkLink, 'photo-'), strlen($vkLink));
		$attachment = substr($attachment, 0, strpos($attachment, '%'));

		$photoId = str_replace('photo', '', $attachment);

		$photoInfo = json_decode(file_get_contents('https://api.vk.com/method/photos.getById?photos='.$photoId));

		$photoLink = '';

		$photoInfo = $photoInfo->response[0];

		if (isset($photoInfo->src_xxbig)) {
			$photoLink = $photoInfo->src_xxbig;
		} else if (isset($photoInfo->src_xbig)) {
			$photoLink = $photoInfo->src_xbig;
		} else if (isset($photoInfo->src_big)) {
			$photoLink = $photoInfo->src_big;
		} else if (isset($photoInfo->src_small)) {
			$photoLink = $photoInfo->src_small;
		} else if (isset($photoInfo->src)) {
			$photoLink = $photoInfo->src;
		}

		return [
			'photoLink' => $photoLink,
			'attachment' => $attachment,
		];
	}

}