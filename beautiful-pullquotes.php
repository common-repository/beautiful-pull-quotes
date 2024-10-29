<?php
/**
 * Plugin Name: Beautiful Pull Quotes
 * Plugin URI:  http://techcarnival.org/beautiful-pull-quotes/
 * Description: Instantly add stylish quotes to your content with cite and alignment, choose from 3 ready-made styles available.
 * Version: 1.0
 * Author: SaiKrishna Mundreti
 * Author URI: http://techcarnival.org
 * License: GPL2
 */

/*  Copyright 2016  SaiKrishna Mundreti  (krishnasai44@gmail.com)

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


// Attaching the stylesheet
function bpquotes_style() {
     // Registering the style 
    wp_register_style( 'bpquotes-styles', plugins_url( '/css/beautiful-pull-quotes.css', __FILE__ ), array(), '1.0', 'all' );
    // Enqueue the registered style 
    wp_enqueue_style( 'bpquotes-styles' );
}
add_action( 'wp_enqueue_scripts', 'bpquotes_style' );


//Adding the shortcode functionality 
add_shortcode( 'beautifulquote', 'bpquotes_shortcode' );

function bpquotes_shortcode($atts, $content){
$bpq_atts = shortcode_atts( array(
        'align' => 'left',
        'cite'  => null
        ), $atts );

    $useralign = '';
    switch ( $bpq_atts['align'] ) {
        case 'full':
            $useralign = ' bpq-full';
            break;
        case 'right':
            $useralign = ' bpq-right';
            break;
        default:
            $useralign = ' bpq-left';
            break;
    } 

    if ( isset($bpq_atts['cite']) && strlen($bpq_atts['cite']) > 1 ):
        $usercite = '<cite> — '.strip_tags( $bpq_atts['cite'] ).'</cite>';
    else:
        $usercite = null;
    endif;
       
    return '<div class="'.get_option('bpquotes_options_style').$useralign.'"><p>'.do_shortcode($content).'</p>'.$usercite.'</div>';
}



// Adding custom button to TinyMCE 

add_action('admin_head', 'bpquotes_add_tinymce_button');


function bpquotes_add_tinymce_button(){
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "bpquotes_add_tinymce_plugin");
        add_filter('mce_buttons', 'bpquotes_register_tinymce_button');
    }
}


function bpquotes_add_tinymce_plugin($plugin_array) {
    $plugin_array['bpquotes_tinymce_button'] = plugins_url( 'js/bpquotes_tinymce.js', __FILE__ ); 
    return $plugin_array;
}

function bpquotes_register_tinymce_button($buttons) {
   array_push($buttons, "bpquotes_tinymce_button");
   return $buttons;
}


function bpquotes_tinymce_css() {
    wp_enqueue_style('bpquotes-tinymce', plugins_url('/css/bpquotes_tinymce.css', __FILE__));
}
 
add_action('admin_enqueue_scripts', 'bpquotes_tinymce_css');


/* Beautiful PullQuotes Settings  */
if(is_admin())
{
add_action( 'admin_init', 'bpquotes_register_settings' );
}


function bpquotes_register_settings(){
        register_setting('bpquotes_options_group', 'bpquotes_options_style');
}

//Registering the settings page 
add_action( 'admin_menu', 'bpquotes_options' ); 

function  bpquotes_options() { 
    add_options_page( 
        'Beautiful Pull Quotes',
        'Beautiful PullQuotes',
        'manage_options',
        'bpquotes',
        'bpquotes_options_page'
    );
}

// Printing the settings page 
function bpquotes_options_page(){
    ?>
    <div class="wrap">
<center> <img class="bpq_logo" src=" <?php echo plugins_url('images/beautiful_pull_quotes.png',__FILE__); ?> ">    
</center>
<h2> Preview of Styles </h2>

<img  class="bpqstyle" src="<?php echo plugins_url('images/basic_view.png',__FILE__); ?> ">
<img  class="bpqstyle" src="<?php echo plugins_url('images/gradient_view.png',__FILE__); ?> ">
<img  class="bpqstyle" src="<?php echo plugins_url('images/classic_view.png',__FILE__); ?> ">

<form name="bpquotes_settings_form" method="post" action="options.php">
<?php @settings_fields('bpquotes_options_group'); ?>
<?php @do_settings_fields('bpquotes_options_group'); ?>

<h3>Choose your pull quote style: 
<select name="bpquotes_options_style" id="bpquotes_options_style" >
    <option value="bpq_basic" <?php if( get_option('bpquotes_options_style')=="bpq_basic"){ echo 'selected';} ?> >Basic Style</option>
    <option value="bpq_gradient" <?php if( get_option('bpquotes_options_style')=="bpq_gradient"){ echo 'selected';} ?> >Gradient Style</option>
    <option value="bpq_classic" <?php if( get_option('bpquotes_options_style')=="bpq_classic"){ echo 'selected';} ?> >Classic Style</option>
</select>
</h3>

<?php @submit_button(); ?>

<h4 align=center><i> Thank you for choosing us! </i></h4>
<p align=center class="bpq_credits">Report a bug, give a suggetion, or just say hi by sending a mail to <b>krishnasai44@gmail.com</b> </p>
</div>

<?php
}

// Plugin Action Link 
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bpquotes_action_link' );

function bpquotes_action_link ( $links ) {
 $links[] = '<a href="' . admin_url( 'options-general.php?page=bpquotes' ) . '">Settings</a>';
return array_merge( $links);
}
