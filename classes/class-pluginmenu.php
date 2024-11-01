<?php
/**
* Beta testing only (check if in use yet) - phasing array files into classes of their own then calling into the main class
*/
class WTGTASKSMANAGER_TabMenu {
    public function menu_array() {
        $menu_array = array();
        
        ######################################################
        #                                                    #
        #                        MAIN                        #
        #                                                    #
        ######################################################
        // can only have one view in main right now until WP allows pages to be hidden from showing in
        // plugin menus. This may provide benefit of bringing user to the latest news and social activity
        // main page
        $menu_array['main']['groupname'] = 'main';        
        $menu_array['main']['slug'] = 'wtgtasksmanager';// home page slug set in main file
        $menu_array['main']['menu'] = __( 'Settings', 'wtgtasksmanager' );// plugin admin menu
        $menu_array['main']['pluginmenu'] = __( 'WTG Tasks Manager Settings' ,'wtgtasksmanager' );// for tabbed menu
        $menu_array['main']['name'] = "main";// name of page (slug) and unique
        $menu_array['main']['title'] = 'Settings';// title at the top of the admin page
        $menu_array['main']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu         
        $menu_array['main']['tabmenu'] = false;// boolean - true indicates multiple pages in section, false will hide tab menu and show one page 
                
        ######################################################
        #                                                    #
        #                OVERVIEW SECTION                    #
        #                                                    #
        ###################################################### 
       /*    
        // Log - using the plugins log table list key events
        $menu_array['log']['groupname'] = 'overview';
        $menu_array['log']['slug'] = 'wtgtasksmanager_log'; 
        $menu_array['log']['menu'] = __( 'Overview', 'wtgtasksmanager' );
        $menu_array['log']['pluginmenu'] = __( 'Log', 'wtgtasksmanager' );
        $menu_array['log']['name'] = "log";
        $menu_array['log']['title'] = __( 'Log', 'wtgtasksmanager' ); 
        $menu_array['log']['parent'] = 'parent'; 
        $menu_array['log']['tabmenu'] = true; 
       
        // Statistics - total tasks, tasks complete, total ideas, ideas awaiting approval
        $menu_array['statistics']['groupname'] = 'overview';
        $menu_array['statistics']['slug'] = 'wtgtasksmanager_statistics'; 
        $menu_array['statistics']['menu'] = __( 'Statistics', 'wtgtasksmanager' );
        $menu_array['statistics']['pluginmenu'] = __( 'Statistics', 'wtgtasksmanager' );
        $menu_array['statistics']['name'] = "statistics";
        $menu_array['statistics']['title'] = __( 'Statistics', 'wtgtasksmanager' ); 
        $menu_array['statistics']['parent'] = 'log'; 
        $menu_array['statistics']['tabmenu'] = true; 
       */
        ######################################################
        #                                                    #
        #                  TASKS SECTION                     #
        #                                                    #
        ###################################################### 
       
        // All Tasks
        $menu_array['alltasks']['groupname'] = 'tasks';
        $menu_array['alltasks']['slug'] = 'wtgtasksmanager_alltasks'; 
        $menu_array['alltasks']['menu'] = __( 'Extra Views', 'wtgtasksmanager' );
        $menu_array['alltasks']['pluginmenu'] = __( 'All', 'wtgtasksmanager' );
        $menu_array['alltasks']['name'] = "alltasks";
        $menu_array['alltasks']['title'] = __( 'All Tasks', 'wtgtasksmanager' ); 
        $menu_array['alltasks']['parent'] = 'parent'; 
        $menu_array['alltasks']['tabmenu'] = true; 
        
        // Started Tasks
        $menu_array['startedtasks']['groupname'] = 'tasks';
        $menu_array['startedtasks']['slug'] = 'wtgtasksmanager_startedtasks'; 
        $menu_array['startedtasks']['menu'] = __( 'Started', 'wtgtasksmanager' );
        $menu_array['startedtasks']['pluginmenu'] = __( 'Started', 'wtgtasksmanager' );
        $menu_array['startedtasks']['name'] = "startedtasks";
        $menu_array['startedtasks']['title'] = __( 'Started Tasks', 'wtgtasksmanager' ); 
        $menu_array['startedtasks']['parent'] = 'alltasks'; 
        $menu_array['startedtasks']['tabmenu'] = true; 
        
        // Finished Tasks
        $menu_array['finishedtasks']['groupname'] = 'tasks';
        $menu_array['finishedtasks']['slug'] = 'wtgtasksmanager_finishedtasks'; 
        $menu_array['finishedtasks']['menu'] = __( 'Finished', 'wtgtasksmanager' );
        $menu_array['finishedtasks']['pluginmenu'] = __( 'Finished', 'wtgtasksmanager' );
        $menu_array['finishedtasks']['name'] = "finishedtasks";
        $menu_array['finishedtasks']['title'] = __( 'Finished Tasks', 'wtgtasksmanager' ); 
        $menu_array['finishedtasks']['parent'] = 'alltasks'; 
        $menu_array['finishedtasks']['tabmenu'] = true;     
           
        // Closed Tasks
        $menu_array['closedtasks']['groupname'] = 'tasks';
        $menu_array['closedtasks']['slug'] = 'wtgtasksmanager_closedtasks'; 
        $menu_array['closedtasks']['menu'] = __( 'Closed', 'wtgtasksmanager' );
        $menu_array['closedtasks']['pluginmenu'] = __( 'Closed', 'wtgtasksmanager' );
        $menu_array['closedtasks']['name'] = "closedtasks";
        $menu_array['closedtasks']['title'] = __( 'Closed Tasks', 'wtgtasksmanager' ); 
        $menu_array['closedtasks']['parent'] = 'alltasks'; 
        $menu_array['closedtasks']['tabmenu'] = true; 
        
        // Cancelled Tasks
        $menu_array['cancelledtasks']['groupname'] = 'tasks';
        $menu_array['cancelledtasks']['slug'] = 'wtgtasksmanager_cancelledtasks'; 
        $menu_array['cancelledtasks']['menu'] = __( 'Cancelled', 'wtgtasksmanager' );
        $menu_array['cancelledtasks']['pluginmenu'] = __( 'Cancelled', 'wtgtasksmanager' );
        $menu_array['cancelledtasks']['name'] = "cancelledtasks";
        $menu_array['cancelledtasks']['title'] = __( 'Cancelled Tasks', 'wtgtasksmanager' ); 
        $menu_array['cancelledtasks']['parent'] = 'alltasks'; 
        $menu_array['cancelledtasks']['tabmenu'] = true; 
        
        // Create Tasks Tools
        $menu_array['createtasks']['groupname'] = 'tasks';
        $menu_array['createtasks']['slug'] = 'wtgtasksmanager_createtasks'; 
        $menu_array['createtasks']['menu'] = __( 'Create', 'wtgtasksmanager' );
        $menu_array['createtasks']['pluginmenu'] = __( 'Create Tasks', 'wtgtasksmanager' );
        $menu_array['createtasks']['name'] = "createtasks";
        $menu_array['createtasks']['title'] = __( 'Create Tasks', 'wtgtasksmanager' ); 
        $menu_array['createtasks']['parent'] = 'alltasks'; 
        $menu_array['createtasks']['tabmenu'] = true; 
        
        // Import Tasks
        $menu_array['importtasks']['groupname'] = 'tasks';
        $menu_array['importtasks']['slug'] = 'wtgtasksmanager_importtasks'; 
        $menu_array['importtasks']['menu'] = __( 'Import Tasks', 'wtgtasksmanager' );
        $menu_array['importtasks']['pluginmenu'] = __( 'Import Tasks', 'wtgtasksmanager' );
        $menu_array['importtasks']['name'] = "importtasks";
        $menu_array['importtasks']['title'] = __( 'Import Tasks', 'wtgtasksmanager' ); 
        $menu_array['importtasks']['parent'] = 'alltasks'; 
        $menu_array['importtasks']['tabmenu'] = true; 

        /*                            
        ######################################################
        #                                                    #
        #                MILESTONES SECTION                  #
        #                                                    #
        ###################################################### 
           
        // Create Milestones - one or more tasks make up a milestone, all must be complete for milestone to be reached  
        $menu_array['createmilestones']['groupname'] = 'milestones';
        $menu_array['createmilestones']['slug'] = 'wtgtasksmanager_createmilestones'; 
        $menu_array['createmilestones']['menu'] = __( 'Milestones', 'wtgtasksmanager' );
        $menu_array['createmilestones']['pluginmenu'] = __( 'Create Milestones', 'wtgtasksmanager' );
        $menu_array['createmilestones']['name'] = "createmilestones";
        $menu_array['createmilestones']['title'] = __( 'Create Milestones', 'wtgtasksmanager' ); 
        $menu_array['createmilestones']['parent'] = 'parent'; 
        $menu_array['createmilestones']['tabmenu'] = true; 
        */
                           
        return $menu_array;
    }
} 
?>
