<?php

class UQLRule{
	
	private $entity_name;
	private $alises_map;
	private $rules_map;

    public function __construct($entity_name)
	{
		$this->entity_name = $entity_name;
		$this->alises_map  = new UQLMap();
		$this->rules_map   = new UQLMap();
	}
	
	public function __call($function_name,$parameters)
	{
		
	}
}

//function uql_rule_isemail($name,$value,$alias = null,&$result = null) {}
//function uql_rule_between($name,$value,$params = null,$alias = null,&$result = null) {}

$the_students_rules = new UQLRule("studnets");

$the_students_rules->id('number');
$the_students_rules->name('length',50);
$the_students_rules->email('email');
$the_students_rules->name('between',10,50);



?>