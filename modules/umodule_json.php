<?php 


class umodule_json extends UQLModule implements IUQLModule{

 private $json_source;
 
 public function getSource()
 {
   return $this->json_source;
 }
 
 private function formatJSONField($field,$value,$is_string)
 {
   if($is_string)
    $json_f = sprintf('"%s": "%s"',$field,$value);
   else
     $json_f = sprintf('"%s": %s',$field,$value);
     
   return $json_f;
 }
 
 private function formatJSONRow(&$path)
 {
   if(!$path) return "";
   
   $fields = $path->_('get_current_query_fields');
   $fields_count = @count($fields);
   if(!$fields || $fields_count == 0)
    return "";
    
    $json_r = '{ ';
    $abstract_entity = $path->_('get_abstract_entity');
    
    if(!$abstract_entity)
     return "";
     
    for($i = 0; $i < $fields_count; $i++)
    {
      $field_object = $abstract_entity->_('get_field_object',$fields[$i]);
      if($field_object->numeric)
        $json_r .= $this->formatJSONField($fields[$i],$path->$fields[$i],false);
      else
        $json_r .= $this->formatJSONField($fields[$i],$path->$fields[$i],true);
        
      if(($i + 1) != $fields_count) $json_r .= ',';
    }
    
    $json_r .= ' }'."\n";
    
    return $json_r;
 }
 
 public function init()
 {
   $this->json_source = "";
   $this->um_is_input = false;
 }
 
 public function in(&$values,$is_insert = true)
 {
   // No implementation
 }
 
 public function out(&$path)
 { 
   $e = $path->_('get_abstract_entity')->_('get_entity_name');
   $this->json_source  = '{"'.$e.'" :['."\n";
   $fields_count = $path->_('get_count');
   
   while($path->_('get_next'))
   {
     $this->json_source .= $this->formatJSONRow($path);
     $fields_count--;
     if($fields_count > 0) $this->json_source .= ',';
   }
    
   $this->json_source .= ']}';
 }
 
 public function shutdown()
 {
   $this->json_source = null;
 }
}

?>