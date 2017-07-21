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

### Checking Your Dump
The function spits out the full filename of the sql file, meaning it is as simple as this to check for a successful dump:
```php
if(file_exists(mysql_dump('backups/dump.sql', 'localhost', 'test-database', 'username', 'password')))
{
    echo "I always feel better after a dump!";
}
else
{
    echo "I'm gonna need some prunes.";
}
```
