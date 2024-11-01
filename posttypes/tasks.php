<?php
/** 
* Tasks Post Type 
* 
* I've considered storing tasks in a stand alone table and could do that if there is a demand so let me know. The 
* advantage to have the tasks as post type is for anyone wishing to turn a blog into a project management environment
* with blog categories and full integration with other plugins.
* 
* @package WTG Tasks Manager
* @author Ryan Bayne   
* @since 0.0.1
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );   
                   
add_action( 'init', 'register_customposttype_wtgtasks' );   
add_action( 'save_post', 'save_meta_boxes_wtgtasks', 10, 2 );
add_action( 'add_meta_boxes', 'add_meta_boxes_wtgtasks' );   
             
/**
* REPLACEABOUT
* 
* @author Ryan R. Bayne
* @package WTG Tasks Manager
* @since 0.0.1
* @version 1.0
*/
function register_customposttype_wtgtasks() {
    $labels = array(
        'name' => _x( 'Task Posts', 'Post type for WebTechGlobal Tasks plugin' ),
        'singular_name' => _x( 'Task Post', 'Task post type for WebTechGlobal Tasks plugin' ),
        'add_new' => _x( 'New Task', 'wtgtasks' ),
        'add_new_item' => __( 'Create Task' ),
        'edit_item' => __( 'Edit Task' ),
        'new_item' => __( 'Create Task' ),
        'all_items' => __( 'All Tasks' ),
        'view_item' => __( 'View Task' ),
        'search_items' => __( 'Search Tasks' ),
        'not_found' =>  __( 'No tasks found' ),
        'not_found_in_trash' => __( 'No tasks found in Trash' ), 
        'parent_item_colon' => '',
        'menu_name' => __( 'Task Posts', 'wtgtasksmanager' )
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true, 
        'show_in_menu'       => true, 
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true, 
        'hierarchical'       => false,
        'menu_position'      => 100,
        'supports'           => array( 'title', 'editor', 'custom-fields' ),
        'taxonomies'         =>  array('post_tag')
    );   

    register_post_type( 'wtgtasks', $args );    
} 

####################################################################################
#                                                                                  #
#                                 ADD META BOXES                                   #
#                                                                                  #
####################################################################################
function add_meta_boxes_wtgtasks() {
  
    // main options
    add_meta_box(
        'wtgtasks-meta-mainoptions',//  id            
        esc_html__( 'Main Options' ),// meta box title       
        'metabox_wtgtasks_mainoptions',// callback function name        
        'wtgtasks',                  
        'normal',                 
        'default'                  
    ); 
}

// project meta box
function metabox_wtgtasks_mainoptions( $object, $box ) {
    wp_nonce_field( basename( __FILE__ ), 'wtgtasks_mainoptionsnonce' );
     
    $WTGTASKSMANAGER = new WTGTASKSMANAGER;
    $FORMS = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Formbuilder', 'class-forms.php', 'classes' );
        
    // get all active (none archived) projects
    $result = $WTGTASKSMANAGER->get_projects();
    $projects_array = array( 'noneselected' => __( 'Please Select Project', 'wtgtaskmanager' ) );
    if( $result ) {
        foreach( $result as $key => $pro ) {
            $projects_array[ $pro['project_id'] ] = $pro['projectname'];    
        }
    }
    
    // register form, set values used for security later, also allows easy validation to be setup
    $FORMS->form_start( 'wtgtasksmainoptions', 'wtgtasksmainoptions', 'Create Task Main Options', false, false );
     
    echo '<table class="form-table">';
                
    $FORMS->input(  $box['args']['formid'], 'menu', 'projectid', 'projectid', __( 'Project', 'wtgtasksmanager' ), '', true, '', array( 'itemsarray' => $projects_array ), array( 'numeric' ) );            
                
    $FORMS->input(  $box['args']['formid'], 'menu', 'priority', 'priority', __( 'Priority', 'wtgtasksmanager' ), '', true, '', array( 'itemsarray' => array( 1 => __( 'Urgent' ), 2 => __( 'High' ), 3 => __( 'Important' ), 4 => __( 'Low' ), 5 => __( 'Optional' ) ) ) );    
    
    $FORMS->input(  $box['args']['formid'], 'text', 'requiredtasks', 'requiredtasks', __( 'Required Tasks', 'wtgtasksmanager' ), '', false, '', array(), array( 'numericlist' ) );                    
    
    $FORMS->input(  $box['args']['formid'], 'text', 'freelanceroffer', 'freelanceroffer', __( 'Freelancer Offer', 'wtgtasksmanager' ), '', false, '', array(), array( 'numeric' ) );                                    
    
    $FORMS->input(  $box['args']['formid'], 'menu_capabilities', 'requiredcapability', 'requiredcapability', __( 'Required Capability', 'wtgtasksmanager' ), '', false, '', array(), array() );                                    
    
    echo '</table>';
}

/**
* Save flag meta box's
* 
* @param mixed $post_id
* @param mixed $post
*/
function save_meta_boxes_wtgtasks( $post_id, $post ) {   

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['wtgtasks_mainoptionsnonce'] ) || !wp_verify_nonce( $_POST['wtgtasks_mainoptionsnonce'], basename( __FILE__ ) ) )    
        return $post_id;
              
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
                                          
    // check permissions
    if ( (key_exists( 'post_type', $post ) ) && ( 'page' == $post->post_type ) ) {
        if (!current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
    } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }        
         
    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );
     
    // run WebTechGlobal form security and process the request if security does not fail
    $WTGTASKSMANAGER_REQ = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Requests', 'class-requests.php', 'classes' );
    $WTGTASKSMANAGER_REQ->process_admin_request( 'post', 'wtgtasksmainoptions' );    
    
    return $post_id;
} 

####################################################################################
#                                                                                  #
#                               CUSTOM TABLE COLUMNS                               #
#                                                                                  #
####################################################################################

add_filter( 'manage_edit-wtgtasks_columns', 'set_custom_edit_book_columns' );
add_action( 'manage_wtgtasks_posts_custom_column' , 'custom_book_column', 10, 2 );

function set_custom_edit_book_columns($columns) {
    //unset( $columns['author'] );
    $columns['project_name'] = __( 'Project Name', 'wtgtasksmanager' );
    $columns['task_priority'] = __( 'Priority', 'wtgtasksmanager' );
    return $columns;
}

function custom_book_column( $column, $post_id ) {
    switch ( $column ) {
        case 'project_name' :
            global $wpdb;
            
            $project_id = get_post_meta( $post_id , 'wtgprojectid' , true ); 
            
            if( !empty( $project_id ) && $project_id !== '' ) {    
                echo $wpdb->get_var( "SELECT projectname FROM " . $wpdb->webtechglobal_projects . " WHERE project_id = '$project_id'" );    
                return;
            }
            
            _e( 'Not Found', 'wtgtasksmanager' );
                            
            break;
        case 'task_priority' :
            global $wpdb;
            
            $priority = get_post_meta( $post_id , 'wtgpriority' , true ); 
            
                if( is_numeric( $priority ) ) {    
                    switch( $priority ) {
                        case 1:
                            _e( 'Urgent', 'wtgtasksmanager' );
                            return;
                            break;
                        case 2:
                            _e( 'High', 'wtgtasksmanager' );
                            return;
                            break;
                        case 3:
                            _e( 'Important', 'wtgtasksmanager' );
                            return;
                            break;
                        case 4:
                            _e( 'Low', 'wtgtasksmanager' );
                            return;
                            break;
                        case 5:
                            _e( 'Optional', 'wtgtasksmanager' );
                            return;
                            break;
                    }    
                }
            
            _e( 'Not Found', 'wtgtasksmanager' );
                            
            break;

    }
} 

####################################################################################
#                                                                                  #
#                                   TAXONOMIES                                     #
#                                                                                  #
####################################################################################

/**
* Task skills allows skills to be found by freelancers based on their skill-set.
* 
* Ability to use specific software is also a skill and so adding the name of software
* or techniques is acceptable. I didn't want to add too many taxonomies that are similar.
* 
* Experience/certification/qualifications could also be added if very specific demands
* wanted for a task. Keep in mind that a task can be highly detailed, like a full project posting.
* 
* @author Ryan R. Bayne
* @package WTG Tasks Manager
* @since 0.0.1
* @version 1.0
*/
function skills_taxonomy_for_tasksmanager() {
    $labels = array(
        'name'                       => _x( 'Task Skills', 'taxonomy general name' ),
        'singular_name'              => _x( 'Skill', 'taxonomy singular name' ),
        'search_items'               => __( 'Search Skills' ),
        'popular_items'              => __( 'Popular Skills' ),
        'all_items'                  => __( 'All Skills' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Skill' ),
        'update_item'                => __( 'Update Skill' ),
        'add_new_item'               => __( 'Add New Skill' ),
        'new_item_name'              => __( 'New Skill' ),
        'separate_items_with_commas' => __( "Enter skills separated by commas." ),
        'add_or_remove_items'        => __( 'Add or remove skills' ),
        'choose_from_most_used'      => __( 'Choose from the most used skills' ),
        'not_found'                  => __( 'No skills found.' ),
        'menu_name'                  => __( 'Skills' ),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'tasksmanagerskills' ),
    );

    register_taxonomy( 'wtgtaskmanagerskills', 'wtgtasks', $args );
}

add_action( 'init', 'skills_taxonomy_for_tasksmanager', 0 );


function custom_post_status_wtgtasks_new(){
     register_post_status( 'newtask', array(
          'label'                     => _x( 'New', 'wtgtasksmanager' ),
          'public'                    => false,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'New <span class="count">(%s)</span>', 'New <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status_wtgtasks_new' );

function custom_post_status_wtgtasks_started(){
     register_post_status( 'startedtask', array(
          'label'                     => _x( 'Started', 'post' ),
          'public'                    => false,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Started <span class="count">(%s)</span>', 'Started <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status_wtgtasks_started' );

function custom_post_status_wtgtasks_finished(){
     register_post_status( 'finishedtask', array(
          'label'                     => _x( 'Finished', 'post' ),
          'public'                    => false,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Finished <span class="count">(%s)</span>', 'Finished <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status_wtgtasks_finished' );


function custom_post_status_wtgtasks_closed(){
     register_post_status( 'closedtask', array(
          'label'                     => _x( 'Closed', 'post' ),
          'public'                    => false,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Closed <span class="count">(%s)</span>', 'Closed <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status_wtgtasks_closed' );


function custom_post_status_wtgtasks_cancelled(){
     register_post_status( 'cancelledtask', array(
          'label'                     => _x( 'Cancelled', 'post' ),
          'public'                    => false,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status_wtgtasks_cancelled' );


function projecttaskmanager_append_post_status_list(){
    global $post;
    if( $post->post_type == 'wtgtasks' ){
        
        // New (newtask)
        $complete = '';
        $label = '';   
        
        if( $post->post_status == 'newtask' ){
            $complete = ' selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> New</span>';
        }

        echo '<script>
        jQuery(document).ready(function($){
        $("select#post_status").append("<option value=\"newtask\" '.$complete.'>New</option>");
        $(".misc-pub-section label").append("'.$label.'");
        });
        </script>';

        // Started (startedtask)
        $complete = '';
        $label = '';    
                       
        if( $post->post_status == 'startedtask' ){
            $complete = ' selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Started</span>';
        }
        
        echo '<script>
        jQuery(document).ready(function($){
        $("select#post_status").append("<option value=\"startedtask\" '.$complete.'>Started</option>");
        $(".misc-pub-section label").append("'.$label.'");
        });
        </script>';        
        
        // Finished (finishedtask)
        $complete = '';
        $label = '';  
        
        if( $post->post_status == 'finishedtask' ){
            $complete = ' selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Finished</span>';
        }
        
        echo '<script>
        jQuery(document).ready(function($){
        $("select#post_status").append("<option value=\"finishedtask\" '.$complete.'>Finished</option>");
        $(".misc-pub-section label").append("'.$label.'");
        });
        </script>';
        
        // Closed (closedtask)
        $complete = '';
        $label = '';
                
        if( $post->post_status == 'closedtask' ){
            $complete = ' selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Closed</span>';
        }
        
        echo '<script>
        jQuery(document).ready(function($){
        $("select#post_status").append("<option value=\"closedtask\" '.$complete.'>Closed</option>");
        $(".misc-pub-section label").append("'.$label.'");
        });
        </script>';
        
        // Cancelled (cancelledtask)
        $complete = '';
        $label = '';
                
        if( $post->post_status == 'cancelledtask' ){
            $complete = ' selected=\"selected\"';
            $label = '<span id=\"post-status-display\"> Cancelled</span>';
        }

        echo '<script>
        jQuery(document).ready(function($){
        $("select#post_status").append("<option value=\"cancelledtask\" '.$complete.'>Cancelled</option>");
        $(".misc-pub-section label").append("'.$label.'");
        });
        </script>';
 
        $complete = '';
    }
}add_action('admin_footer-post.php', 'projecttaskmanager_append_post_status_list');

####################################################################################
#                                                                                  #
#                                     FILTERS                                      #
#                                                                                  #
####################################################################################
?>