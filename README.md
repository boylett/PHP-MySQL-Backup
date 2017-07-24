# MySQL Dump
Nice and easy to use, this function creates an sql file containing a complete MySQL dump for the supplied database credentials.

### Usage
The following will create a dump at `backups/dump.sql`. If the database cannot be accessed, a `PDOException` will be thrown.
```php
mysql_dump('backups/dump.sql', 'localhost', 'test-database', 'username', 'password');
```

### Specifying Tables
To specify which tables to export, supply them as an array of strings as the final argument.
```php
mysql_dump('backups/users.sql', 'localhost', 'test-database', 'username', 'password', array('users'));
```

### Returns - Checking Your Dump
The function spits out the path to the created file if successful, or `false` in case of failure.
```php
if(mysql_dump('backups/dump.sql', 'localhost', 'test-database', 'username', 'password'))
{
    echo "I always feel better after a dump!";
}
else
{
    echo "I'm gonna need some prunes.";
}
```

### Arguments
|Argument|Description|
|---|---|
|`$filename`|The full path to the destination sql file|
|`$host`|The MySQL database host|
|`$database`|The name of the MySQL database to import to|
|`$username`|The database login username|
|`$password`|The database login password|

&nbsp;

# MySQL Import
This function imports an SQL file into a database using the supplied credentials.

### Usage
Assume we have an sql file laying around that we'd like to turn into a functioning database. We have MySQL and PHP installed, now what? Well, my friend, it's as easy as this:
```php
mysql_import('database-file.sql', 'localhost', 'test-database', 'username', 'password');
```

### Returns
`mysql_import` will return `true` if the import was successful, or `false` if the sql file supplied does not exist, was not readable or was empty, or if a query in the file throws a `PDOException`.

### Arguments
|Argument|Description|
|---|---|
|`$filename`|The full path to the target sql file|
|`$host`|The MySQL database host|
|`$database`|The name of the MySQL database to import to|
|`$username`|The database login username|
|`$password`|The database login password|