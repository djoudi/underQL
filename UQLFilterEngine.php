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

        foreach ($filters->getMap() as $filter_name => $filter_value) {
            $filter_flag = $filter_value['filter'][1];
           // echo $filter_flag;
            if(strcmp(strtolower($filter_flag),'in') == 0)
                    $filter_flag = UQL_FILTER_IN;
            else if(strcmp(strtolower($filter_flag),'out') == 0)
                     $filter_flag = UQL_FILTER_OUT;
            else
                 $filter_flag = UQL_FILTER_IN | UQL_FILTER_OUT;
            
               if((!$filter_value['is_active'])
                ||(($filter_flag != $this->uql_in_out_flag) &&($filter_flag != UQL_FILTER_IN|UQL_FILTER_OUT)))
                continue;

            $filter_api_function = sprintf(UQL_FILTER_FUNCTION_NAME,$filter_name);
            
            if(!function_exists($filter_api_function))
                die($filter_name.' is not a valid filter');

            $include_filter_api = 'include_filter';
            $include_filter_api($filter_name);
            if(@count($filter_value['filter']) == 2) // the filter has no parameter(s)
                $tmp_value = $filter_api_function($field_name,$value,$filter_flag);
            else {
                $params = array_slice($filter_value['filter'],2);
                $tmp_value = $filter_api_function($field_name,$value,$filter_flag,$params);
            }
        }
        return $tmp_value;
    }

    public function runEngine() {
        if(!$this->uql_values_map || $this->uql_values_map->getCount() == 0)
            return null;


        foreach($this->uql_values_map->getMap() as $name => $value) {
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