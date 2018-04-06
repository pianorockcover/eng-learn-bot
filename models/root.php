<?php
namespace models;

class Root
{
	public static function check($word, $root)
	{
		$word = mb_strtolower($word);
		$root = mb_strtolower($root);

		if (mb_strpos($word, $root) !== false) {
			return true;
		}

		return false;
	}

	public static function identify($word, $roots, $ignoreNumbers = false) 
	{
		if (!$ignoreNumbers) {
			if (is_numeric($word)) {
				foreach ($roots as $key => $root) {
					if ($word == ($key + 1)) {
						return $key;
					}
				}
			}
		}

 		$word = mb_strtolower($word);

		foreach ($roots as $key => $root) {
			$root = mb_strtolower($root->name_root);

			if (mb_strpos($word, $root) !== false) {
				return $key;
			}
		}

		return false;
	}
}