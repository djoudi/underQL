<?php

class UQLFilterEngine extends UQLBase{
    
    private $uql_filter_object;
    private $uql_values_map; //current inserted | updated $key => $value pairs
    private $uql_in_out_flag; // specify if the engine for input or output

    public function __construct(&$filter_object,$in_out_flag) {
        $this->uql_filter_object = $filter_object;
        $this->uql_values_map = null;
        $this->uql_in_out_flag = $in_out_flag;
    }

    public function setValuesMap(&$values_map) {
        $this->uql_values_map = $values_map;
    }

    public function applyFilter($field_name,$value) {
        if($this->uql_filter_object != null)
            $filters = $this->uql_filter_object->getFiltersByFieldName($field_name);
        else
            return $value;

        if($filters == null)
            return $value;

        $tmp_value = $value;

        foreach ($filters->getMap() as $key => $params) {
            $filter_flag = $params[1];
            if($filter_flag != $this->uql_in_out_flag)
                continue;

            $filter_api_function = sprintf(UQL_FILTER_FUNCTION_NAME,$params[0]);

            if(!function_exists($filter_api_function))
                die($params[0].' is not a valid filter');

            if(@count($params) == 2) // the filter has no parameter(s)
                $tmp_value = $filter_api_function($field_name,$value,$filter_flag);
            else {
                $params = array_slice($params,2);
                $tmp_value = $filter_api_function($field_name,$value,$filter_flag,$params);
            }
        }
        return $tmp_value;
    }

    public function runEngine() {
        if(!$this->uql_values_map || $this->uql_values_map->getCount() == 0)
            return null;


        foreach($this->uql_values_map->getMap() as $name => $value) {
            // echo $this->applyFilter($name,$value).'<br />';
            $this->uql_values_map->addElement($name,$this->applyFilter($name,$value));
        }
        return $this->uql_values_map;
    }

    public function __destruct() {
        $this->uql_values_map = null;
        $this->uql_filter_object = null;
    }
}

?>