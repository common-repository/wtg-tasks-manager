=== Plugin Name ===
Contributors: WebTechGlobal
Donate link: http://www.webtechglobal.co.uk/wtg-tasks-manager-wordpress/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: WTG, Task Manager, Tasks, Task Management, Task, todo, TO DO, to-do, Workflow, project management
Requires at least: 3.8.0
Tested up to: 4.3.1
Stable tag: trunk

Task management with a plan - this plugin will grow to meet the needs of online business managed within WordPress.

== Description ==

Created to offer a workflow and in early stages. This plugin is part of the WebTechGlobal projects
system which brings many plugins together to make a service orientated project
management environment.

Plans for this plugin include being able to create new tasks from any device. The 
development goal
is to be able to quickly record ideas, faults, customer requests and general
requirements a project may have.

When integrated with other WTG plugins what you can offer customers, clients and
developers will increase greatly i.e. the tickets plugins will offer the ability
to turn a ticket into a task for developers to complete and the creator of the
ticket will get updates on the task completion even when the ticket is closed.   

The main goal is a transparant development environment that everyone within a 
websites community can access in one way or another i.e. displaying tasks
publicaly so that freelancers can access and offer to complete them. 

= Main Links = 
*   <a href="http://www.webtechglobal.co.uk/wtg-tasks-manager/" title="WebTechGlobals Task Manager Portal">Plugins Portal</a>
*   <a href="http://forum.webtechglobal.co.uk/viewforum.php?f=40" title="WebTechGlobal Forum for Task Manager">Plugins Forum</a>
*   <a href="https://www.facebook.com/pages/WTG-Tasks-Manager-for-WordPress/302610946614839" title="WTG Task Manager Facebook Page">Plugins Facebook</a>
*   <a href="http://www.webtechglobal.co.uk/category/wordpress/wtg-tasks-manager/" title="WebTechGlobal blog category for tasks manager">Plugins Blog</a>
*   <a href="http://www.twitter.com/WebTechGlobal" title="WebTechGlobal Tweets">Plugins Twitter</a>
*   <a href="http://www.youtube.com/playlist?list=PLMYhfJnWwPWD5P2vNf2c9gRsRNqRSVSoV" title="Official YouTube channel for WTG Tasks Manager">YouTube Playlist</a>

= Feature List = 

Remember this plugin is a beta so the list will be short for a while.                                
   
1. Use WYSIWYG editor to describe tasks in detail.
1. Tasks are made as a custom post type.
1. Every post box can be added to the Dashboard as a widget.     
 
== Installation ==

Please install WTG Tasks Manager from WordPress.org by going to Plugins --> Add New and searching "WTG Tasks Manager". This is safer and quicker than any other methods.

== Frequently Asked Questions ==

= Can I import tasks from a .csv file? =
You can import tasks to WTG Tasks Manager from a .csv file using my CSV 2 POST plugin. Tasks in this plugin are
simply posts and CSV 2 POST allows the creation of posts for custom post types. Please use my forum to get help
on this subject.

= Can I integrate WTG Tasks Manager with WTG Project Manager? =
Yes these two plugins are being designed to work together and create a larger solution. Other plugins will integrate with both of these
and the larger goal is to create a professional project management system.

= Why choose WTG Tasks Manager over other tasks management plugins? =
I have a keen interest in allowing freelancers to access and complete tasks. I also want to be able to award credits instead of money
for selected tasks. Credits would be spent on services and products. I have many ideas aimed at getting the community involved in development
that I do not see in other plugins. As I roll out new features or new plugins that integrate with this one, you may find your desired solution.

= As a WebTechGlobal subscriber can I get higher priority support for this plugin? =
Yes - subscribers are put ahead of my Free Workflow and will not only result in a quicker response for support
but requests for new features are marked with higher priority.

= Can I hire you to customize the plugin for me? =
Yes - you can pay to improve the plugin to suit your needs. However many improvements will be done free.
Please post your requirements on the plugins forum first before sending me Paypal or Bitcoins. If your request is acceptable
within my plans it will always be added to the WTG Tasks Management plugin which is part of my workflow system. The tasks
priority can be increased based on your WebTechGlobal subscription status, donations or contributions you have made.

== Screenshots ==

1. Manage Multiple Projects.
2. Simple Import Statistics.
3. Category Data Selection.

== Upgrade Notice ==

Please update this plugin using your WordPress Installed Plugins screen. Click on Update Now under this plugins details when an update is ready.
This method is safer than using any other source for the files.

== Changelog == 

= 0.0.40 = 
* Feature Changes
    * Public tasks list HTML improved.
    * Pindol Theme styles added to public tasks list - get your themes requirements added by going to the plugins forum.
* Technical Information
    * None
* Known Issues
    * Search abilities are limited.
    * Posts view "All" does not show all posts - I think it is a WordPress limitation at this time when using custom statuses. I'll seek a workaround like hooking into the query.
    * post-new.php for the plugins custom post type (Create Task) cannot be used properly yet - it does not have all required fields. Please use form provided on plugins own pages until it is improved.

= 0.0.39 = 
* Feature Changes
    * None
* Technical Information
    * Bug fix - accidental variable deletion in function screens_menuoptions() right before release.
* Known Issues
    * Search abilities are limited.
    * Posts view "All" does not show all posts - I think it is a WordPress limitation at this time when using custom statuses. I'll seek a workaround like hooking into the query.
    * post-new.php for the plugins custom post type (Create Task) cannot be used properly yet - it does not have all required fields. Please use form provided on plugins own pages until it is improved.

= 0.0.38 = 
* Feature Changes
    * New shortcode: [displaytaskslist projectid="1" status="newtask" limit="2" style="basic"]
    * Cont. Displays a list of tasks. This is just the beginning of a larger plan to display tasks for public interaction i.e. freelancers.
    * Progress column removed from Cancelled Tasks view. 
* Technical Information
    * Division by Zero error fixed on All tasks view.
    * settings_array.php removed.
    * Default settings array now in class-configuration.php -> default_settings().
* Known Issues
    * Search abilities are limited.
    * Posts view "All" does not show all posts - I think it is a WordPress limitation at this time when using custom statuses. I'll seek a workaround like hooking into the query.
    * post-new.php for the plugins custom post type (Create Task) cannot be used properly yet - it does not have all required fields. Please use form provided on plugins own pages until it is improved.
     
== Plugin Author == 

Thank you for considering WTG Tasks Manager. 

== Donators ==
These donators have giving their permission to add their site to this list so that plugin authors can
request their support for their own project. Please do not request donations but instead visit their site,
show interest and tell them about your own plugin - you may get lucky. 

* <a href="" title="">Ryan Bayne from WebTechGlobal</a>

== Contributors: Translation ==
These contributors helped to localize WTG Tasks Manager by translating my endless dialog text.

* None Yet

== Contributors: Code ==
These contributers typed some PHP or HTML or CSS or JavaScript or Ajax for WTG Tasks Manager. Bunch of geeks really! 

* None Yet

== Contributors: Design ==
These contributors created graphics for the plugin and are good with Photoshop. No doubt they spend their time merging different species together!

* None Yet

== Contributors: Video Tutorials ==
These contributors published videos on YouTube or another video streaming website for the community to enjoy...and maybe to get some ad clicks.

* None Yet

== Version Numbers and Updating ==

Explanation of versioning used by myself Ryan Bayne. The versioning scheme I use is called "Semantic Versioning 2.0.0" and more
information about it can be found at http://semver.org/ 

These are the rules followed to increase the WTG Tasks Manager plugin version number. Given a version number MAJOR.MINOR.PATCH, increment the:

MAJOR version when you make incompatible API changes,
MINOR version when you add functionality in a backwards-compatible manner, and
PATCH version when you make backwards-compatible bug fixes.
Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

= When To Update = 

Browse the changes log and decide if you need any recent changes. There is nothing wrong with skipping versions if changes do not
help you - look for security related changes or new features that could really benefit you. If you do not see any you may want
to avoid updating. If you decide to apply the new version - do so after you have backedup your entire WordPress installation 
(files and data). Files only or data only is not a suitable backup. Every WordPress installation is different and creates a different
environment for WTG Task Manager - possibly an environment that triggers faults with the new version of this software. This is common
in software development and it is why we need to make preparations that allow reversal of major changes to our website.