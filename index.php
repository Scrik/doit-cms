<?php
	$start_time = microtime(true);
	error_reporting(E_ALL);
	
	include_once ('config.php');
	include_once ('cms/cms.php');
	
	$status = explode('  ', mysql_stat());
	$status = (explode(' ', $status[2]));
	$na4xtat =  $status[1];
	
	header('Content-type: text/html; Charset=UTF-8');

	print doit()->main(); 

	
	$exec_time = microtime(true) - $start_time;
	$status = explode('  ', mysql_stat());
	$status = (explode(' ', $status[2]));
	$na4xtat = ( 1 * $status[1])-$na4xtat;
	
	printf("<!-- %f seconds, %d bytes, %d queries -->",$exec_time, memory_get_usage(true),$na4xtat);
