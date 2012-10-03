<?php
/*
Plugin Name: YIW Custom Types Progetti
Plugin URI: http://www.yourinspirationweb.com
Description: Questo plugin aggiunge un custom post type progetto
Author: Giustino Borzacchiello
Version: 0.2
*/

add_action('init', 'crea_progetti'); #[1]
function crea_progetti() {

    $labels = array(
        'name'               => __('Progetti'),
        'singular_name'      => __('Progetto'),
        'add_new'            => __('Aggiungi progetto'),
        'add_new_item'       => __('Nuovo Progetto'),
        'edit_item'          => __('Modifica Progetto'),
        'new_item'           => __('Nuovo Progetto'),
        'all_items'          => __('Elenco Progetti'),
        'view_item'          => __('Visualizza Progetto'),
        'search_items'       => __('Cerca Progetto'),
        'not_found'          => __('Progetto non trovato'),
        'not_found_in_trash' => __('Progetto non trovato nel cestino'),
    );

    $args = array(
        'labels'             => $labels, # [2]
        'public'             => true,    # [3]
        'rewrite'            => array('slug' => 'progetti'), # [4]
        'has_archive'        => true, # [5]
        'hierarchical'       => false, # [6]
        'menu_position'      => 5, # [7]
        'supports'           => array( # [8]
            'title',
            'editor',
            'thumbnail'
        ),
    );

    register_post_type('progetto', $args); #[9]
}

/*===============================================
META BOXES
*/
    add_action('add_meta_boxes', 'yiw_progetti_meta_boxes');
    function yiw_progetti_meta_boxes()
    {
        add_meta_box('yiw_progetti', #id arbitrario univoco
            __('Dettagli progetto'), #Titolo del box
            'yiw_progetti_box',      #Funzione da richiamare per la creazione del box
            'progetto',              #Post Type a cui applicare il box
            'side');
    }

    function yiw_progetti_box($post)
    {
    ?>
    <p>Aggiungi i dettagli del progetto:</p>
    <p><label for="yiw_progetti_link"><?php _e('Link al sito'); ?></label>
        <input type="text" id="yiw_progetti_link" name="yiw_progetti_link" class="widefat"
               value="<?php echo esc_attr(get_post_meta($post->ID, 'yiw_progetti_link', true)); ?>"/></p>

    <p><label for="yiw_progetti_anno"><?php _e('Anno'); ?></label>
        <input type="text" id="yiw_progetti_anno" name="yiw_progetti_anno" class="widefat"
               value="<?php echo esc_attr(get_post_meta($post->ID, 'yiw_progetti_anno', true)); ?>"/></p>
    <?php
    }

add_action('save_post', 'yiw_progetti_save_details', 10, 2);
function yiw_progetti_save_details($post_id, $post)
{
    // Non salvare se si tratta di revisioni
    if($post->post_type === 'revision') { return; }

    if(isset($_POST['yiw_progetti_link'])) {

        update_post_meta($post_id, 'yiw_progetti_link', esc_url($_POST['yiw_progetti_link']));
        update_post_meta($post_id, 'yiw_progetti_anno', intval($_POST['yiw_progetti_anno']));
    }
}

/*==========================================
TASSONOMIA
*/
add_action( 'init', 'yiw_skill_taxonomy' );
function yiw_skill_taxonomy() {

    $labels = array(
        'name'               => __('Skills'),
        'singular_name'      => __('Skill'),
        'search_items'       => __('Cerca skill'),
        'popular_items'      => __('Skill più utilizzate'),
        'all_items'          => __('Elenco skill'),
        'edit_item'          => __('Modifica skill'),
        'add_new_item'       => __('Nuova skill'),
        'separate_items_with_commas' => __('Separa le skill con una virgola'),
        'choose_from_most_used' => __('Scegli le skill più utilizzate'),
    );

    $args = array(
        'labels' => $labels,
        'rewrite' => array( 'slug' => 'skill' ),
    );
    register_taxonomy('skills','progetto', $args);
}

/*==========================================0
CUSTOM COLUMNS
*/
add_filter('manage_edit-progetto_columns', 'yiw_set_columns_progetto');
function yiw_set_columns_progetto($old_columns)
{
    $progetti_col = array(
        'cb'     => '<input type="checkbox">',
        'title'  => __('Nome Progetto'),
        'skills' => __('Skills'),
        'anno'   => __('Anno'),
    );
    return $progetti_col;
}

add_action('manage_progetto_posts_custom_column', 'yiw_get_progetto_columns', 10, 2);
function yiw_get_progetto_columns($col, $post_id)
{
    switch($col) {
        case 'skills':
            $skills = get_the_terms($post_id, 'skills');
            if($skills) {
                foreach($skills as $skill) {
                    echo $skill->name . ' ';
                }
            } else {
                echo 'No skills';
            }

            break;
        case 'anno':
            $anno = get_post_meta($post_id, 'yiw_progetti_anno', true);
            echo $anno;
            break;
        default:
            break;
    }
}



