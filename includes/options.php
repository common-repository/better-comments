<?php
/**
 * Options page
 * PHP version 7
 *
 * @category Options
 * @package  Better_Comments
 * @author   Utopique <support@utopique.net>
 * @license  GPL https://utopique.net
 * @link     https://utopique.net
 */

namespace BetterComments\Plugin\Options;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Field\Complex_Field;

// Import the sky_bc_fs() function as defined in the plugin
// and rename it as BC_FS so we can use it.
use function BetterComments\Plugin\sky_bc_fs as BC_FS;

/**
 * Créée une page d'options pour notre plugin.
 * Les onglets sont initialement vides mais sont définis et remplis de champs via des filtres définis plus bas.
 *
 * @link https://carbonfields.net/docs/containers-theme-options/
 *
 * @return void
 */
function options_initialize_admin_page() {
    $tabs = apply_filters( 'sky_bco_options_tabs', array() );

    if ( empty( $tabs ) ) {
        return;
    }

    // On crée la page d'options.
    $theme_options = Container::make( 'theme_options', __( 'Better Comments', 'better-comments' ) );

    // On définit son slug utilisé dans l'URL de la page.
    $theme_options->set_page_file( 'better-comments' );

    // On définit son nom dans le menu d'admin.
    $theme_options->set_page_menu_title( __( 'Better Comments', 'better-comments' ) );

    // On définit sa position dans le menu d'admin, juste après le menu Comments.
    $theme_options->set_page_menu_position( 25 );

    // On change son icône dans le menu d'admin.
    // $theme_options->set_icon( 'dashicons-testimonial' );
    // Notes:
    // 1. remove rect declaration
    // 2. fill must be set (white).
    $theme_options->set_icon( 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M15.29 8.85l-4.73 4.74l-1.85-1.86a1 1 0 0 0-1.42 1.42l2.56 2.56a1 1 0 0 0 1.42 0l5.44-5.44a1 1 0 1 0-1.42-1.42zM12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20zm0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20z" fill="#626262"/></svg>' ) );

    // On gère les classes.
    if ( BC_FS()->is_plan( 'plus', true ) ) {
        $plan = 'plan-plus';
    }
    if ( BC_FS()->is_plan( 'pro', true ) ) {
        $plan = 'plan-pro';
    }
    if ( BC_FS()->is_plan( 'business', true ) ) {
        $plan = 'plan-business';
    }
    if ( BC_FS()->is_not_paying() || BC_FS()->is_free_plan() ) {
        $plan = 'free';
    }
    $theme_options->set_classes( $plan );

    // Et enfin, pour chaque onglet, on charge les champs de l'onglet concerné.
    foreach ( $tabs as $tab_slug => $tab_title ) {
        $theme_options->add_tab(
            esc_html( $tab_title ),
            apply_filters( "sky_bco_options_fields_tab_{$tab_slug}", array() )
        );
    }
}
add_action(
    'carbon_fields_register_fields',
    __NAMESPACE__ . '\\options_initialize_admin_page'
);


/**
 * Liste des onglets dans lesquels seront rangés les champs de notre page d'options.
 *
 * @param array $tabs [].

 * @return array $tabs Tableau des onglets : la clé d'une entrée est utilisée par le filtre chargeant les champs de l'onglet, la valeur d'une entrée est le titre de l'onglet.
 */
function options_set_tabs( $tabs ) {
    return array(
        'form'      => __( 'Form', 'better-comments' ),
        'design'    => __( 'Comments', 'better-comments' ),
        'tweaks'    => __( 'Tweaks', 'better-comments' ),
        'seo'       => __( 'SEO', 'better-comments' ),
        'disable'   => __( 'Disable', 'better-comments' ),
        'quicktags' => __( 'Quicktags', 'better-comments' ),
        'plugins'   => __( 'Plugins', 'better-comments' ),
    );
}
add_filter( 'sky_bco_options_tabs', __NAMESPACE__ . '\\options_set_tabs' );

/**
 * Ajoute des champs dans l'onglet "Form".
 *
 * @return array $fields Le tableau contenant nos champs.
 *
 * @link https://carbonfields.net/docs/fields-usage/
 */
function options_form_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'form_h2' )
    ->set_html( sprintf( '<h2>%s</h2>', __( 'Comment form', 'better-comments' ) ) );

    // CUSTOMIZE FIELDS.

    $fields[] = Field::make( 'checkbox', 'bco_customize_fields', __( 'Customize fields', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_fields_radius', __( 'Border radius (px)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 0 )
        ->set_attribute( 'max', 50 )
        ->set_attribute( 'step', 0.1 )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_customize_fields', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_fields_background_color', __( 'Background color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#EDEFF1' )
        ->set_palette( array( '#EDEFF1' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_customize_fields', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_fields_border_color', __( 'Border color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#DDDDDD' )
        ->set_palette( array( '#DDDDDD' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_customize_fields', 'value' => true ) )
        );

    $fields[] = Field::make(
        'color',
        'bco_fields_text_color',
        __( 'Text color', 'better-comments' )
    )
        ->set_classes( 'indent' )
        ->set_default_value( '#1B1717' )
        ->set_palette( array( '#1B1717' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_customize_fields', 'value' => true ) )
        );

    // HIGHLIGHT FIELDS.

    $fields[] = Field::make( 'checkbox', 'bco_highlight_active_field', __( 'Highlight active field', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'color', 'bco_highlight_active_field_color', __( 'Border color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#51CBEE' )
        ->set_palette( array( '#51CBEE', '#0000FF' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_highlight_active_field', 'value' => true ) )
        );

    // DESIGN SUBMIT BUTTON.

    $fields[] = Field::make( 'checkbox', 'bco_design_submit_button', __( 'Submit button', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_submit_button_radius', __( 'Border radius (px)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 0 )
        ->set_attribute( 'max', 50 )
        ->set_attribute( 'step', 0.1 )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_submit_button_background_color', __( 'Background color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#CD2653' )
        ->set_palette( array( '#CD2653' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_submit_button_hover_color', __( 'Background color (hover)', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#B72D52' )
        ->set_palette( array( '#B72D52' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_submit_button_border_color', __( 'Border color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#FFFFFF' )
        ->set_palette( array( '#FFFFFF' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_submit_button_text_color', __( 'Text color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#FFFFFF' )
        ->set_palette( array( '#FFFFFF' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_submit_button_dropshadow_color', __( 'Dropshadow text color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#0B0000' )
        ->set_palette( array( '#0B0000' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_submit_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'checkbox', 'bco_define_title_reply', __( 'Set reply title', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_title_reply', __( 'Reply title', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_define_title_reply', 'value' => true ) )
        );

    $fields[] = Field::make( 'checkbox', 'bco_define_submit_label', __( 'Set submit button label', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_submit_label', __( 'Submit button label', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_define_submit_label', 'value' => true ) )
        );

    // FORM OPTIONS.

    $fields[] = Field::make( 'html', 'form_options_h2' )
    ->set_html( sprintf( '<h2>%s</h2>', __( 'Form options', 'better-comments' ) ) );

    $fields[] = Field::make(
        'checkbox',
        'bco_move_comment_field_to_bottom',
        __( 'Move comment field to the bottom of the form', 'better-comments' )
    )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_remove_url_field', __( 'Remove URL field', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_stack_fields', __( 'Stack all fields', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    // COMMENT POLICY.

    $fields[] = Field::make( 'checkbox', 'bco_add_comment_policy', __( 'Add comment policy', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_comment_policy_radius', __( 'Border radius (px)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 0 )
        ->set_attribute( 'max', 50 )
        ->set_attribute( 'step', 0.1 )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_comment_policy_background_color', __( 'Background color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#FFF4E5' )
        ->set_palette( array( '#FFF4E5' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_comment_policy_border_color', __( 'Border color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#FFD499' )
        ->set_palette( array( '#FFD499' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_comment_policy_text_color', __( 'Text color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#32373C' )
        ->set_palette( array( '#32373C' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    $fields[] = Field::make( 'select', 'bco_comment_policy_position', __( 'Position', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_options( array( 'before' => 'before comment box (logged-out users only)', 'after' => 'after comment box (all users)' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    $fields[] = Field::make( 'textarea', 'bco_comment_policy', __( 'Comment policy', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_add_comment_policy', 'value' => true ) )
        );

    return $fields;
}
add_filter( 'sky_bco_options_fields_tab_form', __NAMESPACE__ . '\\options_form_tab_theme_fields', 10 );

/**
 * Ajoute des champs dans l'onglet "Design".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_design_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'design_h2' )
    ->set_html( sprintf( '<h2>%s</h2>', __( 'Comments section', 'better-comments' ) ) );

    // Hide date metadata.

    $fields[] = Field::make( 'checkbox', 'bco_hide_date', __( 'Hide comment date', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    // DESIGN REPLY BUTTON.

    $fields[] = Field::make( 'checkbox', 'bco_design_reply_underline', __( 'Remove underline from reply link', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_design_reply_button', __( 'Design reply link as a button', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_reply_button_radius', __( 'Border radius (px)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 0 )
        ->set_attribute( 'max', 50 )
        ->set_attribute( 'step', 0.1 )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'text', 'bco_reply_button_padding', __( 'Padding (px)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 0 )
        ->set_attribute( 'max', 50 )
        ->set_attribute( 'step', 0.1 )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_reply_button_background_color', __( 'Background color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#CD2653' )
        ->set_palette( array( '#CD2653' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_reply_button_hover_color', __( 'Background color (hover)', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#9B51E0' )
        ->set_palette( array( '#9B51E0' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_reply_button_border_color', __( 'Border color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#F5EFE0' )
        ->set_palette( array( '#F5EFE0' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_reply_button_text_color', __( 'Text color', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_default_value( '#FFFFFF' )
        ->set_palette( array( '#FFFFFF' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_design_reply_button', 'value' => true ) )
        );

    $fields[] = Field::make( 'html', 'avatars_h2' )
    ->set_html( sprintf( '<h2>%s</h2>', __( 'Avatars', 'better-comments' ) ) );

    // Select avatars shape.

    $fields[] = Field::make( 'checkbox', 'bco_select_avatars_shape', __( 'Select avatars shape', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'select', 'bco_set_avatars_shape', __( 'Set avatars and gravatars shape', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_options(
            array(
                'default'  => '-- Default --',
                'circle'   => 'circle ',
                'triangle' => 'triangle',
                'rhombus'  => 'rhombus',
                'pentagon' => 'pentagon',
                'hexagon'  => 'hexagon',
                'heptagon' => 'heptagon',
                'octagon'  => 'octagon',
                'nonagon'  => 'nonagon',
                'decagon'  => 'decagon',
                'bevel'    => 'bevel',
                'rabbet'   => 'rabbet',
                'message'  => 'message',
                'star'     => 'star',
                'cross'    => 'cross',
                'close'    => 'close',
            )
        )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_select_avatars_shape', 'value' => true ) )
        );

    // set default avatar.

    $fields[] = Field::make( 'checkbox', 'bco_set_default_avatar', __( 'Set default avatar', 'better-comments' ) )
        ->set_classes( 'slider-checkbox' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'image', 'bco_default_avatar', __( 'Default avatar image' ) )
        ->set_classes( 'indent' )
        ->set_value_type( 'url' )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_set_default_avatar', 'value' => true ) )
        );

    return $fields;
}
    add_filter( 'sky_bco_options_fields_tab_design', __NAMESPACE__ . '\\options_design_tab_theme_fields', 10 );


/**
 * Ajoute des champs dans l'onglet "Tweaks".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_tweaks_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'admin_h2' )
        ->set_html( sprintf( '<h2>%s</h2>', __( 'Admin tweaks', 'better-comments' ) ) );

    // Comment on closed posts.

    $fields[] = Field::make( 'checkbox', 'bco_admin_can_always_comment', __( 'Allow admin to comment on closed to comments posts', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    // Add referrer info to notifications.

    $fields[] = Field::make( 'checkbox', 'bco_add_referrer_to_notifications', __( 'Add referrer URL to admin comments notifications emails', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    // Allow oEmbeds in comments.

    $fields[] = Field::make( 'checkbox', 'bco_allow_oembed_in_comments', __( 'Allow oEmbeds in comments', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    // ADMIN TWEAKS.

    $fields[] = Field::make( 'html', 'admin_tweaks' )
        ->set_html( sprintf( '<h2>%s</h2>', __( 'Comments tweaks', 'better-comments' ) ) );

    // Set minimum comment length.

    $fields[] = Field::make( 'checkbox', 'bco_set_comment_length', __( 'Set comment length', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    // min.
    $fields[] = Field::make( 'text', 'bco_minimum_comment_length', __( 'Minimum comment length (in characters)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 3 )
        ->set_required( true )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_set_comment_length', 'value' => true ) )
        );

    $fields[] = Field::make( 'text', 'bco_minimum_comment_length_error', __( 'Error message for short comments', 'better-comments' ) )
        ->set_classes( 'indent plus' )
        ->set_required( true )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_set_comment_length', 'value' => true ) )
        );

    // max.
    $fields[] = Field::make( 'text', 'bco_maximum_comment_length', __( 'Maximum comment length (in characters)', 'better-comments' ) )
        ->set_classes( 'indent inline-flex' )
        ->set_attribute( 'type', 'number' )
        ->set_attribute( 'min', 3 )
        ->set_required( true )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_set_comment_length', 'value' => true ) )
        );

    $fields[] = Field::make( 'text', 'bco_maximum_comment_length_error', __( 'Error message for long comments', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->set_required( true )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_set_comment_length', 'value' => true ) )
        );

    $fields[] = Field::make( 'checkbox', 'bco_discard_numeric_commenters', __( 'Discard comments posted by commenters using an all-numeric name', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_detect_imposters', __( 'Discard comments posted by commenters trying to impersonate a registered user', 'better-comments' ) )
        ->set_classes( 'slider-checkbox plus' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'text', 'bco_detect_imposters_error_title', __( 'Error title:', 'better-comments' ) )
        ->set_classes( 'indent show-field' )
        ->set_conditional_logic( array( array( 'field' => 'bco_detect_imposters', 'value' => true ) ) )
        ->set_required( true )
        ->set_help_text( __( 'Error title', 'better-comments' ) )
        ->set_default_value( 'Error: possible imposter detected' );

    $fields[] = Field::make( 'textarea', 'bco_detect_imposters_error_message', __( 'Message to display when an imposter is detected:', 'better-comments' ) )
        ->set_classes( 'indent show-field' )
        ->set_conditional_logic( array( array( 'field' => 'bco_detect_imposters', 'value' => true ) ) )
        ->set_required( true )
        ->set_help_text( __( 'Error explanation', 'better-comments' ) )
        ->set_default_value( 'You are attempting to post a comment with information (i.e. email address or login ID) belonging to a registered user. If you have an account on this site, please login to post your comment. Otherwise, please try again with different information.' );

    /*
    $fields[] = Field::make('html', 'admin_upsell')
        ->set_html('<div class="upselly"><div id="buy"><a class="button" href="'.BC_FS()->get_upgrade_url().'">'. __('Get these features with the premium version', 'better-comments') . '</a></div></div>');
    */

    // DISABLE TAXONOMIES.

    /*
    $args = [ 'public'   => true,
    // '_builtin' => false,
    ];
    $output = 'objects'; // or objects
    $operator = 'and'; // 'and' or 'or'
    $taxonomies = get_taxonomies( $args, $output, $operator );
    if ( $taxonomies ) {

    $fields[] = Field::make( 'html', 'bco_remove_comments_on_taxonomies' . $name )->set_html( '<h2>Disable comments on custom taxonomies</h2>' );

    foreach ( $taxonomies  as $taxonomy ) {
    $name = strtolower($taxonomy->labels->name);
    $capitalized_name = ucfirst( $name );
    //$fields[] = Field::make( 'html', 'bco_remove_comments_from_' . $name )->set_html( '<h2>'. $taxonomy->labels->name . '</h2>' );

    $fields[] = Field::make( 'checkbox', 'bco_disable_comments_on_' . $name, __( 'Disable comments on '. $capitalized_name, 'better-comments' ) )
    ->set_classes( 'slider-checkbox' )
    ->set_option_value( 'yes' )
    ;
    }
    }
    */
    return $fields;
}
add_filter(
    'sky_bco_options_fields_tab_tweaks',
    __NAMESPACE__ . '\\options_tweaks_tab_theme_fields',
    10
);



/**
 * Ajoute des champs dans l'onglet "Quicktags".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_quicktags_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'quicktags_frontend_h2' )
    ->set_html( sprintf( '<h2>%s</h2>', __( 'Comments quicktags on the frontend', 'better-comments' ) ) );

    $fields[] = Field::make( 'checkbox', 'bco_enable_comments_quicktags_frontend', __( 'Enable quicktags', 'better-comments' ) )
        ->set_classes( 'slider-checkbox business' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'color', 'bco_quicktags_button_text_color', __( 'Text color', 'better-comments' ) )
        ->set_classes( 'indent business' )
        ->set_default_value( '#FFFFFF' )
        ->set_palette( array( '#FFFFFF' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_enable_comments_quicktags_frontend', 'value' => true ) )
        );

    $fields[] = Field::make( 'color', 'bco_quicktags_button_background_color', __( 'Background color', 'better-comments' ) )
        ->set_classes( 'indent business' )
        ->set_default_value( '#CD2653' )
        ->set_palette( array( '#CD2653', '#007CBA', '#32373c', '#0693E3' ) )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_enable_comments_quicktags_frontend', 'value' => true ) )
        );

    $fields[] = Field::make( 'text', 'bco_quicktags_button_padding', __( 'Padding', 'better-comments' ) )
    ->set_classes( 'indent inline-flex business' );

    $fields[] = Field::make( 'multiselect', 'bco_use_builtin_quicktags_frontend', __( 'Select built-in quicktags', 'better-comments' ) )
        ->set_classes( 'indent business' )
        ->add_options(
            array(
                // strong,em,link,block,del,ins,img,ul,ol,li,code,more,close.
                'strong' => 'bold',
                'em'     => 'italics',
                'link'   => 'link',
                'block'  => 'blockquote',
                'del'    => 'del',
                'ins'    => 'ins',
                'img'    => 'image',
                'ul'     => 'ul',
                'ol'     => 'ol',
                'li'     => 'li',
                'code'   => 'code',
                'more'   => 'more',
                'close'  => 'close',
            )
        );

    // CUSTOM FRONTEND QUICKTAGS.

    $fields[] = Field::make( 'checkbox', 'bco_define_comments_quicktags_frontend', __( 'Define custom quicktags', 'better-comments' ) )
        ->set_classes( 'slider-checkbox business' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'complex', 'bco_frontend_quicktags_enable_custom_tags', __( 'Custom quicktags', 'better-comments' ) )
        ->add_fields(
            array(
                Field::make( 'text', 'bco_tag_name', __( 'Tag name', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'Tag name', 'better-comments' ) ),

                Field::make( 'text', 'bco_tag_start', __( 'Tag code starts with:', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'Start tag', 'better-comments' ) ),

                Field::make( 'text', 'bco_tag_end', __( 'Tag code ends with:', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'End tag', 'better-comments' ) ),

                Field::make( 'checkbox', 'bco_tag_enable', __( 'Enable', 'better-comments' ) )
                    ->set_classes( 'slider-checkbox plus' )
                    ->set_option_value( 'yes' ),
            )
        )
        ->set_layout( 'tabbed-horizontal' )
        ->set_header_template(
            '<% if (bco_tag_name)  { %>
        <%- bco_tag_name %>
        <% } else { %>
            empty
            <% } %>'
        )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_define_comments_quicktags_frontend', 'value' => true ) )
        );

    // BACKEND.

    $fields[] = Field::make( 'html', 'quicktags_backend_h2' )
        ->set_html( sprintf( '<h2>%s</h2>', __( 'Comments quicktags on the backend', 'better-comments' ) ) );

    $fields[] = Field::make( 'checkbox', 'bco_enable_comments_quicktags_backend', __( 'Enable quicktags', 'better-comments' ) )
        ->set_classes( 'slider-checkbox business' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'multiselect', 'bco_use_builtin_quicktags_backend', __( 'Select built-in quicktags', 'better-comments' ) )
        ->set_classes( 'indent business' )
        ->add_options(
            array(
                // strong,em,link,block,del,ins,img,ul,ol,li,code,more,close.
                'strong' => 'bold',
                'em'     => 'italics',
                'link'   => 'link',
                'block'  => 'blockquote',
                'del'    => 'del',
                'ins'    => 'ins',
                'img'    => 'image',
                'ul'     => 'ul',
                'ol'     => 'ol',
                'li'     => 'li',
                'code'   => 'code',
                'more'   => 'more',
                'close'  => 'close',
            )
        );

    // CUSTOM BACKEND QUICKTAGS.

    $fields[] = Field::make( 'checkbox', 'bco_define_comments_quicktags_backend', __( 'Define custom quicktags', 'better-comments' ) )
        ->set_classes( 'slider-checkbox business' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'complex', 'bco_backend_quicktags_enable_custom_tags', __( 'Custom quicktags', 'better-comments' ) )
        ->add_fields(
            array(
                Field::make( 'text', 'bco_tag_name', __( 'Tag name', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'Tag name.', 'better-comments' ) ),

                Field::make( 'text', 'bco_tag_start', __( 'Tag code starts with:', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'Start tag', 'better-comments' ) ),

                Field::make( 'text', 'bco_tag_end', __( 'Tag code ends with:', 'better-comments' ) )
                    ->set_required( true )
                    ->set_help_text( __( 'End tag', 'better-comments' ) ),

                Field::make( 'checkbox', 'bco_tag_enable', __( 'Enable', 'better-comments' ) )
                    ->set_classes( 'slider-checkbox plus' )
                    ->set_option_value( 'yes' ),
            )
        )
        ->set_layout( 'tabbed-horizontal' )
        ->set_header_template( '<% if (bco_tag_name) { %>    <%- bco_tag_name %> <% } else { %> empty <% } %>' )
        ->set_conditional_logic(
            array( array( 'field' => 'bco_define_comments_quicktags_backend', 'value' => true ) )
        );

    /*
    $fields[] = Field::make('html', 'quicktags_upsell')
        ->set_html('<div class="upselly"><div id="buy"><a class="button" href="'.BC_FS()->get_upgrade_url().'">'. __('Get these features with the premium version', 'better-comments') .'</a></div></div>');
    */
    return $fields;
}
    add_filter( 'sky_bco_options_fields_tab_quicktags', __NAMESPACE__ . '\\options_quicktags_tab_theme_fields', 10 );

/**
 * Ajoute des champs dans l'onglet "SEO".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_seo_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'seo_redirects_h2' )
        ->set_html( sprintf( '<h2>%s</h2>', __( 'SEO comment redirects', 'better-comments' ) ) );

    $fields[] = Field::make(
        'checkbox',
        'bco_redirect_all_comments',
        __( 'Redirect `/all-comments/` requests to original post', 'better-comments' )
    )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_redirect_comment_page_1', __( 'Redirect `/comment-page-1/` requests to original post', 'better-comments' ) )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_replytocom_replace', __( 'Replace and redirect `?replytocom` fragments with comment ID anchors for Reply links', 'better-comments' ) )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'html', 'seo_links_h2' )
        ->set_html( sprintf( '<h2>%s</h2>', __( 'SEO comment links', 'better-comments' ) ) );

    $fields[] = Field::make( 'checkbox', 'bco_gravatar_alt', __( 'Add missing ALT text for gravatar images', 'better-comments' ) )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_dofollow_internal_url_field', __( 'Dofollow post author\'s URL', 'better-comments' ) )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    $fields[] = Field::make( 'checkbox', 'bco_dofollow_internal_links', __( 'Dofollow internal links', 'better-comments' ) )
        ->set_classes( 'slider-checkbox pro' )
        ->set_option_value( 'yes' );

    /*
    $fields[] = Field::make('html', 'seo_upsell')
        ->set_html('<div class="upselly pro"><div id="buy"><a class="button" href="'.BC_FS()->get_upgrade_url().'">'. __('Get these features with the premium version', 'better-comments') .'</a></div></div>');
    */
    return $fields;
}
    add_filter( 'sky_bco_options_fields_tab_seo', __NAMESPACE__ . '\\options_seo_tab_theme_fields', 10 );


/**
 * Ajoute des champs dans l'onglet "Plugins".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_plugins_tab_theme_fields() {
    $fields = array();

    $fields[] = Field::make( 'html', 'plugins_h2' )
        ->set_html(
            sprintf(
                '<h2>%s</h2><div id="plugins-grid"></div>',
                __( 'Our plugins', 'better-comments' )
            )
        );

    return $fields;
}
    add_filter( 'sky_bco_options_fields_tab_plugins', __NAMESPACE__ . '\\options_plugins_tab_theme_fields', 10 );


/**
 * Ajoute des champs dans l'onglet "Disable".
 *
 * @return array $fields Le tableau contenant nos champs.
 * @link   https://carbonfields.net/docs/fields-usage/
 */
function options_disable_tab_theme_fields() {
    $fields = array();

    // DISABLE COMMENTS ON CPT.
    $args           = array( 'public' => true   /* '_builtin' => false, */ );
    $output         = 'objects'; // 'names' or 'objects' (default: 'names').
        $operator   = 'and'; // 'and' or 'or' (default: 'and').
        $post_types = get_post_types( $args, $output, $operator );

    if ( $post_types ) { // If there are any custom public post types.

        $fields[] = Field::make( 'html', 'bco_remove_comments_on_cpt' )
            ->set_html( sprintf( '<h2>%s</h2>', __( 'Disable comments on custom post types', 'better-comments' ) ) );

        foreach ( $post_types  as $post_type ) {
            $name             = strtolower( $post_type->name );
            $capitalized_name = ucfirst( $name );
            $fields[]         = Field::make(
                'checkbox',
                'bco_disable_comments_on_' . $name,
                // translators: capitalized name.
                sprintf( __( 'Disable comments on %s', 'better-comments' ), $capitalized_name . 's' )
            )
                ->set_classes( 'slider-checkbox pro' )
                ->set_option_value( 'yes' );
            $hello[ $name ] = $name;
        }

        /*
        $fields[] = Field::make( 'multiselect', 'bco_disable_comments', __( 'Disable comments on:', 'better-comments' ) )
        ->set_classes( 'indent' )
        ->add_options( $hello );
        */

        $fields[] = Field::make( 'html', 'bco_hide_comments_on_cpt' )
            ->set_html( sprintf( '<h2>%s</h2>', __( 'Hide comments on custom post types', 'better-comments' ) ) );

        /*
        $fields[] = Field::make( 'multiselect', 'bco_hide_comments', __( 'Hide existing comments on:', 'better-comments' ) )
        ->set_classes( 'indent0 business' )
        ->add_options( $hello );
        */

        foreach ( $post_types  as $post_type ) {
            $name             = strtolower( $post_type->name );
            $capitalized_name = ucfirst( $name );
            $fields[]         = Field::make(
                'checkbox',
                'bco_hide_comments_on_' . $name,
                // translators: capitalized name.
                sprintf( __( 'Hide comments on %s', 'better-comments' ), $capitalized_name . 's' )
            )
                ->set_classes( 'slider-checkbox pro' )
                ->set_option_value( 'yes' );
            $hello[ $name ] = $name;
        }

        $fields[] = Field::make( 'html', 'bco_misc_cpt' )
            ->set_html( sprintf( '<h2>%s</h2>', __( 'Misc', 'better-comments' ) ) );

        $fields[] = Field::make( 'checkbox', 'bco_remove_comments_are_closed', __( 'Remove "comments are closed" message', 'better-comments' ) )
            ->set_classes( 'slider-checkbox pro' )
            ->set_option_value( 'yes' );

        $fields[] = Field::make( 'checkbox', 'bco_remove_comments_cookies', __( 'Disable comments cookies', 'better-comments' ) )
            ->set_classes( 'slider-checkbox pro' )
            ->set_option_value( 'yes' );

        $fields[] = Field::make( 'checkbox', 'bco_disable_trackbacks', __( 'Disable trackbacks globally', 'better-comments' ) )
            ->set_classes( 'slider-checkbox pro' )
            ->set_option_value( 'yes' );
    }

    /*
    $fields[] = Field::make('html', 'disable_upsell')
        ->set_html('<div class="upselly pro"><div id="buy"><a class="button" href="'.BC_FS()->get_upgrade_url().'">' . __('Get these features with the premium version', 'better-comments') . '</a></div></div>');
    */

    return $fields;
}
add_filter(
    'sky_bco_options_fields_tab_disable',
    __NAMESPACE__ . '\\options_disable_tab_theme_fields',
    10
);



// Add custom avatar upload to profile page.
add_action(
    'carbon_fields_register_fields',
    function () {
        Container::make( 'user_meta', 'Profile picture' )
            ->add_fields(
                array(
                    Field::make( 'image', 'bco_user_custom_avatar', 'Your avatar' )
                    ->set_value_type( 'url' ),
                )
            );
    }
);

add_filter(
    'user_profile_picture_description',
    function () {
        return '<a href="#application-passwords-section">Upload or modify your avatar</a>';
    }
);

/**
 * Upsell features
 *
 * @return mixed
 */
function upsell_features() {
    /* platinum users: bail early. */
    if ( BC_FS()->is_plan( 'business' ) ) {
        return;
    }

    /* define all plans. */
    $features = array();

    $plus = array(
        esc_html__( 'Admin and user tweaks', 'better-comments' ),
    );

    $pro = array(
        esc_html__( 'Stop SEO errors with smart redirects', 'better-comments' ),
        esc_html__( 'Disable or remove comments on your custom post types', 'better-comments' ),
    );

    $business = array(
        esc_html__( 'Comments quicktags', 'better-comments' ),
    );

    /* Free plan or non-paying user. */
    if ( BC_FS()->is_not_paying() || BC_FS()->is_free_plan() ) {
        $features = array_merge( $features, $plus, $pro, $business );
    }

    /* Business upsell. */
    if ( BC_FS()->is_plan( 'plus', true ) ) {
        $features = array_merge( $features, $pro, $business );
    }

    /* Pro upsell. */
    if ( BC_FS()->is_plan( 'pro', true ) ) {
        $features = array_merge( $features, $business );
    }

    /* common features for all plans. */
    $all_plans = array(
        esc_html__( 'Priority premium support', 'better-comments' ),
    );
    $features  = array_merge( $features, $all_plans ); ?>
    <div id="promo-box" class="wp-core-ui"><h2>🚀</h2>
    <div id="buy"><a class="button" href="<?php echo esc_url( BC_FS()->get_upgrade_url() ); ?>">
    <span class="trolley">🛒  </span> <?php esc_html_e( 'Go Pro', 'better-comments' ); ?>
    </a></div>
    <p><strong><?php esc_html_e( 'And get access to:', 'better-comments' ); ?></strong></p>
    <ul>
    <?php
    foreach ( $features as $f ) {
		// phpcs:ignore.
        printf( '<li>✔ %s</li>', $f ); // Escaped earlier when added to the array.
    }
    ?>
    </ul>
    </div>
    <?php
}
add_action(
    'carbon_fields_container_better_comments_after_sidebar',
    __NAMESPACE__ . '\\upsell_features'
);
