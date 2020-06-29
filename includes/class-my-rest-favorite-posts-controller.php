<?php

namespace LogFavoritePosts\Includes;

class My_REST_Favorite_Posts_Controller {
 
    /**
     * Initializes properties, actions and filters of the class.
     *
     * @return    void
     */
    public function initialize() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    // Here initialize our namespace and resource name.
    public function __construct() {
        $this->namespace     = 'log-favorite-posts/v1';
        $this->resource_name = 'favorite-posts';
    }
 
    // Register our routes.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name, [
            'methods' => 'GET',
            'callback' => array( $this, 'get_favorite_posts' ),
        ]);

        register_rest_route( $this->namespace, '/' . $this->resource_name, [
            'methods' => 'PUT',
            'callback' => array( $this, 'update_favorite_post' ),
        ]);
    }
 
    /**
     * Grabs the most recent favorite posts and outputs them as a rest response.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function get_favorite_posts( $request ) {
        $posts = $this->get_favorite_post_list();

        return new \WP_REST_Response([
            'data' => $posts
        ], 200);
    }

    /**
     * Set the status of the specified post, bookmarked/unmarked as favorite.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function update_favorite_post( $request ) {
        $input = $request->get_params();

        if ( isset( $input['post_id'] ) && $input['post_id'] ) {
            $post_id = $input['post_id'];

            if ( $this->check_favorited( $post_id ) ) {
                $this->set_cookie( $post_id, '' );
                
                return new \WP_REST_Response([
                    'status' => 'unmarked',
                    'message' => 'Post has been unmarked as a favorite',
                ], 200);
            }

            $this->set_cookie( $post_id, 'added' );

            return new \WP_REST_Response([
                'status' => 'marked',
                'message' => 'Post has been bookmarked',
            ], 200);
        }

        return new \WP_REST_Response([
            'message' => 'the post_id field is required',
        ], 422);
    }

    private function set_cookie( $post_id, $str ) {
        $expire = time() + 60 * 60 * 24 * 30;
        return setcookie( "wp-favorite-posts[$post_id]", $str, $expire, "/" );
    }

    private function get_cookie() {
        if ( isset( $_COOKIE['wp-favorite-posts'] ) ) {
            return $_COOKIE['wp-favorite-posts'];
        }

        return false;
    }

    private function check_favorited($cid) {
        if ( $this->get_cookie() ){
            foreach ( $this->get_cookie() as $fpost_id => $val ) {
                if ( $fpost_id == $cid && $val == 'added' ) {
                    return true;
                }
            }

            return false;
        }

        return $this->get_cookie();
    }

    private function get_favorite_post_list() {
        $post_ids = [];
        if ( $this->get_cookie() ){
            foreach ( $this->get_cookie() as $fpost_id => $val ) {
                if ( $val ) {
                    array_push( $post_ids, $fpost_id );
                }
            }
        }

        if ( empty( $post_ids ) ) {
            return $post_ids;
        }

        return get_posts([
            'post__in' => $post_ids,
            'numberposts' => -1,
        ]);
    }
}