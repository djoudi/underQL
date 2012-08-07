<?php

define('UQL_FILTER_IN', 0xA);
define('UQL_FILTER_OUT',0xB);

class UQLFilter{
	
	private $entity_name;
	private $filters_map;

    public function __construct($entity_name)
	{
		$this->entity_name = $entity_name;
		$this->filters_map   = new UQLMap();
	}
	
	public function __call($function_name,$parameters)
	{
		$local_params_count = count($parameters);
		if($local_params_count < 2 /*filter_type (in | out) and filter_name [AT LEAST]*/) return;
		
		$this->addFilter($function_name, $parameters);
	}
	
	protected function addFilter($field,$filter)
	{
		if(!$this->filters_map->isElementExist($field))
		 	$this->filters_map->addElement($field, new UQLMap());
		
		    $local_filter = $this->filters_map->findElement($field);
			$local_filter->addElement($local_filter->getCount(),$filter);
			$this->filters_map->addElement($field, $local_filter);
	}
	
	public function getFiltersByFieldName($field_name)
	{
		return $this->filters_map->findElement($field_name);
	}
	
	public function getFilters()
	{
		return $this->filters_map;
	}
	
	public function getEntityName()
	{
		return $this->entity_name;
	}
	
	public function __destruct()
	{
		$this->entity_name = null;
		$this->filters_map = null;
	}
}


//function uql_filter_sql_injection($name,$value,$params = null,&$result = null) {}

//$the_students_filter = new UQLFilter("students");
//$the_students_filter->name(UQL_FILTER_IN,"sql_injection");
//$the_students_filter->name(UQL_FILTER_IN | UQL_FILTER_OUT,"xss");
//$the_students_filter->name(UQL_FILTER_OUT,"read_data","%s.php");


?>