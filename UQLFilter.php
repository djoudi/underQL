<?php

class UQLFilter extends UQLBase{

    private $uql_entity_name;
    private $uql_filters_map;

    public function __construct($entity_name) {
        $this->uql_entity_name = $entity_name;
        $this->uql_filters_map   = new UQLMap();
    }

    public function __call($function_name,$parameters) {
        $local_params_count = count($parameters);
        if($local_params_count < 2 /*filter_type (in | out) and filter_name [AT LEAST]*/)
            $this->error($function_name.' filter must have 2 parameters at least');

        $this->addFilter($function_name, $parameters);
    }

    protected function addFilter($field,$filter) {
        if(!$this->uql_filters_map->isElementExist($field))
            $this->uql_filters_map->addElement($field, new UQLMap());

        $local_filter = $this->uql_filters_map->findElement($field);
        $local_filter->addElement($filter,array('is_active'=>true));
        $this->uql_filters_map->addElement($field, $local_filter);
    }

    public function setFilterActivation($field_name,$filter_name,$activation)
    {
        $local_filter = $this->uql_filters_map->findElement($field);
        if(!$local_filter)
            $this->error('You can not stop a filter for unknown field ('.$field_name.')');

        $target_filter = $local_filter->findElement($filter_name);
        if(!$target_filter)
            $this->error('You can not stop unknown filter ('.$filter_name.')');


        $local_filter->addElement($filter_name,array('is_active'=> $activiation));
        $this->uql_filters_map->addElement($field, $local_filter);
    }

    public function startFilter($field_name,$filter_name)
    {
        $this->setFilterActivitation($filed_name,$filter_name,true);
    }

    public function stopFilter($field_name,$filter_name)
    {
        $this->setFilterActivitation($filed_name,$filter_name,false);
    }

    public function getFiltersByFieldName($field_name) {
        return $this->uql_filters_map->findElement($field_name);
    }

    public function getFilters() {
        return $this->uql_filters_map;
    }

    public function getEntityName() {
        return $this->uql_entity_name;
    }

    public static function findFilterObject($entity) {
        $filter_object_name = sprintf(UQL_FILTER_OBJECT_SYNTAX,$entity);
        if(isset($GLOBALS[$filter_object_name]))
            $filter_object = $GLOBALS[$filter_object_name];
        else
            $filter_object = null;

        return $filter_object;
    }

    public function __destruct() {
        $this->uql_entity_name = null;
        $this->uql_filters_map = null;
    }
}

?>