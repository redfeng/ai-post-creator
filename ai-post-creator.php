<?php
/**
 * Plugin Name:       AI Post Creator
 * Plugin URI:        https://github.com/your-repo/ai-post-creator
 * Description:       Provides a REST API endpoint to create Gutenberg posts from AI-generated Markdown content.
 * Version:           1.0.1
 * Author:            Gemini
 * Author URI:        https://gemini.google.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-post-creator
 */

// Prevent direct access to the file.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The main class for the plugin.
 */
final class AI_Post_Creator {

    /**
     * The single instance of the class.
     *
     * @var AI_Post_Creator
     */
    private static $instance = null;

    /**
     * Main AI_Post_Creator Instance.
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @static
     * @return AI_Post_Creator - Main instance.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->setup();
        }
        return self::$instance;
    }

    /**
     * Setup plugin functionality.
     */
    private function setup() {
        // The 'rest_api_init' hook is the correct place to register custom REST API routes.
        add_action('rest_api_init', array($this, 'register_rest_route'));
    }

    /**
     * Register the custom REST API route.
     */
    public function register_rest_route() {
        register_rest_route(
            'ai-creator/v1', // A concise and unique namespace for our plugin.
            '/create-post',      // The endpoint for creating a post.
            array(
                'methods'             => 'POST',
                'callback'            => array($this, 'handle_create_post_from_md'),
                'permission_callback' => array($this, 'check_permissions'),
                'args'                => array(
                    'title' => array(
                        'required'          => true,
                        'type'              => 'string',
                        'description'       => 'The title for the new post.',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'markdown_content' => array(
                        'required'          => true,
                        'type'              => 'string',
                        'description'       => 'The full Markdown content for the post body.',
                        'sanitize_callback' => 'wp_kses_post',
                    ),
                    'status' => array(
                        'required'          => false,
                        'type'              => 'string',
                        'description'       => "The post status (e.g., 'publish', 'draft', 'pending'). Defaults to 'draft'.",
                        'default'           => 'draft',
                    ),
                ),
            )
        );
    }

    /**
     * Permission check for the REST API endpoint.
     *
     * @return bool|WP_Error
     */
    public function check_permissions() {
        if (!current_user_can('publish_posts')) {
            return new WP_Error(
                'rest_forbidden',
                'You do not have permissions to publish posts.',
                array('status' => 401)
            );
        }
        return true;
    }

    /**
     * The callback function to handle the post creation.
     *
     * @param WP_REST_Request $request The incoming API request.
     * @return WP_REST_Response|WP_Error
     */
    public function handle_create_post_from_md($request) {
        if (!file_exists(__DIR__ . '/Parsedown.php')) {
            return new WP_Error(
                'parsedown_missing',
                'The Parsedown library is missing. Please download Parsedown.php and add it to the plugin directory.',
                array('status' => 500)
            );
        }
        require_once __DIR__ . '/Parsedown.php';

        $title            = $request['title'];
        $markdown_content = $request['markdown_content'];
        $status           = $request['status'];

        $Parsedown = new Parsedown();
        $html_content = $Parsedown->text($markdown_content);

        $blocks = wp_convert_html_to_blocks($html_content);

        $post_content = serialize_blocks($blocks);

        $post_data = array(
            'post_title'    => $title,
            'post_content'  => $post_content,
            'post_status'   => $status,
            'post_author'   => get_current_user_id(),
        );

        $post_id = wp_insert_post($post_data, true);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        $response_data = array(
            'success'   => true,
            'post_id'   => $post_id,
            'post_url'  => get_permalink($post_id),
            'message'   => 'Post created successfully.',
        );

        return new WP_REST_Response($response_data, 201);
    }
}

/**
 * Begins execution of the plugin.
 */
function ai_post_creator_run() {
    return AI_Post_Creator::instance();
}

// Kick it off.
ai_post_creator_run();
