<?php

class UQLFilterEngine
{
  private $filter_object;
  private $values_map; //current inserted | updated $key => $value pairs
  
  public function __construct(&$filter_object,&$values_map)
  {
     $this->filter_object = $filter_object;
     $this->values_map = $values_map;
  }
  
  protected function applyFilter($field_name,$value)
  {
     $filters = $this->filter_object->getFiltersByFieldName($field_name);
     
     if($filters == null)
      return $value;
      
      $tmp_value = $value;
      
      foreach ($filters->getMap() as $key => $params)
      {
        $filter_api_function = sprintf(UQL_FILTER_FUNCTION_NAME,$params[0]);
        
        if(!function_exists($filter_api_function))
         return $tmp_value;
         
        if(@count($params) == 2) // the filter has no parameter(s)
         $tmp_value = $filter_api_function($field_name,$tmp_value,$params[1]);
        else
         {
           $filter_flag = $params[1];

           $params = array_shift($params); //delete filter name
           $params = array_shift($params); // delete in-out flag
           
           $tmp_value = $filter_api_function($field_name,$tmp_vaue,$filter_flag,$params);
         }
      }
      return $tmp_value;
  }
  
  public function runEngine()
  {
     if(!$this->values_map || $this->values_map->getCount() == 0)
      return null;
      
     
      foreach($this->values_map->getMap() as $name => $value)
        {
         // echo $this->applyFilter($name,$value).'<br />';
          $this->values_map->addElement($name,$this->applyFilter($name,$value));
        }
      return $this->values_map;
  }
  
  public function __destruct()
  {
    $this->values_map = null;
    $this->filter_object = null;
  }
}

?>