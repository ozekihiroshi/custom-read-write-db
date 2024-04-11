# custom-read-write-db

This program is a plugin that simply separates write and read processing in WordPress DB. I assume autoscaling processing with reader on AWS RDS.

This program is a plugin that simply separates write and read processing in WordPress DB. I assume autoscaling processing with reader on AWS RDS.
Add the following to wp-config.php.
```
define('DB_HOST_READ', 'read-only-db-host');
```
