<?php

class UQLQueryPath{

 private $abstract_entity;
 private $query;
 private $columns_buffer;
}

$_('student','student');

$sinfo = $_->path->student;

$sinfo->id;
$sinfo->name;

 UQLQueryPath $path = new UQLQueryPath('student',$db_handle);

 $path->

?>