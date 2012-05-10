<?php

class UQLQueryPath{
	
	public $query_result;
	public $current_result_object;
	public $cureent_query_fields;
	
}


/*
 

$_->template->.. 
$_('pages_list','pages');
$_->query('xyz','....');
$_->output->pages_list;

$row = $_->input->newTo('student');
$row->id = 10;
$row->name = "Abdullah";
$row->saveIt();

$row = $_->input->modifyThe('pages');
$row->id = 10;
$row->name = "Ali";
$row->whereID(10);
$row->modifyIt();

$_->trash->deleteAll('student');
$_->trash->deleteById('student',10,'sid');
$_->trash->deleteWhere('student','WHERE HERE');

$_->input;
$_->output;
$_->trash;

$_->query('SELECT COUNT(id) AS cid FROM `student`');*/
?>