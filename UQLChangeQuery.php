<?php

class UQLChangeQuery extends UQLBase{

    private $uql_the_query;
    private $uql_the_abstract_entity;
    /*
	used to save list of [field_name = value] that are coming
	 from current insertion query
    */
    private $uql_the_values_map;
    private $uql_the_rule_engine;
    private $uql_the_rule_engine_results;


    public function __construct(&$database_handle,&$abstract_entity) {
        if((!$database_handle instanceof UQLConnection)
                ||
                (!$abstract_entity instanceof UQLAbstractEntity))
            die('Bad database handle');

        $this->uql_the_query = new UQLQuery($database_handle);
        $this->uql_the_abstract_entity = $abstract_entity;
        $this->uql_the_values_map = new UQLMap();
        $this->uql_the_rule_engine = null;
        $this->uql_the_rule_engine_results = null;
    }

    public function __set($name,$value) {
        if(!$this->uql_the_abstract_entity->isFieldExist($name))
            die($name.' is not a valid column name');

        $this->uql_the_values_map->addElement($name,$value);
       // echo '<pre>'; var_dump($this->uql_the_values_map); echo '</pre>';
    }

    public function __get($name) {
        if(!$this->uql_the_abstract_entity->isFieldExist($name))
            die($name.' is not a valid column name');

        if(!$this->uql_the_values_map->isElementExist($name))
            return null;
        else
            return $this->uql_the_values_map->findElement($name);

    }

    public function areRulesPassed() {
        if($this->uql_the_rules_engine != null)
            return $this->areRulesPassed();

        return true;
    }

    public function getMessagesList() {
        if(($this->uql_the_rules_engine != null)
                ||
                ($this->uql_the_rule_engine_results == true))
            return $this->uql_the_rule_engine_results;

        return null;

    }

    protected function formatInsertQuery() {
        $values_count = $this->uql_the_values_map->getCount();
        if($values_count == 0)
            return "";

        $insert_query = 'INSERT INTO `'.$this->uql_the_abstract_entity->getEntityName().'` (';

        $fields = '';
        $values = 'VALUES(';

        $all_values = $this->uql_the_values_map->getMap();
        $comma = 0; // for last comma in a string

        foreach($all_values as $key => $value) {
            $fields .= "`$key`";
            $field_object = $this->uql_the_abstract_entity->getFieldObject($key);
            if($field_object->numeric)
                $values .= $value;
            else // string quote
                $values .= "'$value'";

            $comma++;

            if(($comma) < $values_count) {
                $fields .= ',';
                $values .= ',';
            }
        }

        $values .= ')';

        $insert_query .= $fields.') '.$values;
        return $insert_query;
    }

    protected function insertOrUpdate($is_save = true,$extra = '') {
        $values_count = $this->uql_the_values_map->getCount();
        if($values_count == 0)
            return false;

        $rule_object = UQLRule::findRuleObject($this->uql_the_abstract_entity->getEntityName());

        if($rule_object != null) {
            $this->uql_the_rule_engine = new UQLRuleEngine($rule_object,
                    $this->uql_the_values_map);

            $this->uql_the_rule_engine_results = $this->uql_the_rule_engine->runEngine();

            if(!$this->uql_the_rule_engine->areRulesPassed())
                return false;
        }

        $filter_object = UQLFilter::findFilterObject($this->uql_the_abstract_entity->getEntityName());

        if($filter_object != null) {
            $fengine = new UQLFilterEngine($filter_object,UQL_FILTER_IN);
            $fengine->setValuesMap($this->uql_the_values_map);
            $this->uql_the_values_map = $fengine->runEngine();
        }

        if($is_save)
            $query = $this->formatInsertQuery();
        else
            $query = $this->formatUpdateQuery($extra);

        // clear values
        $this->uql_the_values_map = new UQLMap();

        return $this->uql_the_query->executeQuery($query);
    }

    public function insert() {
        return $this->insertOrUpdate();
    }

    protected function formatUpdateQuery($extra = '') {
        $values_count = $this->uql_the_values_map->getCount();
        if($values_count == 0)
            return "";

        $update_query = 'UPDATE `'.$this->uql_the_abstract_entity->getEntityName().'` SET ';

        $fields = '';

        $all_values = $this->uql_the_values_map->getMap();
        $comma = 0; // for last comma in a string

        foreach($all_values as $key => $value) {
            $fields .= "`$key` = ";
            $field_object = $this->uql_the_abstract_entity->getFieldObject($key);
            if($field_object->numeric)
                $fields .= $value;
            else // string quote
                $fields .= "'$value'";

            $comma++;

            if(($comma) < $values_count) {
                $fields .= ',';
            }
        }

        $update_query .= $fields.' '.$extra;

        return $update_query;
    }


    public function update($extra ='') {
        return $this->insertOrUpdate(false,$extra);
    }

    public function updateWhereID($id,$id_name = 'id') {
        return $this->update("WHERE `$id_name` = $id");
    }

    public function freeResources()
    {
        $this->uql_the_query->freeResources();
        unset($this->uql_the_abstract_entity);
        $this->uql_the_values_map->freeResources();
        unset($this->uql_the_rule_engine);
        unset($this->uql_the_rule_engine_results);
    }

    public function __destruct() {
        $this->freeResources();
        $this->uql_the_query = null;
        $this->uql_the_abstract_entity = null;
        $this->uql_the_values_map = null;
        $this->uql_the_rule_engine = null;
        $this->uql_the_rule_engine_results = null;
    }

}
?>