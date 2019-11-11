<?php
class SpaceModel {
	private static $_table_name = "currency";

  public static function get($realm = "all") {
		global $_space_db;

		$rtn = $inputarr = array();

		if ($realm == "all") {
			$sql = "SELECT * FROM ".self::$_table_name;
		}
		else {
			$sql = "SELECT * FROM ".self::$_table_name." WHERE realm = ?";
			$inputarr[] = $realm;
		}

		$statement = $_space_db->prepare($sql);
		$status = $statement->execute($inputarr);

		if ($status !== false) {
			$ret = $statement->fetchAll(PDO::FETCH_ASSOC);
			$rtn = $ret;
		}
		else {
			$rtn = false;
		}

		return $rtn;
  }

	public static function insertCurrency($currency, $datetime) {
		global $_space_db;

    $rtn = $inputarr = array();

		$sql = "INSERT INTO ".self::$_table_name." (realm, exchange_rate, created_datatime) value ";
		$sql_part = "(?, ?, ?)";

		$sqlArr = array_fill(0, count($currency), $sql_part);
		$sql .= implode(", ", $sqlArr);

		foreach($currency as $realm => $rate) {
			$inputarr[] = $realm;	
			$inputarr[] = $rate;	
			$inputarr[] = $datetime;	
		}

		$statement = $_space_db->prepare($sql);
		$status = $statement->execute($inputarr);
		if ($status !== false) {
			$rtn = true;
		}
		else {
			$rtn = false;
		}
		return $rtn;
	}

}
