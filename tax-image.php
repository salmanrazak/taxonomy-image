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

include_once( 'add-menu.php');

if(esc_attr( get_option('custom_post_type') ) != null && esc_attr( get_option('custom_post_type_taxonomy') ) != null ){

    // hide unwated tags or fields
    add_action( 'admin_footer', 'remove_taxonomy_extra_fields' );
    function remove_taxonomy_extra_fields(){
        global $current_screen;
        //var_dump($current_screen);
        if( 'edit-'.esc_attr( get_option('custom_post_type_taxonomy') ) === $current_screen->id ){
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $('#tag-description').parent().remove();
                    $('#tag-slug').parent().remove();
                    $('#description').closest('.form-field').remove();
                    $('#slug').closest('.form-field').remove();
                });
            </script>
            <?php
        }
    }

    // remove columns from right table
    add_filter( 'manage_edit-'.esc_attr( get_option('custom_post_type_taxonomy') ) . '_columns', 'remove_btype_columns' );
    function remove_btype_columns( $columns ){
        if ($columns['description'] ){
            unset($columns['description']);
            unset($columns['slug']);

            $columns['image'] = 'Image';
        }
        return $columns;
    }

    // display new  columns with new data
    add_action( 'manage_'.esc_attr( get_option('custom_post_type_taxonomy') ) . '_custom_column', 'manage_image_columns', 10, 3 );
    function manage_image_columns( $string, $columns, $term_id ){
        switch ($columns) {
            case 'image':
                echo '<img src="' . get_term_meta( $term_id, esc_attr( get_option('custom_post_type_taxonomy') ).'-image', true ) . '" width="80">';
                break;
        }
    }

    // add fields to screen
    add_action( esc_attr( get_option('custom_post_type_taxonomy') ) . '_add_form_fields', 'add_btype_form_fields' );
    function add_btype_form_fields(){

        $placeholder = plugin_dir_url( __FILE__ ) . 'image/placeholder.png';
        ?>
        <div class="form-field">
            <label for="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>-image">Add Image</label>
            <img src="<?php echo $placeholder; ?>" id="image-placeholder" width="100" style="display: block; width: 100px; border: 1px solid #bbb; box-shadow: 0 3px 5px #ccc; margin: 5px 0 0;" alt=""><br>
            <button type="button" class="button add-image-button">Add Image</button>
            <input type="hidden" name="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>-image" id="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>-image" value="<?php echo $placeholder; ?>">
            <a href="#" class="remove-image" style="display: none;">Remove Image</a>
            <p>Attach category image.</p>
        </div>
        <?php
        
    }

    // edit image field
    add_action( esc_attr( get_option('custom_post_type_taxonomy') ) . '_edit_form_fields', 'edit_btype_form_fields', 10, 2 );
    function edit_btype_form_fields( $term, $taxonomy ){
        $value = get_term_meta($term->term_id, esc_attr( get_option('custom_post_type_taxonomy') ).'-image', true);
        $placeholder = plugin_dir_url( __FILE__ ) . 'image/placeholder.png';
        ?>
        <tr class="form-field">
            <th scope="row"><label for="btype-image">Add Image</label></th>
            <td>
                <?php
                if($value){
                    echo '<img src="'. $value .'" id="image-placeholder" width="100" style="width: 100px; border: 1px solid #bbb; box-shadow: 0 3px 5px #ccc;margin: 5px 0;" alt=""><br>';
                } else {
                    echo '<img src="'. $placeholder .'" id="image-placeholder" width="100" style="width: 100px; border: 1px solid #bbb; box-shadow: 0 3px 5px #ccc;margin: 5px 0;" alt=""><br>';
                }
                ?>
                <input type="hidden" name="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>-image" id="<?php echo esc_attr( get_option('custom_post_type_taxonomy') ); ?>-image" value="<?php echo $value; ?>">
                <a href="#" class="update-image-button" style="">Update Image</a>
                <p>Attached category image.</p>
            </td>
        </tr>
        <?php
    }

    // add and update new image values
    add_action( 'created_'.esc_attr( get_option('custom_post_type_taxonomy') ), 'created_btype_fields' );
    add_action( 'edited_'.esc_attr( get_option('custom_post_type_taxonomy') ), 'created_btype_fields' );
    function created_btype_fields( $term_id ){
        update_term_meta( $term_id, esc_attr( get_option('custom_post_type_taxonomy') ).'-image', sanitize_text_field($_POST[esc_attr( get_option('custom_post_type_taxonomy') ).'-image']) );
    }

    // add jquery functionality
    add_action( 'admin_enqueue_scripts', 'media_scripts' );
    function media_scripts() {
        
        // WordPress media uploader scripts
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        // our custom JS
        wp_enqueue_script( 'imageUpload', plugin_dir_url( __FILE__ ) . 'tax-image.js', array( 'jquery' ) );
    }

}