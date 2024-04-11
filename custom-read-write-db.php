<?php
/*
Plugin Name: Custom Read Write DB
Description: WordPress plugin to separate read and write database connections.
Version: 1.0
Author: Hiroshi Ozeki
*/
// Define custom database class
class Custom_Read_Write_DB extends wpdb {
    public $write_connection;
    public $read_connection;

    function __construct() {
        // Initialize the master database (write connection)
        $this->write_connection = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

        // Initialize the read database
        $this->read_connection = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST_READ);
    }

    // Custom query routing logic
    function query($query) {
        // Log the query for debugging
        error_log('Custom_Read_Write_DB: Query: ' . $query);

        // Determine if query is a read operation
        if ($this->is_read_query($query)) {
            // Log that the query is sent to the read database
            error_log('Custom_Read_Write_DB: Sending query to read database');

            // Execute the query on the read connection
            return $this->read_connection->query($query);
        } else {
            // Log that the query is sent to the write database
            error_log('Custom_Read_Write_DB: Sending query to write database');

            // Execute the query on the write connection (master database)
            return $this->write_connection->query($query);
        }
    }

    // Logic to determine if a query is for reading
    function is_read_query($query) {
        // Check if the query starts with "SELECT" or contains "DESCRIBE" or "EXPLAIN"
        return (preg_match('/^\s*SELECT|DESCRIBE|EXPLAIN/i', $query) === 1);
    }
}

// Filter to return custom database class instance
function custom_read_write_db_init($wpdb) {
    return new Custom_Read_Write_DB();
}
add_filter('wpdb', 'custom_read_write_db_init');
