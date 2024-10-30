<?php

/**
 * Plugin Name: Better Comments
 * Plugin URI: https://utopique.net/products/better-comments/
 * Description: Easily style your comments section and comment form in a few clicks.
 * Version: 1.6.0
 * Author: Utopique
 * Author URI: https://utopique.net/
 * Copyright: 2020-2024 Utopique Plugins
 * Text Domain: better-comments
 * Domain Path: /languages
 * License: GPLv3 or later
 * Requires at least: 4.7
 * Tested up to: 6.5
 * Requires PHP: 8
 * WC requires at least: 3.3
 * WC tested up to: 8.7.0
 * PHP version 7
 *
 * @category        Better_Comments
 * @package         Better_Comments
 * @author          Utopique <support@utopique.net>
 * @license         GPL https://utopique.net
 * @link            https://utopique.net
 */
namespace BetterComments\Plugin;

defined( 'ABSPATH' ) || exit;
// Exit if accessed directly.
// Current version.
define( 'BETTER_COMMENTS_VERSION', '1.6.0' );
// Plugin root path.
define( 'BETTER_COMMENTS_ROOT', trailingslashit( plugin_dir_path( __FILE__ ) ) );
// Define the plugin slug as a constant.
if ( !defined( 'BETTER_COMMENTS_SLUG' ) ) {
    define( 'BETTER_COMMENTS', plugin_basename( __FILE__ ) );
}
/**
 * FS
 */

if ( function_exists( __NAMESPACE__ . '\\sky_bc_fs' ) ) {
    sky_bc_fs()->set_basename( false, __FILE__ );
} else {
    // FS.
    
    if ( !function_exists( __NAMESPACE__ . '\\sky_bc_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         *
         * @return $sky_bc_fs
         */
        function sky_bc_fs()
        {
            global  $sky_bc_fs ;
            
            if ( !isset( $sky_bc_fs ) ) {
                // Include Freemius SDK.
                include_once BETTER_COMMENTS_ROOT . '/vendor/freemius/wordpress-sdk/start.php';
                $sky_bc_fs = fs_dynamic_init( array(
                    'id'             => '6952',
                    'slug'           => 'better-comments',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_d99db62781b535ca9eecaebc00228',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'    => 'better-comments',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $sky_bc_fs;
        }
        
        // Init Freemius.
        sky_bc_fs();
        // Signal that SDK was initiated.
        do_action( 'sky_bc_fs_loaded' );
    }
    
    /**
     * Localization
     *
     * @return void
     */
    function load_textdomain()
    {
        load_plugin_textdomain( 'better-comments', false, basename( __DIR__ ) . '/languages/' );
    }
    
    add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );
    /**
     * Charge notre dépendance Carbon Fields via Composer
     *
     * @return void
     */
    function load_carbonfields()
    {
        include_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
        \Carbon_Fields\Carbon_Fields::boot();
        /**
         * Remove sidebar creation
         */
        // immediately after calling \Carbon_Fields\Carbon_Fields::boot().
        $sidebar_manager = \Carbon_Fields\Carbon_Fields::resolve( 'sidebar_manager' );
        remove_action( 'admin_enqueue_scripts', array( $sidebar_manager, 'enqueue_scripts' ) );
    }
    
    add_action( 'after_setup_theme', __NAMESPACE__ . '\\load_carbonfields' );
    /**
     * Charge notre fichier de plugin
     *
     * @return mixed
     */
    function load_plugin()
    {
        include_once plugin_dir_path( __FILE__ ) . '/includes/options.php';
    }
    
    add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
    /**
     * Add settings link to plugins page
     *
     * @return mixed $links modified links.
     */
    function plugin_action_links()
    {
        add_filter(
            'plugin_action_links_' . plugin_basename( __FILE__ ),
            function ( $links ) {
            $settings_link = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=better-comments' ) ), __( 'Settings', 'better-comments' ) );
            array_unshift( $links, $settings_link );
            // or array_push.
            return $links;
        },
            10,
            1
        );
    }
    
    // Hook this function to an appropriate action, like 'admin_init' or 'plugins_loaded'.
    add_action( 'plugins_loaded', __NAMESPACE__ . '\\plugin_action_links' );
    /**
     * Add additional useful links to plugins page
     *
     * @param mixed $links existing links.
     * @param mixed $file  the current plugin file.
     *
     * @return mixed $links modified links
     */
    function meta_row( $links, $file )
    {
        
        if ( plugin_basename( __FILE__ ) === $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( apply_filters( 'better_comments_docs_url', 'https://utopique.net/docs/' ) ) . '" title="' . esc_attr( __( 'View Documentation', 'better-comments' ) ) . '">' . __( 'Docs', 'better-comments' ) . '</a>',
                'support' => '<a href="' . esc_url( apply_filters( 'better_comments_support_url', 'https://wordpress.org/support/plugin/better-comments/' ) ) . '" title="' . esc_attr( __( 'Visit support forum', 'better-comments' ) ) . '">' . __( 'Support forum', 'better-comments' ) . '</a>',
                'rate'    => '<a href="' . esc_url( apply_filters( 'better_comments_rate', 'https://wordpress.org/support/plugin/better-comments/reviews/?rate=5#new-post' ) ) . '" target="_blank" title="' . esc_attr( __( 'Rate Better Comments', 'better-comments' ) ) . '">' . __( 'Rate the plugin', 'better-comments' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        
        return (array) $links;
    }
    
    add_filter(
        'plugin_row_meta',
        __NAMESPACE__ . '\\meta_row',
        10,
        2
    );
    /**
     * Admin credits.
     *
     * @param string $footer_text footer text.
     *
     * @return string $footer_text footer text.
     */
    function admin_credits( $footer_text )
    {
        $current_screen = get_current_screen();
        $hook = $current_screen->id;
        $array = array( 'toplevel_page_better-comments', 'better-comments_page_better-comments-account', 'better-comments_page_better-comments-contact' );
        if ( !in_array( $hook, $array, true ) ) {
            return $footer_text;
        }
        $footer_text = sprintf( wp_kses(
            // translators: %s is the URL to the "Better Comments" product page.
            __( 'Thank you for using <a href="%s" target="_blank">Better Comments</a>', 'better-comments' ),
            array(
                'a' => array(
                'href'   => array(),
                'target' => array(),
            ),
            )
        ), esc_url( 'https://utopique.net/products/better-comments/' ) );
        // Combine the link text and 'the plugin' into a single translatable format with placeholders.
        $footer_text .= sprintf(
            wp_kses(
                // translators: %1$s: opening anchor tag with URL, %2$s: closing anchor tag.
                __( '%1$sRate%2$s the plugin', 'better-comments' ),
                array(
                    'a' => array(
                    'href'   => array(),
                    'target' => array(),
                ),
                )
            ),
            '<a href="' . esc_url( 'https://wordpress.org/support/plugin/better-comments/reviews/?rate=5#new-post' ) . '" target="_blank">',
            // %1$s
            '</a>'
        );
        return $footer_text;
    }
    
    add_filter( 'admin_footer_text', __NAMESPACE__ . '\\admin_credits', 20 );
    /**
     * Remove URL field from comment form
     *
     * @param array $fields fields.
     *
     * @return array
     */
    function remove_url_field( $fields )
    {
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_remove_url_field' ) ) ) {
            if ( isset( $fields['url'] ) ) {
                unset( $fields['url'] );
            }
        }
        return $fields;
    }
    
    add_filter( 'comment_form_default_fields', __NAMESPACE__ . '\\remove_url_field' );
    /**
     * Get theme handle
     *
     * @return $handle
     */
    function get_theme_handle()
    {
        global  $wp_styles ;
        $stylesheet_uri = get_stylesheet_uri();
        // Common handles used by themes.
        $common_handles = array( 'theme-styles', 'main-style', 'style' );
        // Add more as needed.
        foreach ( $common_handles as $handle ) {
            if ( isset( $wp_styles->registered[$handle] ) && strpos( $wp_styles->registered[$handle]->src, $stylesheet_uri ) !== false ) {
                return $handle;
            }
        }
        // Fallback: Search all registered styles.
        foreach ( $wp_styles->registered as $handle => $style_obj ) {
            if ( strpos( $style_obj->src, $stylesheet_uri ) !== false ) {
                return $handle;
            }
        }
        return null;
    }
    
    /**
     * Sanitize hex colours
     *
     * @param string $color colour.
     *
     * @return $color|false
     */
    function sanitize_hex_color( $color )
    {
        if ( preg_match( '/^#([a-fA-F0-9]{3}){1,2}$/', $color ) ) {
            return $color;
        }
        return false;
        // false if no colour is defined.
    }
    
    // Comment Form CSS.
    add_action( 'wp_head', function () {
        // bail early if we're not on a singular page.
        if ( !is_singular() ) {
            return;
        }
        $css = '';
        // if comments are open, we can style.
        // * fields.
        // * reply button.
        // * comment policy.
        // * submit button.
        
        if ( comments_open() ) {
            // fields.
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_customize_fields' ) ) ) {
                $f_radius = (int) carbonade( 'bco_fields_radius' );
                $f_bg = sanitize_hex_color( carbonade( 'bco_fields_background_color' ) );
                $f_border = sanitize_hex_color( carbonade( 'bco_fields_border_color' ) );
                $f_text = sanitize_hex_color( carbonade( 'bco_fields_text_color' ) );
                $f_radius = ( $f_radius ? 'border-radius:' . $f_radius . 'px;' : '' );
                $f_bg = ( $f_bg ? 'background-color:' . $f_bg . ';' : 'background-color: revert;' );
                $f_border = ( $f_border ? 'border-color:' . $f_border . ';' : 'border-color: none;' );
                $f_text = ( $f_text ? 'color:' . $f_text . ';' : 'color: revert;' );
                $css .= '#respond input[type="text"], #respond input[type="email"], #respond input[type="url"], #respond textarea {' . $f_radius . $f_bg . $f_border . $f_text . '}';
            }
            
            // highlight active field.
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_highlight_active_field' ) ) ) {
                $highlight = ( carbonade( 'bco_highlight_active_field_color' ) ? sanitize_hex_color( carbonade( 'bco_highlight_active_field_color' ) ) : '#51cbee' );
                $css .= '#respond input[type=text], #respond input[type=email], #respond input[type=url], #respond textarea {
		  transition: all 0.30s ease-in-out;
		  outline: none;
		  border: 1px solid #DDDDDD;
		}
		#respond input[type=text]:focus, #respond input[type=email]:focus, #respond input[type=url]:focus, #respond textarea:focus {
		  box-shadow: 0 0 5px ' . $highlight . ';
		  border: 2px solid ' . $highlight . ';
		}';
            }
            
            // comment policy.
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_add_comment_policy' ) ) ) {
                $policy_text_color = sanitize_hex_color( carbonade( 'bco_comment_policy_text_color' ) );
                $policy_background_color = ( carbonade( 'bco_comment_policy_background_color' ) ? sanitize_hex_color( carbonade( 'bco_comment_policy_background_color' ) ) : '#fff4e5' );
                $policy_border_color = ( carbonade( 'bco_comment_policy_border_color' ) ? sanitize_hex_color( carbonade( 'bco_comment_policy_border_color' ) ) : '#ffd499' );
                $policy_radius = ( carbonade( 'bco_comment_policy_radius' ) ? (int) carbonade( 'bco_comment_policy_radius' ) : 0 );
                $css .= 'p.comment-policy {
		  color: ' . $policy_text_color . ';
		  border: 1px solid ' . $policy_border_color . ';
		  background-color: ' . $policy_background_color . ';
		  border-radius: ' . $policy_radius . 'px;
		  padding: 1.2rem;
		  margin: 2rem 0;
		  font-size: small;
		  font-style: italic;
		}';
            }
            
            // Stack fields.
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_stack_fields' ) ) ) {
                $css .= 'div.comment-respond .comment-form-author, div.comment-respond .comment-form-email { 
					  float: none; width: 100%; margin-left: 0; 
					}';
            }
            // design submit button.
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_design_submit_button' ) ) ) {
                $sb_radius = (int) carbonade( 'bco_submit_button_radius' );
                $sb_background_color = sanitize_hex_color( carbonade( 'bco_submit_button_background_color' ) );
                $sb_border_color = sanitize_hex_color( carbonade( 'bco_submit_button_border_color' ) );
                $sb_text_color = sanitize_hex_color( carbonade( 'bco_submit_button_text_color' ) );
                $sb_hover_color = sanitize_hex_color( carbonade( 'bco_submit_button_hover_color' ) );
                $sb_dropshadow_color = sanitize_hex_color( carbonade( 'bco_submit_button_dropshadow_color' ) );
                $sb_radius = ( $sb_radius ? 'border-radius:' . $sb_radius . 'px;' : '' );
                $sb_background_color = ( $sb_background_color ? 'background-color:' . $sb_background_color . ';' : '' );
                $sb_border_color = ( $sb_border_color ? 'border:1px solid ' . $sb_border_color . ';' : '' );
                $sb_text_color = ( $sb_text_color ? 'color:' . $sb_text_color . ';' : '' );
                $sb_hover_color = ( $sb_hover_color ? 'background-color:' . $sb_hover_color . ';' : '' );
                $sb_dropshadow_color = ( $sb_dropshadow_color ? 'text-shadow:0px 1px 0px ' . $sb_dropshadow_color . ';' : '' );
                $css .= "#submit { {$sb_background_color} {$sb_radius} {$sb_border_color} {$sb_text_color} padding:16px 31px; text-decoration:none; {$sb_dropshadow_color} }\n\t\t#submit:hover { {$sb_hover_color} }\n\t\t#submit:active { position:relative; top:1px; }";
            }
            
            // Reply button.
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_design_reply_button' ) ) ) {
                $rb_radius = (int) carbonade( 'bco_reply_button_radius' );
                $rb_padding = (int) carbonade( 'bco_reply_button_padding' );
                $rb_background_color = sanitize_hex_color( carbonade( 'bco_reply_button_background_color' ) );
                $rb_border_color = sanitize_hex_color( carbonade( 'bco_reply_button_border_color' ) );
                $rb_text_color = sanitize_hex_color( carbonade( 'bco_reply_button_text_color' ) );
                $rb_hover_color = sanitize_hex_color( carbonade( 'bco_reply_button_hover_color' ) );
                $rb_underline = sanitize_text_field( carbonade( 'bco_design_reply_underline' ) );
                $rb_radius = ( $rb_radius ? 'border-radius:' . $rb_radius . 'px;' : '' );
                $rb_padding = ( $rb_padding ? 'padding:' . $rb_padding . 'px;' : '' );
                $rb_background_color = ( $rb_background_color ? 'background-color:' . $rb_background_color . ';' : '' );
                $rb_border_color = ( $rb_border_color ? 'border:1px solid ' . $rb_border_color . ';' : '' );
                $rb_text_color = ( $rb_text_color ? 'color:' . $rb_text_color . ';' : '' );
                $rb_hover_color = ( $rb_hover_color ? 'background-color:' . $rb_hover_color . ';' : '' );
                $rb_underline = ( $rb_underline ? 'text-decoration:none' : '' );
                $css .= "a.comment-reply-link { \n\t\t\t\t\t  {$rb_background_color} {$rb_padding} {$rb_radius} \n\t\t\t\t\t  {$rb_border_color} {$rb_text_color} {$rb_underline} }\n\t\ta.comment-reply-link:hover { {$rb_hover_color} }\n\t\ta.comment-reply-link:active { position:relative; top:1px; }";
            }
        
        }
        
        // is_open.
        // Shape of avatars.
        
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_select_avatars_shape' ) ) ) {
            $shape = sanitize_text_field( carbonade( 'bco_set_avatars_shape' ) );
            $polygons = array(
                'default'  => '',
                'circle'   => 'clip-path: circle(50% at 50% 50%);',
                'triangle' => 'clip-path: polygon(50% 0%, 0% 100%, 100% 100%);',
                'rhombus'  => 'clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);',
                'pentagon' => 'clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);',
                'hexagon'  => 'clip-path: polygon(50% 0,95% 25%,95% 75%,50% 100%,5% 75%,5% 25%);',
                'heptagon' => 'clip-path: polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%);',
                'octagon'  => 'clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);',
                'nonagon'  => 'clip-path: polygon(50% 0%, 83% 12%, 100% 43%, 94% 78%, 68% 100%, 32% 100%, 6% 78%, 0% 43%, 17% 12%);',
                'decagon'  => 'clip-path: polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%);',
                'bevel'    => 'clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);',
                'rabbet'   => 'clip-path: polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%);',
                'message'  => 'clip-path: polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 75% 100%, 50% 75%, 0% 75%);',
                'star'     => 'clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);',
                'cross'    => 'clip-path: polygon(10% 25%, 35% 25%, 35% 0%, 65% 0%, 65% 25%, 90% 25%, 90% 50%, 65% 50%, 65% 100%, 35% 100%, 35% 50%, 10% 50%);',
                'close'    => 'clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%);',
            );
            
            if ( !empty($polygons[$shape]) ) {
                $polygons[$shape] = str_replace( ';', ' !important;', $polygons[$shape] );
                $css .= "#comments img.avatar{{$polygons[$shape]} border-radius: 0 !important}";
            }
        
        }
        
        // Hide date.
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_hide_date' ) ) ) {
            $css .= '.comment-meta { margin-bottom: 0; } .comment-metadata { display: none; margin: 0; }';
        }
        // output.
        if ( $css ) {
            echo  "<style>{$css}</style>" ;
        }
    } );
    /**
     * Comment form defaults
     *
     * @param array $arg Form arguments.
     *
     * @return array Modified form arguments.
     */
    function comment_form_defaults( $arg )
    {
        // Title_reply.
        $title = sanitize_text_field( carbonade( 'bco_title_reply' ) );
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_define_title_reply' ) ) && !empty($title) ) {
            $arg['title_reply'] = esc_html( $title );
        }
        // Submit label.
        $submit = sanitize_text_field( carbonade( 'bco_submit_label' ) );
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_define_submit_label' ) ) && !empty($submit) ) {
            $arg['label_submit'] = esc_html__( $submit, 'better-comments' );
        }
        // Comment policy + position.
        $policy = wp_kses_post( carbonade( 'bco_comment_policy' ) );
        // Allows basic HTML tags for formatting.
        
        if ( 'yes' === sanitize_text_field( carbonade( 'bco_add_comment_policy' ) ) && !empty($policy) ) {
            $position = sanitize_key( carbonade( 'bco_comment_policy_position' ) );
            // 'before' or 'after' are expected values.
            if ( in_array( $position, array( 'before', 'after' ), true ) ) {
                $arg["comment_notes_{$position}"] = "<p class='comment-policy'>{$policy}</p>";
            }
        }
        
        return $arg;
    }
    
    add_filter( 'comment_form_defaults', __NAMESPACE__ . '\\comment_form_defaults' );
    /**
     * Move comment field to bottom, after all the text fields.
     *
     * @param array $fields The comment form fields.
     * @return array The reordered comment form fields.
     */
    function move_comment_field_to_bottom( $fields )
    {
        // Fetch the setting and sanitize its value.
        $move_comment_field = sanitize_text_field( carbonade( 'bco_move_comment_field_to_bottom' ) );
        // Proceed if the setting explicitly allows moving the comment field.
        if ( 'yes' === $move_comment_field ) {
            // Ensure the 'comment' field exists to avoid PHP notices.
            
            if ( isset( $fields['comment'] ) ) {
                $comment_field = $fields['comment'];
                unset( $fields['comment'] );
                // Re-add the 'comment' field to ensure it's positioned at the end.
                $fields['comment'] = $comment_field;
            }
        
        }
        return $fields;
    }
    
    add_filter( 'comment_form_fields', __NAMESPACE__ . '\\move_comment_field_to_bottom' );
    /**
     * Make our new gravatar available
     *
     * @param array $avatar_defaults The default avatars.
     * @return array The modified default avatars.
     */
    function new_avatar( $avatar_defaults )
    {
        // Check if the current user has the 'manage_options' capability.
        if ( current_user_can( 'manage_options' ) ) {
            
            if ( 'yes' === sanitize_text_field( carbonade( 'bco_set_default_avatar' ) ) ) {
                // Retrieve the custom avatar URL using carbonade.
                // Assume carbonade returns a sanitized URL. If not, esc_url_raw should be used for sanitization.
                $custom_avatar = carbonade( 'bco_default_avatar' );
                
                if ( !empty($custom_avatar) ) {
                    // Ensure the avatar URL is safe for use in an attribute.
                    $safe_avatar_url = esc_url( $custom_avatar );
                    // Add the custom avatar to the list of defaults.
                    $avatar_defaults[$safe_avatar_url] = __( 'Better Comments', 'better-comments' );
                    // Set our custom avatar as the default in Settings > Discussion.
                    update_option( 'avatar_default', $safe_avatar_url );
                    // Set the new avatar to be the default.
                } else {
                    // If the image has been removed, revert to mystery man.
                    update_option( 'avatar_default', 'mystery' );
                }
            
            } else {
                // If the option is disabled, revert to mystery man.
                update_option( 'avatar_default', 'mystery' );
            }
        
        }
        return $avatar_defaults;
    }
    
    add_filter( 'avatar_defaults', __NAMESPACE__ . '\\new_avatar' );
    /**
     * Display custom avatar if provided
     *
     * @param mixed  $avatar         The avatar.
     * @param int    $id_or_email    The id_or_email.
     * @param int    $size           The size.
     * @param array  $avatar_default The default avatar.
     * @param string $alt            The avatar alt.
     *
     * @return mixed $avatar
     */
    function gravatar_filter(
        $avatar,
        $id_or_email,
        $size,
        $avatar_default,
        $alt
    )
    {
        $user = '';
        // Get user by id or email.
        
        if ( is_numeric( $id_or_email ) ) {
            $id = (int) $id_or_email;
            $user = get_user_by( 'id', $id );
        } elseif ( is_object( $id_or_email ) ) {
            
            if ( !empty($id_or_email->user_id) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id', $id );
            }
        
        } else {
            $user = get_user_by( 'email', $id_or_email );
        }
        
        // user does not exist on the site.
        if ( !$user ) {
            // our custom avatar is set so let's display it.
            
            if ( carbonade( 'bco_default_avatar' ) ) {
                // backend.
                // Properly show Avatars and Gravatars on the dashboard and edit-comments pages.
                
                if ( is_admin() ) {
                    $screen = get_current_screen();
                    
                    if ( is_object( $screen ) && in_array( $screen->id, array( 'dashboard', 'edit-comments' ), true ) ) {
                        $avatar_default = carbonade( 'bco_default_avatar' );
                        $avatar = '<img alt="' . esc_attr( get_comment_author() ) . '" src="' . esc_url( $avatar_default ) . '" class="avatar avatar-' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" width="' . esc_attr( $size ) . '"/>';
                        return $avatar;
                    }
                
                }
                
                // frontend.
                return '<img src="' . esc_url( carbonade( 'bco_default_avatar' ) ) . '" width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" alt="' . esc_attr( get_comment_author() ) . '" class="avatar avatar-' . esc_attr( $size ) . ' photo" />';
            } else {
                // use gravatar.
                return $avatar;
            }
        
        }
        // Get the user id.
        $user_id = $user->ID;
        // Get the file id.
        $avatar_url = carbon_get_user_meta( $user->ID, 'bco_user_custom_avatar' );
        // Bail if we don't have a local avatar.
        if ( !$avatar_url ) {
            return $avatar;
        }
        
        if ( is_admin() ) {
            $screen = get_current_screen();
            // Properly show Avatars and Gravatars on the options-discussion.php.
            if ( is_object( $screen ) && in_array( $screen->id, array( 'options-discussion' ), true ) ) {
                /*
                We check if $avatar_default contains http as the key for our file URL is the one discriminant (and only one) we can use. If it's there, we'll display our avat, otherwise we'll just return WordPress default gravatars
                */
                
                if ( false !== strpos( $avatar_default, 'http' ) ) {
                    // contains HTTP so display our avatars.
                    // display user avatar in the top bar.
                    // it's not working though, not executed as $size seems to be defined afterwards.
                    
                    if ( '26' === $size || '64' === $size ) {
                        // Get the img markup.
                        $alt = get_user_meta( $user->ID );
                        $alt = $alt['nickname'][0];
                        $avatar = '<img alt="' . esc_attr( $alt ) . '" src="' . esc_html( $avatar_url ) . '" class="avatar avatar-' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" width="' . esc_attr( $size ) . '"/>';
                    } else {
                        // this displays our defaut avatar.
                        $avatar = '<img alt="' . esc_attr( $alt ) . '" src="' . esc_url( $avatar_default ) . '" class="avatar avatar-' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" width="' . esc_attr( $size ) . '"/>';
                    }
                    
                    return $avatar;
                } else {
                    // rest of the world.
                    return $avatar;
                }
            
            }
        }
        
        // Get the img markup.
        $alt = get_user_meta( $user->ID );
        $alt = $alt['nickname'][0];
        $avatar = '<img alt="' . esc_attr( $alt ) . '" src="' . esc_url( $avatar_url ) . '" class="avatar avatar-' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" width="' . esc_attr( $size ) . '"/>';
        // Return our new avatar.
        return $avatar;
    }
    
    add_filter(
        'get_avatar',
        __NAMESPACE__ . '\\gravatar_filter',
        900,
        5
    );
    /**
     * Get cached options from transient
     *
     * @return array $opt the options.
     */
    function get_cached_options()
    {
        $cache_key = 'better_comments_options';
        $opt = wp_cache_get( $cache_key );
        
        if ( false === $opt ) {
            $opt = get_option( 'BetterComments', array() );
            
            if ( empty($opt) ) {
                // If not options in cache or stored as option, fetch directly (as last resort).
                $opt_pattern = '_bco_%';
                $opt = array();
                // Use WordPress abstraction to safely prepare SQL query.
                global  $wpdb ;
                $like_pattern = $wpdb->esc_like( $opt_pattern );
                $query = $wpdb->prepare( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s", $like_pattern . '%' );
                $results = $wpdb->get_results( $query, ARRAY_A );
                foreach ( $results as $option ) {
                    $opt[$option['option_name']] = maybe_unserialize( $option['option_value'] );
                }
                // Cache fetched options using WordPress Object Cache.
                wp_cache_set( $cache_key, $opt );
                // Store in a transient and option as fallback.
                set_transient( 'BetterComments', $opt, 0 );
                update_option( 'BetterComments', $opt );
            } else {
                // Options were found as an option, ensure they are cached.
                wp_cache_set( $cache_key, $opt );
            }
        
        }
        
        return $opt;
    }
    
    add_action( 'carbon_fields_theme_options_container_saved', __NAMESPACE__ . '\\get_cached_options' );
    /**
     * Getter : retrieve cached options from transient
     *
     * @param mixed $key  the key to retrieve.
     * @param bool  $show false by default.
     *
     * @return mixed the value for a given key
     */
    function carbonade( $key, $show = false )
    {
        $transient = get_transient( 'BetterComments' );
        if ( false === $transient ) {
            return get_option( $key ) ?? '';
        }
        $key = '_' . $key;
        
        if ( true === $show ) {
            echo  esc_html( $transient[$key] ) ?? '' ;
        } else {
            return $transient[$key] ?? '';
        }
    
    }
    
    /**
     * Enqueue scripts
     *
     * @param mixed $hook the current admin hook.
     *
     * @return mixed styles and scripts
     */
    function add_scripts( $hook )
    {
        $array = array(
            'toplevel_page_better-comments',
            'better-comments_page_better-comments-account',
            'better-comments_page_better-comments-contact',
            'better-comments_page_better-comments-pricing'
        );
        
        if ( in_array( $hook, $array, true ) ) {
            wp_enqueue_style(
                'utopique-elements',
                plugins_url( 'lib/css/elements.css', __FILE__ ),
                false,
                BETTER_COMMENTS_VERSION,
                'all'
            );
            wp_enqueue_style(
                'bco-css',
                plugins_url( 'lib/css/bco.css', __FILE__ ),
                false,
                BETTER_COMMENTS_VERSION,
                'all'
            );
            wp_enqueue_script(
                'bco-js',
                plugins_url( 'lib/js/bco.js', __FILE__ ),
                array( 'jquery' ),
                BETTER_COMMENTS_VERSION,
                true
            );
            wp_localize_script( 'bco-js', 'BCO', array(
                'upgrade_url'      => sky_bc_fs()->get_upgrade_url(),
                'plus_feature'     => __( 'Premium feature', 'better-comments' ),
                'pro_feature'      => __( 'Premium feature', 'better-comments' ),
                'business_feature' => __( 'Premium feature', 'better-comments' ),
            ) );
            wp_add_inline_script( 'bco-js', 'const BetterComments = ' . wp_json_encode( array(
                'utopiqueJson' => home_url( '/wp-content/plugins/better-comments/lib/js/utopique-plugins.json' ),
                'installUrl'   => add_query_arg( array(
                's'    => 'utopique',
                'tab'  => 'search',
                'type' => 'term',
            ), admin_url( 'plugin-install.php?s=utopique&tab=search&type=term' ) ),
                'installText'  => __( 'Install', 'better-comments' ),
            ) ), 'before' );
            // remove WP emoji on our pages.
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
            remove_action( 'admin_print_styles', 'print_emoji_styles' );
            return;
        }
    
    }
    
    add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\add_scripts' );
}

// FS endif.