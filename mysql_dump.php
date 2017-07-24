<?php

	/**
	 * Dumps a MySQL database to an SQL file
	 * 1.0.2  |  2017-07-21  |  Ryan Boylett <boylett.uk>
	 */

	function mysql_dump($filename = 'dump.sql', $host = 'localhost', $database = 'test', $username = 'root', $password = 'root', $tables = array())
	{
		ini_set('memory_limit', '5120M');
		set_time_limit(0);

		$chunk_size = 200;

		$out = fopen($filename, 'w');
		$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);

		fwrite($out, '# Database Dump' . "\n" .
			'# ' . $database . ' @ ' . $host . "\n" . "\n");

		fclose($out);

		if(empty($tables))
		{
			$tables = $pdo->query('SHOW TABLES');
		}

		foreach($tables as $table)
		{
			if(is_array($table))
			{
				$table = $table[0];
			}

			$out = fopen($filename, 'a');

			fwrite($out, '# ' . $table . "\n" .
				'# ' . str_repeat('-', strlen($table)) . "\n" . "\n" .
				'DROP TABLE IF EXISTS `' . $table . '`;' . "\n" . "\n");

			foreach($pdo->query('SHOW CREATE TABLE `' . $table . '`') as $string)
			{
				fwrite($out, $string[1] . ';' . "\n" . "\n");
			}

			$columns = array();

			foreach($pdo->query("DESCRIBE `{$table}`") as $d)
			{
				$columns[] = $d['Field'];
			}

			$has_rows = false;

			foreach($pdo->query('SELECT NULL FROM `' . $table . '` LIMIT 1') as $row)
			{
				$has_rows = true;
			}

			$chunk_index     = 0;
			$chunk_iteration = 0;

			if($has_rows)
			{
				fwrite($out, 'LOCK TABLES `' . $table . '` WRITE;' . "\n" .
					'/' . '*!40000 ALTER TABLE `' . $table . '` DISABLE KEYS *' . '/;' . "\n" . "\n");

				$row = NULL;

				foreach($pdo->query('SELECT * FROM `' . $table . '`', PDO::FETCH_ASSOC) as $row)
				{
					if($chunk_index == 0)
					{
						if($chunk_iteration > 0)
						{
							fwrite($out, ';' . "\n" . "\n");
						}

						fwrite($out, 'INSERT INTO `' . $table . '` (`' . implode('`, `', $columns) . '`)' . "\n" .
							'VALUES' . "\n");
					}
					else
					{
						fwrite($out, ',' . "\n");
					}

					fwrite($out, "\t" . '(');

					$c = 0;

					foreach($row as $value)
					{
						if($c > 0)
						{
							fwrite($out, ',');
						}

						fwrite($out, "'" . preg_replace(array
						(
							"/(\r\n|\r|\n)/",
							"/\t/"
						), array
						(
							"\\n",
							"\\t"
						), addslashes($value)) . "'");

						$c ++;
					}

					fwrite($out, ')');

					$chunk_index ++;

					if($chunk_index >= $chunk_size)
					{
						$chunk_index     = 0;
						$chunk_iteration ++;
					}

					$row = NULL;
				}

				fwrite($out, ';' . "\n" . "\n");

				fwrite($out, '/' . '*!40000 ALTER TABLE `' . $table . '` ENABLE KEYS *' . '/;' . "\n" .
					'UNLOCK TABLES;' . "\n" . "\n");
			}

			fwrite($out, "\n");

			fclose($out);
		}

		$out = NULL;
		$pdo = NULL;

		return $filename;
	}