<?php

/**
 * Plugin Name: Mixtape Machine
 * Description: A customizable retro sidebar widget that brings your SoundCloud playlists straight into the 20th century.
 * Author: Roktopus Industries
 * Plugin URI: http://www.mixtapemachine.com
 * Author URI: http://www.roktopus.net
 * Version: 1.1.1
 * Text Domain: mixtape_machine
 * License: GPLv2
**/

/**
*	Copyright (c) 2017, Dave Hall (dave@roktopus.net)
*	All rights reserved.
*
*	Redistribution and use in source and binary forms, with or without
*	modification, are permitted provided that the following conditions are met:
*		* Redistributions of source code must retain the above copyright
*		  notice, this list of conditions and the following disclaimer.
*		* Redistributions in binary form must reproduce the above copyright
*		  notice, this list of conditions and the following disclaimer in the
*		  documentation and/or other materials provided with the distribution.
*		* Neither the name of the organization nor the
*		  names of its contributors may be used to endorse or promote products
*		  derived from this software without specific prior written permission.
*
*	THIS SOFTWARE IS PROVIDED BY DAVE HALL ''AS IS'' AND ANY
*	EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
*	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
*	DISCLAIMED. IN NO EVENT SHALL <copyright holder> BE LIABLE FOR ANY
*	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
*	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
*	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
*	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
*	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
*	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

add_action( 'load-widgets.php', 'mixtape_machine_load' );
function mixtape_machine_load() {    
	wp_enqueue_style( 'wp-color-picker' );        
  wp_enqueue_script( 'wp-color-picker' );       
}

class mixtape_machine extends WP_Widget {

	// constructor
	function mixtape_machine() {
		parent::__construct(false, 'Mixtape Machine' );
	}

// widget form creation
function form($instance) {

// Check values
if( $instance) {
     $color = esc_attr($instance['color']);
     $embed = $instance['embed'];
     $creds = esc_attr($instance['creds']);
     $fonts = esc_attr($instance['fonts']);
} else {
     $color = '#990000';
     $embed = '';
     $creds = 0 ;
     $fonts = 1 ;
}
?>

<script type='text/javascript'>
	jQuery(document).ready(function($) {
		$('.my-color-picker').wpColorPicker();
	});

</script>

<label for="<?php echo $this->get_field_id('embed'); ?>">SoundCloud Embed Code:</label>
<textarea class="widefat" style="height: 200px" id="<?php echo $this->get_field_id('embed'); ?>" name="<?php echo $this->get_field_name('embed'); ?>"><?php echo $embed; ?></textarea>
<p>Go to SoundCloud and find any track or playlist. Click 'Share' then 'Embed' and copy the code it generates (use the standard iframe code, not the WordPress version). Paste that in here.</p>

<p>
<label for="<?php echo $this->get_field_id('color'); ?>">Background color:</label><br>
<input class="my-color-picker" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo $color; ?>" />
</p>

<p><?php echo __('If you like Mixtape Machine, you\'ll love', 'mixtape_machine'); ?> <a href="http://www.roktopus.net/product/retro-tv">Retro TV</a></p>

<p><a href="http://wordpress.org/support/view/plugin-reviews/mixtape-machine" title="Be brutal..."><?php echo __('Review this plugin', 'mixtape_machine'); ?></a></p>

<?php
}

// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['color'] = strip_tags($new_instance['color']);
      $instance['embed'] = $new_instance['embed'];
      $instance['creds'] = $new_instance['creds'];
      $instance['fonts'] = $new_instance['fonts'];
     return $instance;
}

// display widget
function widget($args, $instance) {
   extract( $args );
   $color = $instance['color'];
   $embed = $instance['embed'];
   $creds = $instance['creds'];
   $fonts = $instance['fonts'];
   echo $before_widget;

wp_enqueue_style('mxm_style', plugins_url( 'mixtape_machine.css', __FILE__ ) );
wp_enqueue_script( 'mxm_api', plugin_dir_url( __FILE__ ) . 'api.js',  array(), '', true  );  
wp_enqueue_script( 'mxm_script', plugin_dir_url( __FILE__ ) . 'mixtape-machine.js', array('jquery'), '', true  );  

?>

  <script>
    var mxm_path = '<?php echo plugins_url('', __FILE__ ); ?>';
  </script>
	<div class="widget-text mxm_player actionButtons" style="background: <?php echo $color; ?>">
        <div class="mxm_listmask">
        <p class="mxm_closer"><?php echo __('Close X','mixtape_machine'); ?></p>
        </div>
        <div class="mxm_burger"><span></span><span></span><span></span></div>
        <div class="mxm_light"></div>
        <div class="mxm_track-info">
            <div class="mxm_progress"></div>
            <a href="javascript:void(0)"><span></span></a>
        </div>
        <button class="play"></button>
        <button class="pause"></button>
        <button class="prev"></button>
        <button class="next"></button>
        <div class="mxm_tracklist"></div>
        <?php echo $embed; ?>

        <img class="mxm_panel" src="<?php echo plugins_url('skins/default/controls.png', __FILE__ ); ?>" >
        <img class="mxm_state play-state" src="<?php echo plugins_url('skins/default/controls-play.png', __FILE__ ); ?>">
        <img class="mxm_state stop-state" src="<?php echo plugins_url('skins/default/controls-stop.png', __FILE__ ); ?>">
        <img class="mxm_state rw-state" src="<?php echo plugins_url('skins/default/controls-rw.png', __FILE__ ); ?>">
        <img class="mxm_state ff-state" src="<?php echo plugins_url('skins/default/controls-ff.png', __FILE__ ); ?>">
        <div style="display: none">xlkj445ddkdjhhgppp00R</div>
	</div>


<?php
   echo $after_widget;
  }
}

add_action('widgets_init', create_function('', 'return register_widget("mixtape_machine");'));

?>