<?php

class UQLEntity extends UQLBase{
    
    private $uql_abstract_entity;
    private $uql_database_handle;
    private $uql_path;
    private $uql_change;
    private $uql_delete;

    public function __construct($entity_name,&$database_handle) {

        $this->uql_abstract_entity = new UQLAbstractEntity($entity_name,$database_handle);
        $this->uql_database_handle = $database_handle;
        $this->uql_path = null;
        $this->uql_change = new UQLChangeQuery($database_handle,$this->uql_abstract_entity);
        $this->uql_delete = new UQLDeleteQuery($database_handle,$this->uql_abstract_entity);
    }

    public function __set($name,$value) {
        $this->uql_change->$name = $value;
    }

    public function __get($name) {
        return $this->uql_change->$name;
    }

    public function the_uql_insert() {
        return $this->uql_change->the_uql_insert();
    }

    public function the_uql_insert_or_update_from_array($the_array,$extra = '',$is_save = true) {
        //$array_count = @count($the_array);
        foreach($the_array as $key => $value) {
            if($this->uql_abstract_entity->the_uql_is_field_exist($key))
                $this->$key = $value;
        }

        if($is_save)
            return $this->the_uql_insert();
        else
            return $this->the_uql_update($extra);
    }

    public function the_uql_insert_from_array($the_array) {
        return $this->the_uql_insert_or_update_from_array($the_array,null);
    }

    public function the_uql_update_from_array($the_array,$extra ='') {
        return $this->insert_or_update_from_array($the_array,$extra,false);
    }

    public function the_uql_update_from_array_where_id($the_array,$id,$id_name = 'id') {
        return $this->insert_or_update_from_array($the_array,"WHERE `$id_name` = $id",false);
    }

    public function the_uql_update($extra = '') {
        return $this->uql_change->the_uql_update($extra);
    }

    public function the_uql_update_where_id($id,$id_name = 'id') {
        return $this->uql_change->the_uql_update_where_id($id,$id_name);
    }

    public function the_uql_delete($extra = '') {
        return $this->uql_delete->the_uql_delete($extra);
    }

    public function the_uql_delete_where_id($id,$id_name = 'id') {
        return $this->uql_delete->the_uql_delete_where_id($id,$id_name);
    }

    public function the_uql_query($query) {

        $this->uql_path = new UQLQueryPath($this->uql_database_handle,$this->uql_abstract_entity);
        if($this->uql_path->the_uql_execute_query($query))
            return $this->uql_path;
 
        return false;
    }

    public function the_uql_select($fields = '*',$extra = '') {
        $query = sprintf("SELECT %s FROM `%s` %s",$fields,
                $this->uql_abstract_entity->the_uql_get_entity_name(),$extra);

        return $this->the_uql_query($query);
    }

    public function the_uql_select_where_id($fields,$id,$id_name = 'id') {
        return $this->the_uql_select($fields,"WHERE `$id_name` = $id");
    }

    public function the_uql_are_rules_passed() {
        return $this->uql_change->the_uql_are_rules_passed();
    }

    public function the_uql_get_messages_list() {
        return $this->uql_change->the_uql_get_messages_list();
    }

    public function the_uql_get_abstract_entity()
    {
      return $this->uql_abstract_entity;
    }
    
    public function __destruct() {
        $this->uql_abstract_entity = null;
        $this->uql_database_handle = null;
        $this->uql_path = null;
        $this->uql_change = null;
        $this->uql_delete = null;
    }

}

?>