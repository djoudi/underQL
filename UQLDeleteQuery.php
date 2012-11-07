<?php

class UQLDeleteQuery {

    private $uql_query;
    private $uql_abstract_entity;

    public function __construct(&$database_handle,&$abstract_entity) {
        if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
            die('Bad database handle');

        $this->uql_query = new UQLQuery($database_handle);
        $this->uql_abstract_entity = $abstract_entity;
    }


    protected function formatDeleteQuery($extra = null) {

        $delete_query = 'DELETE FROM `'.$this->uql_abstract_entity->getEntityName().'`';
        if($extra != null)
            $delete_query .= ' WHERE '.$extra;

        return $delete_query;
    }


    public function delete($extra ='') {
        $query = $this->formatDeleteQuery($extra);
        return $this->uql_query->executeQuery($query);
    }

    public function deleteWhereID($id,$id_name = 'id') {
        return $this->delete("`$id_name` = $id");
    }

}
?>