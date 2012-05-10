<?php
class UQLMap {

	private $map_list;
	private $elements_count;

	public function __construct() {
		$this -> map_list = array();
		$this -> elements_count = 0;
	}

	public function addElement($key, $value) {
		$this -> map_list[$key] = $value;
		$this -> elements_count++;
	}

	public function findElement($key) {
		if ($this -> isElementExist($key))
			return $this -> map_list[$key];

		return null;
	}

	public function isElementExist($key) {
		if ($this -> elements_count <= 0)
			return false;

		if (@array_key_exists($key, $this -> map_list))
			return true;

		return false;
	}

	public function removeElement($key) {

		if ($this -> isElementExist($key)) {
			unset($this -> map_list[$key]);
			$this -> elements_count--;
		}
	}

	public function isEmpty() {
		return $this -> elements_count == 0;
	}

	public function mapCallback($callback) {
		if (!$this -> isEmpty())
			return array_map($callback, $this -> map_list);
	}

	public function __destruct() {
		
		$this -> map_list = null;
		$this -> elements_count = 0;
	}

}
?>