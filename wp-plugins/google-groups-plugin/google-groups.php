<?php
/*
Plugin Name: Google Group Plugin
Plugin URI: http://www.retrofox.com.ar/plugins/google-groups-plugin
Description: Widget for google groups 
Author: Damian Suarez 
Version: 0.0.1
Author URI: http://www.retrofox.com.ar/
Licence: A "Slug" license name e.g. GPL2
*/

/*  Copyright 2012  Damian Suarez  (email : rdsuarez@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Translation support
 */

load_plugin_textdomain('widget_wp_github', false, basename( dirname( __FILE__ ) ) . '/languages' );

/**
 * Constants
 */
$siteurl = get_settings('siteurl');
define('ggp_path', $siteurl.'/wp-content/plugins/google-groups-plugin');
define('ggp_api_host', 'http://groups.google.com/group/');

/**
 * Class
 */

class Google_Groups_Widget extends WP_Widget {

  function __construct() {
    $options= array(
        'classname' => 'Google_Groups_Widget'
      , 'description' => __( "Google Groups information")
    );

    $control_ops = array('width' => 300, 'height' => 300);
    parent::__construct('google-groups-plugin', 'Google Groups Plugin', $options);
  }

  function widget($args, $instance){
    extract($args);
    extract($instance);

    $group = empty($instance['group']) ? 'Group' : $instance['group'];
    $size = empty($instance['size']) ? 'Group' : $instance['size'];

    // Before the widget
    echo $before_widget;
    print '<link rel="stylesheet" href="'.ggp_path.'/google-groups.css" type="text/css" media="screen" />';
?>

    <div id="google-groups-wrap">
      <div class="gimage">
      <?php if (!empty($instance['title'])) : ?> 
        <h2 class="title">
          <a target="_blank" href="http://groups.google.com/group/nodejs-es">
          <?php echo $title; ?>
          </a>
        </h2>
      <?php endif; ?>
      </div>

      <div id="google-groups-feeds"
      <?php
        include_once(ABSPATH.WPINC.'/rss.php');
        wp_rss(ggp_api_host.$group.'/feed/rss_v2_0_msgs.xml', 7);
      ?>
      </div>

      <form action="http://groups.google.com/group/nodejs-es/boxsubscribe" id="google-subscribe">
        <div id="google-subscribe-input">
          <label>Email:&nbsp;
            <input type="text" name="email" id="google-subscribe-email" />
            <input type="submit" name="go" value="Subscribirse" />
          </label>
        </div>
      </form>
    </div>

  <?php echo $after_widget; ?>

  <?php
  }

/**
* Creates the edit form for the widget.
 */

function form($instance){
  $instance = wp_parse_args(
      (array) $instance
      , array('title'=>''
      , 'group'=>'My-Group'
      , 'size'=>'7')
    );

  $title = htmlspecialchars($instance['title']);
  $group = htmlspecialchars($instance['group']);
  $size = htmlspecialchars($instance['size']);
  ?>

  <p>
    <label for="<?php echo $this->get_field_name('title') ?>">
      <?php  _e('Google Group Name:'); ?>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </label>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('group') ?>">
      <?php  _e('Google Unique Handle:'); ?>
      <input class="widefat" id="<?php echo $this->get_field_id('group'); ?>" name="<?php echo $this->get_field_name('group'); ?>" type="text" value="<?php echo $group; ?>" />
    </label>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('size') ?>">
      <?php  _e('# Titles to Display:'); ?>
      <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo $size; ?>" />
    </label>
  </p>

  <?php
  }


  /**
  * Saves the widgets settings.
   */

  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['group'] = strip_tags(stripslashes($new_instance['group']));
    $instance['size'] = strip_tags(stripslashes($new_instance['size']));

    return $instance;
  }

  public function showRSS($group) {
    print "<div align='left'>\n";
    $site = "http://groups.google.com/group/".$group."/feed/rss_v2_0_msgs.xml";
    print "</div>\n";
  }
}


function google_groups_init() {
  register_widget('Google_Groups_Widget');
}

add_action('widgets_init', 'google_groups_init');

?>
