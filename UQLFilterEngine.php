<?php

class UQLFilterEngine
{
  private $filter_object;
  private $values_map; //current inserted | updated $key => $value pairs
  private $in_out_flag; // specify if the engine for input or output
  
  public function __construct(&$filter_object,&$values_map,$in_out_flag)
  {
     $this->filter_object = $filter_object;
     $this->values_map = $values_map;
     $this->in_out_flag = $in_out_flag;
  }
  
  public function applyFilter($field_name,$value)
  {
     if($this->filter_object != null)
      $filters = $this->filter_object->getFiltersByFieldName($field_name);
     else
      return $value;
      
      $tmp_value = $value;
      
      foreach ($filters->getMap() as $key => $params)
      {
        $filter_flag = $params[1];
        if($filter_flag != $this->in_out_flag)
         continue;
         
        $filter_api_function = sprintf(UQL_FILTER_FUNCTION_NAME,$params[0]);
        
        if(!function_exists($filter_api_function))
         die($params[0].' is not a valid filter');
         
        if(@count($params) == 2) // the filter has no parameter(s)
         $tmp_value = $filter_api_function($field_name,$value,$filter_flag);
        else
         {
           $params = array_slice($params,2);
           $tmp_value = $filter_api_function($field_name,$value,$filter_flag,$params);
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