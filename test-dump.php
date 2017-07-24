<?php

	header("Content-Type: text/plain");

	include 'mysql_dump.php';
	
	if(mysql_dump('dump.sql', 'localhost', 'test', 'root', 'root'))
	{
		echo 'Dumped';
	}
	else
	{
		echo 'Failed :(';
	}