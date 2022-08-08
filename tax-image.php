<?php

/**
 * Plugin Name: Taxonomy Image for Custom Post Type
 * Plugin URI: https://github.com/salmanrazak/taxonomy-image
 * Author: Salman Razak
 * Author URI: https://github.com/salmanrazak
 * Description: Add images to CPT taxonomies.
 * Version: 1.0.0
 * License: GPL2 or Later
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: taxonomy-image
*/

add_action( 'admin_footer', 'remove_taxonomy_extra_fields' );
function remove_taxonomy_extra_fields(){
    global $current_screen;
    //var_dump($current_screen);
    if( 'edit-btype' === $current_screen->id ){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#tag-description').parent().remove();
                $('#tag-slug').parent().remove();
            });
        </script>
        <?php
    }
}

// remove columns
add_filter( 'manage_edit-btype_columns', 'remove_btype_columns' );
function remove_btype_columns( $columns ){
    if ($columns['description'] ){
        unset($columns['description']);
        unset($columns['slug']);

        $columns['image'] = 'Image';
    }
    return $columns;
}

// add info to the new columns
add_action( 'manage_btype_custom_column', 'manage_image_column', 10, 3 );
function manage_image_column( $string, $columns, $term_id ){
    switch ($columns) {
        case 'btype-image':
            echo $term_id . '-btype-image';
            break;
    }
}

add_action( 'btype_add_form_fields', 'add_btype_form_fields' );
function add_btype_form_fields(   ){
    $placeholder = plugin_dir_url( __FILE__ ) . 'image/placeholder.png';
    ?>
    <div class="form-field">
        <label for="btype-image">Add Image</label>
        <img src="<?php echo $placeholder; ?>" id="image-placeholder" width="100" style="width: 100px; border: 1px solid #bbb; box-shadow: 0 3px 5px #ccc;margin: 5px 0;" alt=""><br>
        <input type="text" name="btype-image" id="btype-image" value="<?php echo $placeholder; ?>">
        <a href="#" class="remove-image" style="display: none;">Remove Image</a>
        <p>Attach category image.</p>
    </div>
    <?php
}

// add and update new image values
add_action( 'created_btype', 'created_btype_fields' );
add_action( 'updated_btype', 'created_btype_fields' );
function created_btype_fields( $term_id ){
    update_term_meta( $term_id, 'btype-image', sanitize_text_field($_POST['btype-image']) );
}