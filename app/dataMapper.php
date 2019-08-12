<?php
namespace app;

class Model
{
	public static function get($table, $id = false) {
		if ($id) {
			return \QB::table($table)
							->where($table."_id", $id)
							->get()[0];
		}

		return \QB::table($table)
							->get();
	}
}