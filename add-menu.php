<?php

// hide unwated tags or fields
add_action( 'admin_footer', 'add_tabs_toggle_function' );
function add_tabs_toggle_function(){
    global $current_screen;
    //var_dump($current_screen);
    if( 'settings_page_my-custom-submenu-page' === $current_screen->id ){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#tabs .tab-content').hide();
                $('#tabs .tab-content:first').show();
                $('.nav-tab-wrapper a:first').addClass('nav-tab-active');

                $(".nav-tab-wrapper").on("click", ".nav-tab", function(e) {
                e.preventDefault();

                $(".nav-tab a").removeClass("nav-tab-active");
                $(".tab-content").hide();
                $(this).addClass("nav-tab-active");
                $($(this).attr("href")).show();
                });
            });
        </script>
        <?php
    }
}

// add settings menu
add_action('admin_menu','theme_setting_function');
function theme_setting_function() {
    //add_submenu_page( 'options-general.php', 'Taxonomy Image Setting', 'Taxonomy Image Setting', 'manage_options', 'my-custom-submenu-page', 'theme_setting_callback' );
    add_submenu_page( 'edit.php?post_type=books', 'Taxonomy Image', 'Taxonomy Image', 'manage_options', 'my-custom-submenu-page', 'theme_setting_callback' );
}

function register_setting_fields_function(){

    register_setting( 'taxonomy-image', 'custom_post_type' );
    register_setting( 'taxonomy-image', 'custom_post_type_taxonomy' );

}
add_action( 'admin_init', 'register_setting_fields_function' );
 
function theme_setting_callback() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Taxonomy Image Setting</h1>
        <hr class="wp-header-end">

        <form method="post" action="options.php">
            <?php settings_fields( 'taxonomy-image' ); do_settings_sections( 'taxonomy-image' ); ?>
            <div id="tabs">
                <h2 class="nav-tab-wrapper">
                    <a href="#tab-1" class="nav-tab">Custom Post Settings</a>                    
                </h2>
  
                <div id="tab-1" class="tab-content">
                    <div class="card">
                        <h2>Custom Posts</h2>
                        <label>Custom Post Type Name</label><br>
                        <input type="text" name="custom_post_type" value="<?php echo esc_attr( get_option('custom_post_type') ); ?>" placeholder="Enter custom post type name"><br>
                        <em><Strong>Note :- </Strong> Please enter the custom post type name "book" .</em>
                        <br><br>
                        <label>Custom Post Type Taxonomy Name</label><br>
                        <input type="text" name="custom_post_type_taxonomy" value="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>" placeholder="Enter custom post type taxonomy name"><br>
                        <em><Strong>Note :- </Strong> Please enter custom post type taxonmy name e.g. "btype".</em>
                        <?php submit_button( 'Save Settings' ); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php

    /*


    $args = array(
        'name' => 'books',
    );
     
    $post_types = get_post_types( $args, 'objects' );
    //var_dump($post_types);     
    foreach ( $post_types  as $post_type ) {
       echo '<div><input type="checkbox" name="' . $post_type->slug . '" id="' . $post_type->slug . '">' . $post_type->label . "</div>";       
    }

    $post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and', 'names' ); 
 
    echo '<ul>';
         
    foreach ( $post_types as $post_type ) {
     
       echo '<li>' . $post_type . '</li>';
    }
     
    echo '</ul>';
    */

}