<?php

class UQLDeleteQuery extends UQLBase{

    private $uql_query;
    private $uql_abstract_entity;

    public function __construct(&$database_handle,&$abstract_entity) {
        if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
            $this->the_uql_error('Bad database handle');

        $this->uql_query = new UQLQuery($database_handle);
        $this->uql_abstract_entity = $abstract_entity;
    }

    protected function the_uql_format_delete_query($extra = null) {

        $delete_query = 'DELETE FROM `'.$this->uql_abstract_entity->the_uql_get_entity_name().'`';
        if($extra != null)
            $delete_query .= ' WHERE '.$extra;

        return $delete_query;
    }

    public function the_uql_delete($extra ='') {
        $query = $this->the_uql_format_delete_query($extra);
        return $this->uql_query->the_uql_execute_query($query);
    }

    public function the_uql_delete_where_id($id,$id_name = 'id') {
        return $this->the_uql_delete("`$id_name` = $id");
    }

    public function __destruct()
    {
        $this->uql_query = null;
        $this->uql_abstract_entity = null;
    }

}
?>