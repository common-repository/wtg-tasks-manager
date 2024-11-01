<?php
/**
 * Main [section] - Projects [page]
 * 
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class for Main [section] - Projects [page]
 * 
 * @package WTG Tasks Manager
 * @subpackage Views
 * @author Ryan Bayne
 * @since 0.0.1
 */
class WTGTASKSMANAGER_Main_View extends WTGTASKSMANAGER_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 0.0.1
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'main';
    
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
        global $tasksmanager_settings;

        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        $this->meta_boxes_array = array(
            
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( 'main-welcome', __( 'About WTG Tasks Manager', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'welcome' ), true, 'activate_plugins' ),
            array( 'main-mailchimp', __( 'Subscribe for Updates', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'mailchimp' ), true, 'activate_plugins' ),
             
            array( 'main-createproject', __( 'Start New Project', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'startnewproject' ), true, 'activate_plugins' ),
            array( 'main-projectlist', __( 'Project List', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'projectlist' ), true, 'activate_plugins' ),

            array( 'main-globalswitches', __( 'Global Switches', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'globalswitches' ), true, 'activate_plugins' ),
            array( 'main-logsettings', __( 'Log Settings', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'logsettings' ), true, 'activate_plugins' ),
            array( 'main-pagecapabilitysettings', __( 'Page Capability Settings', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'pagecapabilitysettings' ), true, 'activate_plugins' ),
            
            // side boxes
            array( 'main-support', __( 'Support', 'wtgtasksmanager' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'support' ), true, 'activate_plugins' ),            
            array( 'main-twitterupdates', __( 'Twitter Updates', 'wtgtasksmanager' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'twitterupdates' ), true, 'activate_plugins' ),
            array( 'main-facebook', __( 'Facebook', 'wtgtasksmanager' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'facebook' ), true, 'activate_plugins' ),
        );
        
        // add meta boxes that have conditions i.e. a global switch
        if( isset( $tasksmanager_settings['widgetsettings']['dashboardwidgetsswitch'] ) && $tasksmanager_settings['widgetsettings']['dashboardwidgetsswitch'] == 'enabled' ) {
            $this->meta_boxes_array[] = array( 'main-dashboardwidgetsettings', __( 'Dashboard Widget Settings', 'wtgtasksmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'dashboardwidgetsettings' ), true, 'activate_plugins' );   
        }
        
        return $this->meta_boxes_array;                
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
        $this->Log = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Log', 'class-log.php', 'classes' );
        $this->Forms = WTGTASKSMANAGER::load_class( 'WTGTASKSMANAGER_Formbuilder', 'class-forms.php', 'classes' );
        
        parent::setup( $action, $data );
        
        // only output meta boxes
        if( $this->purpose == 'normal' ) {
            self::metaboxes();// register meta boxes for the current view
        } elseif( $this->purpose == 'dashboard' ) {
            // do nothing - add_dashboard_widgets() in class-ui.php calls dashboard_widgets() from this class
        } elseif( $this->purpose == 'customdashboard' ) {
            return self::meta_box_array();// return meta box array
        } else {
            // do nothing 
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
    * @package WTGTASKSMANAGER
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
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_welcome( $data, $box ) {    
        echo '<p>' . __( "This WebTechGlobal tasks plugin focuses on being a to-do system. The plan is to create a Project Management plugin
        which will integrate with this plugin. In short this plugin is about workflow. The project management plugin will offer professional time
        and resource management tools. Combine the both to add more accurate management.", 'wtgtasksmanager' ) . '</p>';
    }      
            
    /**
    * Form for creating new projects.
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_startnewproject( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'These switches disable or enable systems. Disabling systems you do not require will improve the plugins performance.', 'wtgtasksmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $tasksmanager_settings;
        ?>  

            <table class="form-table">
            <?php        
            $this->Forms->text_advanced( $box['args']['formid'], 'newprojectname', 'newprojectname', __( 'Project Name', 'wtgtasksmanager' ), '', false, true, true, false, false, array( 'alphanumeric' ) );      
            ?>
            </table> 
            
        <?php 
        $this->UI->postbox_content_footer();
    }   
             
    /**
    * List of projects with delete ability.
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    * 
    * @todo it is too easy to initiate project deletion
    */
    public function postbox_main_projectlist( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'A list of projects with quick actions.', 'wtgtasksmanager' ), false );        

        global $tasksmanager_settings;
        ?>  

            <table class="form-table">
            <?php 
            $projects = $this->WTGTASKSMANAGER->get_projects();
            foreach( $projects as $key => $project ) {
                
                /*
                 'project_id' => string '1' (length=1)
                  'timestamp' => string '2014-11-08 15:03:12' (length=19)
                  'description' => null
                  'mainmanager' => null
                  'phase' => null
                  'projectname' => string 'fffff' (length=5)
                  'archived' => string '0' (length=1)
                  */
      
                $link = $this->UI->linkaction( 'wtgtasksmanager', 'projectfocus', __( 'WTG Tasks Manager admin link', 'wtgtasksmanager' ), __( 'Focus', 'wtgtasksmanager' ), '&projectid=' . $project['project_id'], ' class="button wtgtasksmanagerbutton"', 'admin.php' );
                $this->UI->option_subline( $link, $project['projectname'] );
                //$this->UI->
            }       
            ?>
            </table> 
            
        <?php 
    } 
               
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_globalswitches( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'These switches disable or enable systems. Disabling systems you do not require will improve the plugins performance.', 'wtgtasksmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $tasksmanager_settings;
        ?>  

            <table class="form-table">
            <?php        
            $this->UI->option_switch( __( 'WordPress Notice Styles', 'wtgtasksmanager' ), 'uinoticestyle', 'uinoticestyle', $tasksmanager_settings['noticesettings']['wpcorestyle'] );
            
            // WTG Flag System Switch
            $flagsystem_status = false;
            if( isset( $tasksmanager_settings['posttypes']['wtgflags']['status'] ) ){
                $flagsystem_status = $tasksmanager_settings['posttypes']['wtgflags']['status'];
            }                                                                       
            
            $this->UI->option_switch( __( 'WTG Flag System', 'wtgtasksmanager' ), 'flagsystemstatus', 'flagsystemstatus', $flagsystem_status );
            
            $this->UI->option_switch( __( 'Dashboard Widgets Switch', 'wtgtasksmanager' ), 'dashboardwidgetsswitch', 'dashboardwidgetsswitch', $tasksmanager_settings['widgetsettings']['dashboardwidgetsswitch'], 'Enabled', 'Disabled', 'disabled' );      
            ?>
            </table> 
            
        <?php 
        $this->UI->postbox_content_footer();
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_logsettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'The plugin has its own log system with multi-purpose use. Not everything is logged for the sake of performance so please request increased log use if required.', 'wtgtasksmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title']);
        
        global $tasksmanager_settings;
        ?>  

            <table class="form-table">
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row">Log</th>
                    <td>
                        <?php 
                        // if is not set ['admintriggers']['newcsvfiles']['status'] then it is enabled by default
                        if(!isset( $tasksmanager_settings['globalsettings']['uselog'] ) ){
                            $radio1_uselog_enabled = 'checked'; 
                            $radio2_uselog_disabled = '';                    
                        }else{
                            if( $tasksmanager_settings['globalsettings']['uselog'] == 1){
                                $radio1_uselog_enabled = 'checked'; 
                                $radio2_uselog_disabled = '';    
                            }elseif( $tasksmanager_settings['globalsettings']['uselog'] == 0){
                                $radio1_uselog_enabled = ''; 
                                $radio2_uselog_disabled = 'checked';    
                            }
                        }?>
                        <fieldset><legend class="screen-reader-text"><span>Log</span></legend>
                            <input type="radio" id="logstatus_enabled" name="wtgtasksmanager_radiogroup_logstatus" value="1" <?php echo $radio1_uselog_enabled;?> />
                            <label for="logstatus_enabled"> <?php _e( 'Enable', 'wtgtasksmanager' ); ?></label>
                            <br />
                            <input type="radio" id="logstatus_disabled" name="wtgtasksmanager_radiogroup_logstatus" value="0" <?php echo $radio2_uselog_disabled;?> />
                            <label for="logstatus_disabled"> <?php _e( 'Disable', 'wtgtasksmanager' ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->
      
                <?php       
                // log rows limit
                if(!isset( $tasksmanager_settings['globalsettings']['loglimit'] ) || !is_numeric( $tasksmanager_settings['globalsettings']['loglimit'] ) ){$tasksmanager_settings['globalsettings']['loglimit'] = 1000;}
                $this->UI->option_text( 'Log Entries Limit', 'wtgtasksmanager_loglimit', 'loglimit', $tasksmanager_settings['globalsettings']['loglimit'] );
                ?>
            </table> 
            
                    
            <h4>Outcomes</h4>
            <label for="wtgtasksmanager_log_outcomes_success"><input type="checkbox" name="wtgtasksmanager_log_outcome[]" id="wtgtasksmanager_log_outcomes_success" value="1" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['outcomecriteria']['1'] ) ){echo 'checked';} ?>> Success</label>
            <br> 
            <label for="wtgtasksmanager_log_outcomes_fail"><input type="checkbox" name="wtgtasksmanager_log_outcome[]" id="wtgtasksmanager_log_outcomes_fail" value="0" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['outcomecriteria']['0'] ) ){echo 'checked';} ?>> Fail/Rejected</label>

            <h4>Type</h4>
            <label for="wtgtasksmanager_log_type_general"><input type="checkbox" name="wtgtasksmanager_log_type[]" id="wtgtasksmanager_log_type_general" value="general" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['typecriteria']['general'] ) ){echo 'checked';} ?>> General</label>
            <br>
            <label for="wtgtasksmanager_log_type_error"><input type="checkbox" name="wtgtasksmanager_log_type[]" id="wtgtasksmanager_log_type_error" value="error" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['typecriteria']['error'] ) ){echo 'checked';} ?>> Errors</label>
            <br>
            <label for="wtgtasksmanager_log_type_trace"><input type="checkbox" name="wtgtasksmanager_log_type[]" id="wtgtasksmanager_log_type_trace" value="flag" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['typecriteria']['flag'] ) ){echo 'checked';} ?>> Trace</label>

            <h4>Priority</h4>
            <label for="wtgtasksmanager_log_priority_low"><input type="checkbox" name="wtgtasksmanager_log_priority[]" id="wtgtasksmanager_log_priority_low" value="low" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['prioritycriteria']['low'] ) ){echo 'checked';} ?>> Low</label>
            <br>
            <label for="wtgtasksmanager_log_priority_normal"><input type="checkbox" name="wtgtasksmanager_log_priority[]" id="wtgtasksmanager_log_priority_normal" value="normal" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['prioritycriteria']['normal'] ) ){echo 'checked';} ?>> Normal</label>
            <br>
            <label for="wtgtasksmanager_log_priority_high"><input type="checkbox" name="wtgtasksmanager_log_priority[]" id="wtgtasksmanager_log_priority_high" value="high" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['prioritycriteria']['high'] ) ){echo 'checked';} ?>> High</label>
            
            <h1>Custom Search</h1>
            <p>This search criteria is not currently stored, it will be used on the submission of this form only.</p>
         
            <h4>Page</h4>
            <select name="wtgtasksmanager_pluginpages_logsearch" id="wtgtasksmanager_pluginpages_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php
                $current = '';
                if( isset( $tasksmanager_settings['logsettings']['logscreen']['page'] ) && $tasksmanager_settings['logsettings']['logscreen']['page'] != 'notselected' ){
                    $current = $tasksmanager_settings['logsettings']['logscreen']['page'];
                } 
                $this->UI->page_menuoptions( $current);?> 
            </select>
            
            <h4>Action</h4> 
            <select name="csv2pos_logactions_logsearch" id="csv2pos_logactions_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $tasksmanager_settings['logsettings']['logscreen']['action'] ) && $tasksmanager_settings['logsettings']['logscreen']['action'] != 'notselected' ){
                    $current = $tasksmanager_settings['logsettings']['logscreen']['action'];
                }
                $action_results = $this->Log->getactions( $current);
                if( $action_results){
                    foreach( $action_results as $key => $action){
                        $selected = '';
                        if( $action['action'] == $current){
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="'.$action['action'].'" '.$selected.'>'.$action['action'].'</option>'; 
                    }   
                }?> 
            </select>
            
            <h4>Screen Name</h4>
            <select name="wtgtasksmanager_pluginscreens_logsearch" id="wtgtasksmanager_pluginscreens_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $tasksmanager_settings['logsettings']['logscreen']['screen'] ) && $tasksmanager_settings['logsettings']['logscreen']['screen'] != 'notselected' ){
                    $current = $tasksmanager_settings['logsettings']['logscreen']['screen'];
                }
                $this->UI->screens_menuoptions( $current);?> 
            </select>
                  
            <h4>PHP Line</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_phpline" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['line'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['line'];} ?>">
            
            <h4>PHP File</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_phpfile" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['file'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['file'];} ?>">
            
            <h4>PHP Function</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_phpfunction" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['function'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['function'];} ?>">
            
            <h4>Panel Name</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_panelname" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['panelname'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['panelname'];} ?>">

            <h4>IP Address</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_ipaddress" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['ipaddress'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['ipaddress'];} ?>">
           
            <h4>User ID</h4>
            <input type="text" name="wtgtasksmanager_logcriteria_userid" value="<?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['userid'] ) ){echo $tasksmanager_settings['logsettings']['logscreen']['userid'];} ?>">    
          
            <h4>Display Fields</h4>                                                                                                                                        
            <label for="wtgtasksmanager_logfields_outcome"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_outcome" value="outcome" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Outcome', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_line"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_line" value="line" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['line'] ) ){echo 'checked';} ?>> <?php _e( 'Line', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_file"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_file" value="file" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['file'] ) ){echo 'checked';} ?>> <?php _e( 'File', 'wtgtasksmanager' );?></label> 
            <br>
            <label for="wtgtasksmanager_logfields_function"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_function" value="function" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['function'] ) ){echo 'checked';} ?>> <?php _e( 'Function', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_sqlresult"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_sqlresult" value="sqlresult" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['sqlresult'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Result', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_sqlquery"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_sqlquery" value="sqlquery" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['sqlquery'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Query', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_sqlerror"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_sqlerror" value="sqlerror" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['sqlerror'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Error', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_wordpresserror"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_wordpresserror" value="wordpresserror" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['wordpresserror'] ) ){echo 'checked';} ?>> <?php _e( 'WordPress Erro', 'wtgtasksmanager' );?>r</label>
            <br>
            <label for="wtgtasksmanager_logfields_screenshoturl"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_screenshoturl" value="screenshoturl" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['screenshoturl'] ) ){echo 'checked';} ?>> <?php _e( 'Screenshot URL', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_userscomment"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_userscomment" value="userscomment" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['userscomment'] ) ){echo 'checked';} ?>> <?php _e( 'Users Comment', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_page"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_page" value="page" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['page'] ) ){echo 'checked';} ?>> <?php _e( 'Page', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_version"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_version" value="version" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['version'] ) ){echo 'checked';} ?>> <?php _e( 'Plugin Version', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_panelname"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_panelname" value="panelname" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] ) ){echo 'checked';} ?>> <?php _e( 'Panel Name', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_tabscreenname"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_tabscreenname" value="tabscreenname" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Screen Name *', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_dump"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_dump" value="dump" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['dump'] ) ){echo 'checked';} ?>> <?php _e( 'Dump', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_ipaddress"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_ipaddress" value="ipaddress" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['ipaddress'] ) ){echo 'checked';} ?>> <?php _e( 'IP Address', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_userid"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_userid" value="userid" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['userid'] ) ){echo 'checked';} ?>> <?php _e( 'User ID', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_comment"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_comment" value="comment" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['comment'] ) ){echo 'checked';} ?>> <?php _e( 'Developers Comment', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_type"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_type" value="type" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['type'] ) ){echo 'checked';} ?>> <?php _e( 'Entry Type', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_category"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_category" value="category" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['category'] ) ){echo 'checked';} ?>> <?php _e( 'Category', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_action"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_action" value="action" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['action'] ) ){echo 'checked';} ?>> <?php _e( 'Action', 'wtgtasksmanager' );?></label>
            <br>
            <label for="wtgtasksmanager_logfields_priority"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_priority" value="priority" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['priority'] ) ){echo 'checked';} ?>> <?php _e( 'Priority', 'wtgtasksmanager' );?></label> 
            <br>
            <label for="wtgtasksmanager_logfields_thetrigger"><input type="checkbox" name="wtgtasksmanager_logfields[]" id="wtgtasksmanager_logfields_thetrigger" value="thetrigger" <?php if( isset( $tasksmanager_settings['logsettings']['logscreen']['displayedcolumns']['thetrigger'] ) ){echo 'checked';} ?>> <?php _e( 'Trigger', 'wtgtasksmanager' );?></label> 

    
        <?php 
        $this->UI->postbox_content_footer();
    }    
        
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_iconsexplained( $data, $box ) {    
        ?>  
        <p class="about-description"><?php _e( 'The plugin has icons on the UI offering different types of help...' ); ?></p>
        
        <h3>Help Icon<?php echo $this->UI->helpicon( 'http://www.webtechglobal.co.uk/wtgtasksmanager' )?></h3>
        <p><?php _e( 'The help icon offers a tutorial or indepth description on the WebTechGlobal website. Clicking these may open
        take a key page in the plugins portal or post in the plugins blog. On a rare occasion you will be taking to another users 
        website who has published a great tutorial or technical documentation.' )?></p>        
        
        <h3>Discussion Icon<?php echo $this->UI->discussicon( 'http://www.webtechglobal.co.uk/wtgtasksmanager' )?></h3>
        <p><?php _e( 'The discussion icon open an active forum discussion or chat on the WebTechGlobal domain in a new tab. If you see this icon
        it means you are looking at a feature or area of the plugin that is a hot topic. It could also indicate the
        plugin author would like to hear from you regarding a specific feature. Occasionally these icons may take you to a discussion
        on other websites such as a Google circles, an official page on Facebook or a good forum thread on a users domain.' )?></p>
                          
        <h3>Info Icon<img src="<?php echo WTGTASKSMANAGER_IMAGES_URL;?>info-icon.png" alt="<?php _e( 'Icon with an i click it to read more information in a popup.' );?>"></h3>
        <p><?php _e( 'The information icon will not open another page. It will display a pop-up with extra information. This is mostly used within
        panels to explain forms and the status of the panel.' )?></p>        
        
        <h3>Video Icon<?php echo $this->UI->videoicon( 'http://www.webtechglobal.co.uk/wtgtasksmanager' )?></h3>
        <p><?php _e( 'clicking on the video icon will open a new tab to a YouTube video. Occasionally it may open a video on another
        website. Occasionally a video may even belong to a user who has created a good tutorial.' )?></p> 
               
        <h3>Trash Icon<?php echo $this->UI->trashicon( 'http://www.webtechglobal.co.uk/wtgtasksmanager' )?></h3>
        <p><?php _e( 'The trash icon will be shown beside items that can be deleted or objects that can be hidden.
        Sometimes you can hide a panel as part of the plugins configuration. Eventually I hope to be able to hide
        notices, especially the larger ones..' )?></p>      
      <?php     
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_twitterupdates( $data, $box ) {    
        ?>
        <p class="about-description"><?php _e( 'Thank this plugins developers with a Tweet...', 'wtgtasksmanager' ); ?></p>    
        <a class="twitter-timeline" href="https://twitter.com/WebTechGlobal" data-widget-id="511630591142268928">Tweets by @WebTechGlobal</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id) ){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>                                                   
        <?php     
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0.4
    */
    public function postbox_main_support( $data, $box ) {    
        ?>      
        <p><?php _e( 'All users (free and pro editions) are supported. Please get to know the plugins <a href="http://www.webtechglobal.co.uk/wtgtasksmanager-support/" title="WTG Tasks Manager Support" target="_blank">support page</a> where you may seek free or paid support.', 'wtgtasksmanager' ); ?></p>                     
        <?php     
    }   
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_facebook( $data, $box ) {    
        ?>      
        <p class="about-description"><?php _e( 'Please show your appreciation for my plugin which has taking hundreds of hours to create...', 'wtgtasksmanager' ); ?></p>
        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FWebTechGlobal1&amp;width=350&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true" scrolling="no" frameborder="0" style="padding: 10px 0 0 0;border:none; overflow:hidden; width:100%; height:290px;" allowTransparency="true"></iframe>                                                                             
        <?php     
    }

    /**
    * Form for setting which captability is required to view the page
    * 
    * By default there is no settings data for this because most people will never use it.
    * However when it is used, a new option record is created so that the settings are
    * independent and can be accessed easier.  
    * 
    * @author Ryan R. Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_pagecapabilitysettings( $data, $box ) {
        global $WTGTASKSMANAGER_Menu;
        
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Set the capability a user requires to view any of the plugins pages. This works independently of role plugins such as Role Scoper.', 'wtgtasksmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>
        
        <table class="form-table">
        
        <?php 
        // get stored capability settings 
        $saved_capability_array = get_option( 'wtgtasksmanager_capabilities' );
        
        // add a menu for each page for the user selecting the required capability 
        foreach( $WTGTASKSMANAGER_Menu as $key => $page_array ) {
            
            // do not add the main page to the list as a strict security measure
            if( $page_array['name'] !== 'main' ) {
                $current = null;
                if( isset( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) && is_string( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) ) {
                    $current = $saved_capability_array['pagecaps'][ $page_array['name'] ];
                }
                
                $this->UI->option_menu_capabilities( $page_array['menu'], 'pagecap' . $page_array['name'], 'pagecap' . $page_array['name'], $current );
            }
        }?>
        
        </table>
        
        <?php 
        $this->UI->postbox_content_footer();        
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG Tasks Manager
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_main_dashboardwidgetsettings( $data, $box ) { 
        global $tasksmanager_settings, $WTGTASKSMANAGER_Menu;
           
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This panel is new and is advanced.   
        Please seek my advice before using it.
        You must be sure and confident that it operates in the way you expect.
        It will add widgets to your dashboard. 
        The capability menu allows you to set a global role/capability requirements for the group of wigets from any giving page. 
        The capability options in the "Page Capability Settings" panel are regarding access to the admin page specifically.', 'wtgtasksmanager' ), false );   
             
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title']);

        echo '<table class="form-table">';

        // now loop through views, building settings per box (display or not, permitted role/capability  
        foreach( $WTGTASKSMANAGER_Menu as $key => $section_array ) {

            /*
                'groupname' => string 'main' (length=4)
                'slug' => string 'wtgtasksmanager_generalsettings' (length=24)
                'menu' => string 'General Settings' (length=16)
                'pluginmenu' => string 'General Settings' (length=16)
                'name' => string 'generalsettings' (length=15)
                'title' => string 'General Settings' (length=16)
                'parent' => string 'main' (length=4)
            */
            
            // get dashboard activation status for the current page
            $current_for_page = '123nocurrentvalue';
            if( isset( $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] ) ) {
                $current_for_page = $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'];   
            }
            
            // display switch for current page
            $this->UI->option_switch( $section_array['menu'], $section_array['name'] . 'dashboardwidgetsswitch', $section_array['name'] . 'dashboardwidgetsswitch', $current_for_page, 'Enabled', 'Disabled', 'disabled' );
            
            // get current pages minimum dashboard widget capability
            $current_capability = '123nocapability';
            if( isset( $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] ) ) {
                $current_capability = $tasksmanager_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'];   
            }
                            
            // capabilities menu for each page (rather than individual boxes, the boxes will have capabilities applied in code)
            $this->UI->option_menu_capabilities( __( 'Capability Required', 'wtgtasksmanager' ), $section_array['name'] . 'widgetscapability', $section_array['name'] . 'widgetscapability', $current_capability );
        }

        echo '</table>';
                    
        $this->UI->postbox_content_footer();
    }    

    /**
    * Mailchimp subscribers list form.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @version 1.1
    */
    public function postbox_main_mailchimp( $data, $box ) {  
    ?>

        <!-- Begin MailChimp Signup Form -->
        <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
            /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
               We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
        </style>
        <div id="mc_embed_signup">
        <form action="//webtechglobal.us9.list-manage.com/subscribe/post?u=99272fe1772de14ff2be02fe6&amp;id=018f5572ec" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
            <div id="mc_embed_signup_scroll">
            <h2>Please Subscribe to WTG Mailing List</h2>
            <h3>Mail will not be frequent. This mailing list will be used to send you
            high priority updates only. There are individual mailing lists per product
            and service if you wish to get monthly updates.</h3>
        <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
        <div class="mc-field-group">
            <label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
        </label>
            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
        </div>
        <div class="mc-field-group">
            <label for="mce-FNAME">First Name </label>
            <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
        </div>
        <div class="mc-field-group">
            <label for="mce-LNAME">Last Name </label>
            <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
        </div>
        <div class="mc-field-group input-group">
            <strong>Email Format </strong>
            <ul><li><input type="radio" value="html" name="EMAILTYPE" id="mce-EMAILTYPE-0"><label for="mce-EMAILTYPE-0">html</label></li>
        <li><input type="radio" value="text" name="EMAILTYPE" id="mce-EMAILTYPE-1"><label for="mce-EMAILTYPE-1">text</label></li>
        </ul>
        </div>
        <p>Powered by <a href="http://eepurl.com/2W_2n" title="MailChimp - email marketing made easy and fun">MailChimp</a></p>
            <div id="mce-responses" class="clear">
                <div class="response" id="mce-error-response" style="display:none"></div>
                <div class="response" id="mce-success-response" style="display:none"></div>
            </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;"><input type="text" name="b_99272fe1772de14ff2be02fe6_018f5572ec" tabindex="-1" value=""></div>
            <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
            </div>
        </form>
        </div>

        <!--End mc_embed_signup-->

    <?php   

    }    
}?>