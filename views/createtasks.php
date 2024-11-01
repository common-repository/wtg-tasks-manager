<?php
/**
 * Create Tasks [page]   
 *
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Create Tasks [page] 
 * 
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne
 * @since 0.0.1
 */
class WTGTASKSMANAGER_Createtasks_View extends WTGTASKSMANAGER_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 0.0.1
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'createtasks';
    
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
            array( $this->view_name . '-advanced', __( 'Create Task', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'advanced' ), true, 'activate_plugins' ),
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
        $this->FORMS = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Formbuilder', 'class-forms.php', 'classes' );
        
        parent::setup( $action, $data );
        
        // using array register many meta boxes
        foreach( self::meta_box_array() as $key => $metabox ) {
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        }
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
    * Create a standard task with all functionality in use. 
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createtasks_advanced( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Create a task with detailed requirements.', 'wtgtasksmanager' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title']);
        ?>  
            <div id="poststuff">
            
                <table class="form-table">
                <?php 
                // get all active (none archived) projects
                $result = $this->WTGTASKSMANAGER->get_projects();
                $projects_array = array( 'noneselected' => __( 'Please Select Project', 'wtgtaskmanager' ) );
                if( $result ) {
                    foreach( $result as $key => $pro ) {
                        $projects_array[ $pro['project_id'] ] = $pro['projectname'];    
                    }
                }               
                
                // task name
                $this->FORMS->text_basic( $box['args']['formid'], 'taskname', 'taskname', __( 'Task Name', 'wtgtasksmanager' ), '', true, array(), array( 'alphanumeric' ) );
                
                $this->FORMS->input(  $box['args']['formid'], 'menu', 'projectid', 'projectid', __( 'Project', 'wtgtasksmanager' ), '', true, '', array( 'itemsarray' => $projects_array ), array( 'numeric' ) );    
                
                $this->FORMS->input(  $box['args']['formid'], 'menu', 'priority', 'priority', __( 'Priority', 'wtgtasksmanager' ), '', true, '', array( 'itemsarray' => array( 1 => __( 'Urgent' ), 2 => __( 'High' ), 3 => __( 'Important' ), 4 => __( 'Low' ), 5 => __( 'Optional' ) ) ) );    
                
                $this->FORMS->input(  $box['args']['formid'], 'text', 'requiredtasks', 'requiredtasks', __( 'Required Tasks', 'wtgtasksmanager' ), '', false, '', array(), array( 'numericlist' ) );                    
                
                $this->FORMS->input(  $box['args']['formid'], 'text', 'freelanceroffer', 'freelanceroffer', __( 'Freelancer Offer', 'wtgtasksmanager' ), '', false, '', array(), array( 'numeric' ) );                                    
                
                $this->FORMS->input(  $box['args']['formid'], 'menu_capabilities', 'requiredcapability', 'requiredcapability', __( 'Required Capability', 'wtgtasksmanager' ), '', false, '', array(), array() );                                    
                ?>
                </table>
                
                <?php 
                // editor for large and detailed description
                $wysiwygdefaultcontent = ''; 
                wp_editor( $wysiwygdefaultcontent, 'taskdescriptioneditor', array( 'textarea_name' => 'taskdescription' ) );
                ?>
            
            </div>
            
            <br>
            
        <?php 
        $this->UI->postbox_content_footer();
    } 

}?>