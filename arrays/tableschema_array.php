<?php
/** 
 * Database tables information for past and new versions.
 * 
 * This file is not fully in use yet. The intention is to migrate it to the
 * installation class and rather than an array I will simply store every version
 * of each tables query. Each query can be broken down to compare against existing 
 * tables. I find this array approach too hard to maintain over many plugins.
 * 
 * @todo move this to installation class but also reduce the array to actual queries per version
 * 
 * @package WTG Tasks Manager
 * @author Ryan Bayne   
 * @since 0.0.1
 * @version 8.1.2
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
 
/*   Column Array Example Returned From "mysql_query( "SHOW COLUMNS FROM..."
        
          array(6) {
            [0]=>
            string(5) "row_id"
            [1]=>
            string(7) "int(11)"
            [2]=>
            string(2) "NO"
            [3]=>
            string(3) "PRI"
            [4]=>
            NULL
            [5]=>
            string(14) "auto_increment"
          }
                  
    +------------+----------+------+-----+---------+----------------+
    | Field      | Type     | Null | Key | Default | Extra          |
    +------------+----------+------+-----+---------+----------------+
    | Id         | int(11)  | NO   | PRI | NULL    | auto_increment |
    | Name       | char(35) | NO   |     |         |                |
    | Country    | char(3)  | NO   | UNI |         |                |
    | District   | char(20) | YES  | MUL |         |                |
    | Population | int(11)  | NO   |     | 0       |                |
    +------------+----------+------+-----+---------+----------------+            
*/
   
global $wpdb;   
$wtgtasksmanager_tables_array =  array();
##################################################################################
#                                 webtechglobal_log                                         #
##################################################################################        
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['name'] = $wpdb->prefix . 'webtechglobal_log';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['required'] = false;// required for all installations or not (boolean)
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['pluginversion'] = '0.0.1';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['primarykey'] = 'row_id';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['uniquekey'] = 'row_id';
// webtechglobal_log - row_id
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['row_id']['type'] = 'bigint(20)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['row_id']['null'] = 'NOT NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['row_id']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['row_id']['default'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['row_id']['extra'] = 'AUTO_INCREMENT';
// webtechglobal_log - outcome
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['outcome']['type'] = 'tinyint(1)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['outcome']['null'] = 'NOT NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['outcome']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['outcome']['default'] = '1';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['outcome']['extra'] = '';
// webtechglobal_log - timestamp
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['type'] = 'timestamp';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['null'] = 'NOT NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['default'] = 'CURRENT_TIMESTAMP';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['extra'] = '';
// webtechglobal_log - line
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['line']['type'] = 'int(11)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['line']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['line']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['line']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['line']['extra'] = '';
// webtechglobal_log - file
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['file']['type'] = 'varchar(250)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['file']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['file']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['file']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['file']['extra'] = '';
// webtechglobal_log - function
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['function']['type'] = 'varchar(250)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['function']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['function']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['function']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['function']['extra'] = '';
// webtechglobal_log - sqlresult
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['type'] = 'blob';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['extra'] = '';
// webtechglobal_log - sqlquery
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['extra'] = '';
// webtechglobal_log - sqlerror
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['type'] = 'mediumtext';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['extra'] = '';
// webtechglobal_log - wordpresserror
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['type'] = 'mediumtext';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['extra'] = '';
// webtechglobal_log - screenshoturl
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['type'] = 'varchar(500)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['extra'] = '';
// webtechglobal_log - userscomment
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['type'] = 'mediumtext';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['extra'] = '';
// webtechglobal_log - page
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['page']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['page']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['page']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['page']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['page']['extra'] = '';
// webtechglobal_log - version
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['version']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['version']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['version']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['version']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['version']['extra'] = '';
// webtechglobal_log - panelid
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelid']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelid']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelid']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelid']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelid']['extra'] = '';
// webtechglobal_log - panelname
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelname']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelname']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelname']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelname']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['panelname']['extra'] = '';
// webtechglobal_log - tabscreenid
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['extra'] = '';
// webtechglobal_log - tabscreenname
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['extra'] = '';
// webtechglobal_log - dump
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['dump']['type'] = 'longblob';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['dump']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['dump']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['dump']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['dump']['extra'] = '';
// webtechglobal_log - ipaddress
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['extra'] = '';
// webtechglobal_log - userid
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userid']['type'] = 'int(11)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userid']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userid']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userid']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['userid']['extra'] = '';
// webtechglobal_log - comment
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['comment']['type'] = 'mediumtext';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['comment']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['comment']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['comment']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['comment']['extra'] = '';
// webtechglobal_log - type
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['type']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['type']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['type']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['type']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['type']['extra'] = '';
// webtechglobal_log - category
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['category']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['category']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['category']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['category']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['category']['extra'] = '';
// webtechglobal_log - action
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['action']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['action']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['action']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['action']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['action']['extra'] = '';
// webtechglobal_log - priority
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['priority']['type'] = 'varchar(45)';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['priority']['null'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['priority']['key'] = '';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['priority']['default'] = 'NULL';
$wtgtasksmanager_tables_array['tables']['webtechglobal_log']['columns']['priority']['extra'] = '';              
?>