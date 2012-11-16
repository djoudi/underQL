<?php

class UQLRuleEngine extends UQLBase{
    
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

    protected function the_uql_apply_rule($field_name,$value) {

        $rules = $this->uql_rule_object->the_uql_get_rules_by_field_name($field_name);

        $the_results = array();

        if($rules == null)
            return true;

        foreach ($rules->the_uql_get_map() as $rule_id => $rule_value) {

            if(!$rule_value['is_active'])
                continue;

            $rule_name = $rule_value['rule'][0];
            $include_rule_api = 'include_rules';
            $include_rule_api($rule_name);

            $rule_api_function = sprintf(UQL_RULE_FUNCTION_NAME,$rule_name);

            if(!function_exists($rule_api_function))
                $this->the_uql_error($rule_name.' is not a valid rule');

            $alias = $this->uql_rule_object->the_uql_get_alias($field_name);

            if(@count($rule_value['rule']) == 1) // the rule has no parameter(s)
                $result = $rule_api_function($field_name,$value,$alias);
            else {
                $params = array_alice($rule_value['rule']); // remove rule name
                $result = $rule_api_function($field_name,$value,$alias,$params);
            }

            if($result != UQL_RULE_SUCCESS) {
                $the_results[$rule_name] = $result; // message
                $this->uql_false_rule_flag = true;
            }
            else
                $the_results[$rule_name] = $result; // OK
        }

        return $the_results;
    }

    public function the_uql_are_rules_passed() {
        return $this->uql_false_rule_flag == false;
    }

    public function the_uql_run_engine() {
        
        if(!$this->uql_values_map || $this->uql_values_map->the_uql_get_count() == 0)
            return null;

        $result = true;
        
        foreach($this->uql_values_map->the_uql_get_map() as $name => $value) {

            $result = $this->the_uql_apply_rule($name,$value);

            if($result != UQL_RULE_SUCCESS)
                $this->uql_fail_rules_list->the_uql_add_element($name,$result);
        }

        if($this->the_uql_are_rules_passed())
            return true;

        $the_map =  $this->uql_fail_rules_list->the_uql_get_map();
        return new UQLRuleMessagesHandler($the_map);
    }

    public function __destruct() {
        $this->uql_values_map = null;
        $this->uql_rule_object = null;
    }
}

?>