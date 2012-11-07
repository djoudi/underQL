<?php

class UQLRuleEngine {
    
    private $uql_rule_object;
    private $uql_values_map; //current inserted | updated $key => $value pairs
    private $uql_false_rule_flag; // true if there is at least one rule failed.
    private $uql_fail_rules_list; // list of error messages about each field that fail in one or more rules

    public function __construct(&$rule_object,&$values_map) {
        
        $this->uql_rule_object = $rule_object;
        $this->uql_values_map = $values_map;
        $this->uql_false_rule_flag = false;
        $this->uql_fail_rules_list = new UQLMap();
    }

    protected function applyRule($field_name,$value) {

        $rules = $this->uql_rule_object->getRulesByFieldName($field_name);

        $the_results = array();

        if($rules == null)
            return true;

        foreach ($rules->getMap() as $key => $params) {
            $rule_name = $params[0];
            $rule_api_function = sprintf(UQL_RULE_FUNCTION_NAME,$rule_name);

            if(!function_exists($rule_api_function))
                die($params[0].' is not a valid rule');

            $alias = $this->uql_rule_object->getAlias($field_name);

            if(@count($params) == 1) // the rule has no parameter(s)
                $result = $rule_api_function($field_name,$value,$alias);
            else {
                $params = array_shift($params); // delete rule name
                $result = $rule_api_function($field_name,$value,$alias,$params);
            }

            if($result != UQL_RULE_SUCCESS) {
                $the_results[$rule_name] = $result; // message
                $this->uql_false_rule_flag = true;
            }
            else
                $the_results[$rule_name] = UQL_RULE_SUCCESS; // OK
        }

        return $the_results;
    }

    public function areRulesPassed() {
        return $this->uql_false_rule_flag == false;
    }

    public function runEngine() {
        
        if(!$this->uql_values_map || $this->uql_values_map->getCount() == 0)
            return null;

        $result = true;
        /*if($this->fail_rules_list->getCount() != 0)
      $this->fail_rules_list = new UQLMap();*/

        foreach($this->uql_values_map->getMap() as $name => $value) {

            $result = $this->applyRule($name,$value);

            if($result != UQL_RULE_SUCCESS)
                $this->uql_fail_rules_list->addElement($name,$result);
        }

        if($this->areRulesPassed())
            return true;

        return $this->uql_fail_rules_list->getMap();
    }

    public function freeResources()
    {
        unset($this->uql_rule_object);
        unset($this->uql_values_map);
        unset($this->uql_false_rule_flag);
        $this->uql_fail_rules_list->freeResources();
    }
    public function __destruct() {
        
        $this->uql_values_map = null;
        $this->uql_rule_object = null;
    }
}

?>