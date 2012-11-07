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

    public function insert() {
        return $this->uql_change->insert();
    }

    public function insertOrUpdateFromArray($the_array,$extra = '',$is_save = true) {
        //$array_count = @count($the_array);
        foreach($the_array as $key => $value) {
            if($this->uql_abstract_entity->isFieldExist($key))
                $this->$key = $value;
        }

        if($is_save)
            return $this->insert();
        else
            return $this->update($extra);
    }

    public function insertFromArray($the_array) {
        return $this->insertOrUpdateFromArray($the_array,null);
    }

    public function updateFromArray($the_array,$extra ='') {
        return $this->insertOrUpdateFromArray($the_array,$extra,false);
    }

    public function updateFromArrayWhereID($the_array,$id,$id_name = 'id') {
        return $this->insertOrUpdateFromArray($the_array,"WHERE `$id_name` = $id",false);
    }

    public function update($extra = '') {
        return $this->uql_change->update($extra);
    }

    public function updateWhereID($id,$id_name = 'id') {
        return $this->uql_change->updateWhereID($id,$id_name);
    }

    public function delete($extra = '') {
        return $this->uql_delete->delete($extra);
    }

    public function deleteWhereID($id,$id_name = 'id') {
        return $this->uql_delete->deleteWhereID($id,$id_name);
    }

    public function query($query) {

        $this->uql_path = new UQLQueryPath($this->uql_database_handle,$this->uql_abstract_entity);
        if($this->uql_path->executeQuery($query))
            return $this->uql_path;
 
        return false;
    }

    public function select($fields = '*',$extra = '') {
        $query = sprintf("SELECT %s FROM `%s` %s",$fields,
                $this->uql_abstract_entity->getEntityName(),$extra);

        return $this->query($query);
    }

    public function selectWhereID($fields,$id,$id_name = 'id') {
        return $this->select($fields,"WHERE `$id_name` = $id");
    }

    public function areRulesPassed() {
        return $this->uql_change->areRulesPassed();
    }

    public function getMessagesList() {
        return $this->uql_change->getMessageList();
    }

    public function freeResources()
    {
        $this->uql_abstract_entity->freeResources();
        unset($this->uql_database_handle);
        if($this->uql_path != null)
                $this->uql_path->freeReources();
        $this->uql_change->freeResources();
        $this->uql_delete->freeResources();
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