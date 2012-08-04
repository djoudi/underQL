<?php

class UQLInsertQuery{
	
	private $query;
	private $abstract_entity;
	private $values_map; //used to save list of [field_name = value] that are comming from current insertion query
	
	public function __construct(&$database_handle,&$abstract_entity)
	{
		if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
		  die('Bad database handle');
		
			$this->query = new UQLQuery($database_handle);
			$this->abstract_entity = $abstract_entity;
			$this->fields_map = new UQLMap();
			
	}
	
}

?>