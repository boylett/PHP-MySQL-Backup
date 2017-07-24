<?php

	header("Content-Type: text/plain");

	include 'mysql_import.php';
	
	if(mysql_import('test.sql', 'localhost', 'test', 'root', 'root'))
	{
		echo 'Imported';
	}
	else
	{
		echo 'Failed :(';
	}