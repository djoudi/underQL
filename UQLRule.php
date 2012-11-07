<?php

class UQLRule {

    private $entity_name;
    private $alises_map;
    private $rules_map;

    public function __construct($entity_name) {
        $this->entity_name = $entity_name;
        $this->alises_map  = new UQLMap();
        $this->rules_map   = new UQLMap();
    }

    public function __call($function_name,$parameters) {
        $local_params_count = count($parameters);
        if($local_params_count == 0) return;

        $this->addRule($function_name, $parameters);
    }

    protected function addRule($field,$rule) {
        if(!$this->rules_map->isElementExist($field))
            $this->rules_map->addElement($field, new UQLMap());

        $local_rule = $this->rules_map->findElement($field);
        $local_rule->addElement($rule[0]/*rule name*/,$rule);

        $this->rules_map->addElement($field, $local_rule);
    }

    public function getRulesByFieldName($field_name) {
        return $this->rules_map->findElement($field_name);
    }

    public function addAlias($key, $value) {
        $this->alises_map->addElement($key, $value);
    }

    public function getAlias($key) {
        return $this->alises_map->findElement($key);
    }

    public function getRules() {
        return $this->alises_map;
    }

    public function getEntityName() {
        return $this->entity_name;
    }

    public function getAliases() {
        return $this->alises_map;
    }

    public static function findRuleObject($entity) {
        $rule_object_name = sprintf(UQL_RULE_OBJECT_SYNTAX,$entity);

        if(isset($GLOBALS[$rule_object_name]))
            $rule_object = $GLOBALS[$rule_object_name];
        else
            $rule_object = null;

        return $rule_object;

    }

    public function __destruct() {
        $this->entity_name = null;
        $this->rules_map = null;
        $this->alises_map = null;
    }
}

?>