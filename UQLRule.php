<?php

class UQLRule {

    private $uql_entity_name;
    private $uql_alises_map;
    private $uql_rules_map;

    public function __construct($entity_name) {

        $this->uql_entity_name = $entity_name;
        $this->uql_alises_map  = new UQLMap();
        $this->uql_rules_map   = new UQLMap();
    }

    public function __call($function_name,$parameters) {

        $local_params_count = count($parameters);
        if($local_params_count == 0) return;

        $this->addRule($function_name, $parameters);
    }

    protected function addRule($field,$rule) {

        if(!$this->uql_rules_map->isElementExist($field))
            $this->uql_rules_map->addElement($field, new UQLMap());

        $local_rule = $this->uql_rules_map->findElement($field);
        $local_rule->addElement($rule[0]/*rule name*/,$rule);

        $this->uql_rules_map->addElement($field, $local_rule);
    }

    public function getRulesByFieldName($field_name) {

        return $this->uql_rules_map->findElement($field_name);
    }

    public function addAlias($key, $value) {

        $this->uql_alises_map->addElement($key, $value);
    }

    public function getAlias($key) {

        return $this->uql_alises_map->findElement($key);
    }

    public function getRules() {
        return $this->uql_alises_map;
    }

    public function getEntityName() {
        return $this->uql_entity_name;
    }

    public function getAliases() {
        return $this->uql_alises_map;
    }

    public static function findRuleObject($entity) {
        
        $rule_object_name = sprintf(UQL_RULE_OBJECT_SYNTAX,$entity);

        if(isset($GLOBALS[$rule_object_name]))
            $rule_object = $GLOBALS[$rule_object_name];
        else
            $rule_object = null;

        return $rule_object;

    }

    public function freeResources()
    {
        unset($this->uql_entity_name);
        $this->uql_alises_map->freeResources();
        $this->uql_rules_map->freeResources();
    }

    public function __destruct() {
        
        $this->uql_entity_name = null;
        $this->uql_rules_map = null;
        $this->uql_alises_map = null;
    }
}

?>