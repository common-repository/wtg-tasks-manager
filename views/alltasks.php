<?php
/**
 * All Tasks [page]   
 *
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * All Tasks [page] 
 * 
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne
 * @since 0.0.1
 */
class WTGTASKSMANAGER_Alltasks_View extends WTGTASKSMANAGER_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 0.0.1
     *
     * @var int
     */
    protected $screen_columns = 1;
    
    protected $view_name = 'alltasks';
    
    public $purpose = 'normal';// normal, dashboard

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function meta_box_array() {
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            //array( $this->view_name . '-example', __( 'WYSIWYG Editor', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'wysiwygeditor' ), true, 'activate_plugins' ),
        );    
    }
        
    /**
     * Set up the view with data and do things that are specific for this view
     *
     * @since 0.0.1
     *
     * @param string $action Action for this view
     * @param array $data Data for this view
     */
    public function setup( $action, array $data ) {
        global $tasksmanager_settings;
        
        // create constant for view name
        if(!defined( "WTGTASKSMANAGER_VIEWNAME") ){define( "WTGTASKSMANAGER_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->WTGTASKSMANAGER = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER', 'class-wtgtasksmanager.php', 'classes' );
        $this->UI = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_UI', 'class-ui.php', 'classes' ); 
        $this->DB = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_PHP', 'class-phplibrary.php', 'classes' );
        
        parent::setup( $action, $data );

        // create a data table ( use "head" to position before any meta boxes and outside of meta box related divs)
        $this->add_text_box( 'head', array( $this, 'datatables' ), 'normal' );
               
        // using array register many meta boxes
        foreach( self::meta_box_array() as $key => $metabox ) {
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        } 
    }
                         
    /**
    * Use this to display one or more tables of data.
    * 
    * @author Ryan R. Bayne
    * @package Project Task Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function datatables( $data, $box ) {    
        global $wpdb; 
         
        // begin table
        $WPTableObject = new WTGTASKSMANAGER_WPTable_AllTasks();
        
        // process bulk actions/requests (before data is queried for display)
        $WPTableObject->process_bulk_action();
        $WPTableObject->UI = $this->UI;
                
        // get data for display
        $data = $WPTableObject->get_data();
                    
        // prepare items using filters
        if( $data ){
            $WPTableObject->prepare_items_further( $data, $WPTableObject->per_page );
        }
        ?>
        
        <form method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <?php
            $WPTableObject->search_box( 'search', 'theidhere' ); 
            $WPTableObject->display(); 
            ?>
        </form>
 
        <?php  
    }
        
    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function metaboxes() {
        parent::register_metaboxes( self::meta_box_array() );     
    }

    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class. 
    * 
    * @uses dashboard_widgets() in parent class WTGTASKSMANAGER_View which loops through meta boxes and registeres widgets
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function dashboard() { 
        parent::dashboard_widgets( self::meta_box_array() );  
    }
     
    /**
    * All add_meta_box() callback to this function to keep the add_meta_box() call simple.
    * 
    * This function also offers a place to apply more security or arguments.
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    function parent( $data, $box ) {
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    }
     
    /**
    * Example meta box
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_alltasks_example( $data, $box ) {    

    } 
}

/**
* Displays table of All Tasks.
* 
* @author Ryan R. Bayne
* @package WTG Tasks Manager
* @since 0.0.1
* @version 1.1
* 
* @link http://codex.wordpress.org/Class_Reference/WP_List_Table
*/
class WTGTASKSMANAGER_WPTable_AllTasks extends WP_List_Table {
    
    public 
        $genuine_actions = array( 'canceltasks' ),
        $per_page = 20;
        
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct() {

        $this->WTGTASKSMANAGER = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER', 'class-wtgtasksmanager.php', 'classes' ); # plugin specific functions
  
        $this->UI = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_UI', 'class-ui.php', 'classes' ); 
        $this->REQUESTS = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Requests', 'class-requests.php', 'classes' );
              
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'task',     //singular name of the listed records
            'plural'    => 'tasks',    //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
        ) );
        
    }
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default( $item, $column_name ){   
        global $wpdb;
        
        $attributes = "class=\"$column_name column-$column_name\"";
                
        switch( $column_name ){
            
            case 'ID':
                return $item->ID;    
                break;
            case 'post_title':
                return $item->post_title;
                break;            
            case 'post_status':
                return $this->human_status( $item->post_status );
                break;                
            case 'post_date':
                return $item->post_date;    
                break;                    
            case 'wtgtaskproject':
          
                $project_id = get_post_meta( $item->ID, 'wtgprojectid', true );
                 
                if( is_numeric( $project_id ) ) {    
                    $tablename = $wpdb->webtechglobal_projects;
                    return $wpdb->get_var( "SELECT projectname FROM $tablename WHERE project_id = $project_id" );    
                }
                
                return '';
                break;
            case 'taskpriority':
                $priority = get_post_meta( $item->ID, 'wtgpriority', true );
                 
                if( is_numeric( $priority ) ) {    
                    switch( $priority ) {
                        case 1:
                            return __( 'Urgent', 'wtgtasksmanager' );
                            break;
                        case 2:
                            return __( 'High', 'wtgtasksmanager' );
                            break;
                        case 3:
                            return __( 'Important', 'wtgtasksmanager' );
                            break;
                        case 4:
                            return __( 'Low', 'wtgtasksmanager' );
                            break;
                        case 5:
                            return __( 'Optional', 'wtgtasksmanager' );
                            break;
                    }    
                }
                
                return 'Low';    
                break;                                            
            default:
                return 'No column function or default setup in switch statement';
        }
    }
                    
    /** ************************************************************************
    * Recommended. This is a custom column method and is responsible for what
    * is rendered in any column with a name/slug of 'title'. Every time the class
    * needs to render a column, it first looks for a method named 
    * column_{$column_title} - if it exists, that method is run. If it doesn't
    * exist, column_default() is called instead.
    * 
    * This example also illustrates how to implement rollover actions. Actions
    * should be an associative array formatted as 'slug'=>'link html' - and you
    * will need to generate the URLs yourself. You could even ensure the links
    * 
    * 
    * @see WP_List_Table::::single_row_columns()
    * @param array $item A singular item (one full row's worth of data)
    * @return string Text to be placed inside the column <td> (movie title only )
    **************************************************************************/
    /*
    function column_title( $item){

    } */
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => 'ID',
            'post_status' => __( 'Status', 'wtgtasksmanager' ),
            'post_title'     => __( 'Task Title', 'wtgtasksmanager' ),            
            'post_date' => __( 'Task Created', 'wtgtasksmanager' ),
            'wtgtaskproject' => __( 'Project Name', 'wtgtasksmanager' ),
            'taskpriority' => __( 'Priority', 'wtgtaskmanager' ),
        );
        
        /*
        if( isset( $this->action ) ){
            $columns['action'] = 'Action';
        } 
        */                                      
           
        return $columns;
    }
    
    function column_cb( $item ) {     
        return sprintf(                        
            '<input type="checkbox" name="tasks[]" value="%s" />', $item->ID
        );    
    }
     
    function column_ID( $item ) {
        $actions = array(                      
            'edit'      => sprintf( '<a href="post.php?post=%s&action=edit" class="button wtgtasksmanagerbutton">Edit</a>', $item->ID )
        );
            
        // add another action button based on the tasks state
        if( $item->post_status == 'newtask' &&  $item->post_status == 'publish' )
        {        
            $actions['canceltask'] = $this->linkaction( 'wtgtasksmanager_alltasks', 'canceltask', __( 'Cancel this task.', 'wtgtasksmanager' ), __( 'Cancel', 'wtgtasksmanager' ), $values = '&task=' . $item->ID );
        }
        
        if( $item->post_status == 'newtask' )
        {
            $actions['starttask'] = $this->linkaction( 'wtgtasksmanager_alltasks', 'starttask', __( 'Begin this task.', 'wtgtasksmanager' ), __( 'Start', 'wtgtasksmanager' ), $values = '&task=' . $item->ID );
        }
        
        if( $item->post_status == 'finishedtask' )
        {    
             $actions['closetask'] = $this->linkaction( 'wtgtasksmanager_alltasks', 'closetask', __( 'Close this task.', 'wtgtasksmanager' ), __( 'Close', 'wtgtasksmanager' ), $values = '&task=' . $item->ID );
        }
        
        if( $item->post_status == 'startedtask' )
        {
             $actions['finishtask'] = $this->linkaction( 'wtgtasksmanager_alltasks', 'finishtask', __( 'Finish this task.', 'wtgtasksmanager' ), __( 'Finish', 'wtgtasksmanager' ), $values = '&task=' . $item->ID );
        }
 
        if( $item->post_status == 'cancelledtask' || $item->post_status == 'closedtask' )
        {
             $actions['continuetask'] = $this->linkaction( 'wtgtasksmanager_alltasks', 'continuetask', __( 'Continue this task.', 'wtgtasksmanager' ), __( 'Continue', 'wtgtasksmanager' ), $values = '&task=' . $item->ID );
        }        
    
        return sprintf( '%1$s %2$s', $item->ID, $this->row_actions( $actions ) );
    }
    
    function get_data() {
        $meta_key = '';
        $meta_value = '';
        $project_focus_id = get_user_meta( get_current_user_id(), 'wtgprojectfocus', true );
        if( $project_focus_id )
        {
            if( is_numeric( $project_focus_id ) )
            {
                $meta_key = 'wtgprojectid';
                $meta_value = $project_focus_id;                
            }    
        }
                
        // get all tasks posts
        $data = array();
              
        $args = array(
        'posts_per_page'   => $this->per_page,
        //'offset'           => $this->get_pagenum(),
        'category'         => '',
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => $meta_key,
        'meta_value'       => $meta_value,
        'post_type'        => 'wtgtasks',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'post_status'      => 'all',
        'suppress_filters' => true ); 

        return get_posts( $args );
    }
 
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items_further() and sort
     * your data accordingly (usually by modifying your query ).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array( 'data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'taskpriority'     => array( 'taskpriority', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'canceltasks' => __( 'Cancel Tasks', 'wtgtasksmanager' )
        );
        return $actions;
    }
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items_further()
     **************************************************************************/
     
    /**
    * Process bulk actions, an optional approach but choosing by WebTechGlobal for
    * the sake of a less confusing design.
    * 
    * Call this method before prepare_items_further() and before querying the set of 
    * data passed to it. 
    * 
    * @link http://wordpress.stackexchange.com/questions/76374/wp-list-tables-bulk-actions
    */
    function process_bulk_action() {
        // return quickly if no bulk processing requested
        $current_action = $this->current_action();
        if( $current_action === false ) {
            return;    
        }
        
        // ensure the action is genuine for this class/table
        if( !in_array( $current_action, $this->genuine_actions ) ) {
            return; 
        }
        
        // ensure nonce value in URL
        if ( !isset( $_GET['_wpnonce'] ) ) {
            wp_die( 'WTG Tasks Manager could not continue because a security check failed! This may be temporary.' );
        }
        
        // ensure nonce is a string 
        if( !is_string( $_GET['_wpnonce'] ) ) {
            wp_die( 'WordPress security detected a problem. Please return to WTG Tasks Manager and try again if you feel this is a mistake!' );    
        }
        
        // filter nonce values and confirm nonce 
        $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
        $nonceaction = 'bulk-' . $this->_args['plural'];
        if ( ! wp_verify_nonce( $_GET['_wpnonce'], $nonceaction ) ) {
            wp_die( 'Sorry a WordPress security check has denied your request!' );
        }
        
        // run the final method for handling the request
        $action = $this->current_action();
        $this->REQUESTS->process_admin_request( 'GET', $action );

        // we need a refresh to make the latest notice/s show and clear values from URL
        wp_redirect( $_GET['_wp_http_referer'] );
        exit;        
    }
    
    /** ************************************************************************
     * Prepare data for display. Sort and filter the data, and generally
     * get it ready to be displayed. 
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items_further( $data, $per_page = 20 ) {
        // get column settings
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
                
        // in this example I'm going to remove records from the array that do not have the searched string (test string is: 2)
        if( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) {
            $searched_string = wp_unslash( $_GET['s'] );
            foreach( $data as $key => $record_values  ) {
                $match_found = false;
                foreach( $record_values as $example_value ) {
                   if ( strpos( $example_value, $searched_string ) !== FALSE) { // Yoshi version
                        $match_found = true;
                        break;
                   }                
                }    
                
                // if no $match_found remove the current $record_values using the $key
                if( !$match_found ) {
                    unset( $data[ $key ] );    
                }
            }
        }
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable);
      
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count( $data );
                                 
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice( $data,( ( $current_page-1 ) * $per_page), $per_page);
 
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
                     
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}
?>