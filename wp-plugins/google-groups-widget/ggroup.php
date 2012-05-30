<?php
/*
Plugin Name: Google Group Widget
Plugin URI: http://www.cameronpreston.com/projects/plugins
Description: Google Group Widget, displays RSS feed of your google group.
Author: Cameron Preston
Version: 0.1
Author URI: http://www.cameronpreston.com/
*/
 
//error_reporting(E_ALL);

//register_activation_hook( __FILE__, array('Google_Groups', 'activate'));
//register_deactivation_hook( __FILE__, array('Google_Groups', 'deactivate'));

class Google_Groups extends WP_Widget {

	function Google_Groups() { // constructor
		$widget_ops = array('classname' => 'Google_Groups', 'description' => __( "Shows Google Groups information") );
		$control_ops = array('width' => 300, 'height' => 300);
		$this->WP_Widget('Google_Groups', __('Google Groups'), $widget_ops);
	}
/*	function activate(){
		$data = array( 'option1' => 'your_rss.xml');
		if ( ! get_option('Google_Groups')){
		  add_option('Google_Groups' , $data);
		} else {
		  update_option('Google_Groups' , $data);
		}
	  } //activate
	  
	  function deactivate(){
		delete_option('Google_Groups');
	  }*/
	  
	  function widget($args, $instance){
		global $wpdb;
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
		$group = empty($instance['group']) ? 'Group' : $instance['group'];
		$size = empty($instance['size']) ? 'Group' : $instance['size'];
		define('MYPLUGIN_PATH', get_settings('siteurl').'/wp-content/plugins/ggroups');
		// Before the widget
		echo $before_widget;
	print '<link rel="stylesheet" href="'.MYPLUGIN_PATH.'/ggroup.css" type="text/css" media="screen" />';

		echo('<div id="ggroup-wrap">');
		// The title
		echo('<div class="gimage">');
			echo $before_title;
//			echo('<img src="'.PLUGINDIR.'/ggroups/googlegroups.png"><br />');
			echo ($title . $after_title);
		echo('</div><br/>');
		echo('<div id="gtext">');
			//$site = "http://groups.google.com/group/".$group."/feed/rss_v2_0_msgs.xml";
			include_once(ABSPATH.WPINC.'/rss.php');
			wp_rss('http://groups.google.com/group/'.$group.'/feed/rss_v2_0_msgs.xml', 7);
		echo('</div>');
		echo('<div class="gjoin"><a href="http://groups.google.com/group/'.$group.'/subscribe">Join '.$title.'!</a></div>');
		
		//		echo '<div style="text-align:center;padding:10px;">' . parseRDF($site) . 
	//		'<br />' . 
		//	$size . 
			//"</div>";
		echo('</div>'); //end ggroup-wrap

		echo $after_widget;
	}  //widget
	/**
	  * Creates the edit form for the widget.
	  * */
	  function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'', 'group'=>'My-Group', 'size'=>'7') );

		$title = htmlspecialchars($instance['title']);
		$group = htmlspecialchars($instance['group']);
		$size = htmlspecialchars($instance['size']);

		# Output the options
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Google Group Name:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		# Group
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('group') . '">' . __('Google Unique Handle:') . ' <input style="width: 200px;" id="' . $this->get_field_id('group') . '" name="' . $this->get_field_name('group') . '" type="text" value="' . $group . '" /></label></p>';
		# Size 
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('size') . '">' . __('# Titles to Display:') . ' <input style="width: 50px;" id="' . $this->get_field_id('size') . '" name="' . $this->get_field_name('size') . '" type="text" value="' . $size . '" /></label></p>';
	}
	

	/**
	  * Saves the widgets settings.
	  *
	  */
	  function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['group'] = strip_tags(stripslashes($new_instance['group']));
		$instance['size'] = strip_tags(stripslashes($new_instance['size']));

	  return $instance;
	}
/*	function register(){
		register_sidebar_widget('Google Group', array('Google_Groups', 'widget'));
		register_widget_control('Google Group', array('Google_Groups', 'control'));
	} //register*/
	
	public function showRSS($group)
	{
			print "<div align='left'>\n";
			$site = "http://groups.google.com/group/".$group."/feed/rss_v2_0_msgs.xml";
			//parseRDF($site);
			print "</div>\n";
	}
  
} //Google_Groups Class
 

/**
  * Register Hello World widget.
  *
  * Calls 'widgets_init' action after the Hello World widget has been registered.
  */
  function Google_GroupsInit() {
	register_widget('Google_Groups');
  }
  add_action('widgets_init', 'Google_GroupsInit');
?>