<?php

	/**
	 * Imports an SQL file to a MySQL Database
	 * 1.0.0  |  2017-07-24  |  Ryan Boylett <boylett.uk>
	 */

	function mysql_import($filename, $host, $database, $username, $password)
	{
		if(!file_exists($filename))
		{
			return false;
		}
		
		ini_set('memory_limit', '5120M');
		set_time_limit(0);

		$file    = new SplFileObject($filename);
		$queries = NULL;

		if($file)
		{
			$queries       = array();
			$current_query = array();

			while(!$file->eof())
			{
				$line = trim($file->fgets());

				if(strlen($line) > 0 and $line[0] != '#' and $line[0] != '/')
				{
					$current_query[] = $line;

					if(substr($line, -1) == ';')
					{
						if(!empty($current_query))
						{
							$queries[] = implode("\n", $current_query);
						}

						$current_query = array();
					}
				}
			}

			$current_query =
			$line          =
			$file          = NULL;
		}
		else
		{
			return false;
		}

		if($queries !== NULL)
		{
			$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);

			foreach($queries as $query)
			{
				if(!$pdo->query($query))
				{
					return false;
				}
			}
			
			$pdo = NULL;

			return true;
		}

		return false;
	}