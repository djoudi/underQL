<?php

class UQLDeleteQuery {

    private $query;
    private $abstract_entity;

    public function __construct(&$database_handle,&$abstract_entity) {
        if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
            die('Bad database handle');

        $this->query = new UQLQuery($database_handle);
        $this->abstract_entity = $abstract_entity;
        $this->values_map = new UQLMap();
    }


    protected function formatDeleteQuery($extra = null) {

        $delete_query = 'DELETE FROM `'.$this->abstract_entity->getEntityName().'`';
        if($extra != null)
            $delete_query .= ' WHERE '.$extra;

        return $delete_query;
    }


    public function delete($extra ='') {
        $query = $this->formatDeleteQuery($extra);
        return $this->query->executeQuery($query);
    }

    public function deleteWhereID($id,$id_name = 'id') {
        return $this->delete("`$id_name` = $id");
    }

}
?>