<?php

/**
 * Class Test_My_REST_Favorite_Posts_Controller
 *
 * @package Log_Favorite_Posts
 */
class Test_My_REST_Favorite_Posts_Controller extends WP_UnitTestCase {

    protected $server;

    protected $post_id;
    
    public function setUp() {
        parent::setUp();
        
        global $wp_rest_server;
        $this->server = $wp_rest_server = new WP_REST_Server;
        do_action( 'rest_api_init' );

        $this->post_id = $this->factory->post->create();
        error_reporting(0); 
    }

    public function tearDown() {
        parent::tearDown();
     
        global $wp_rest_server;
        $wp_rest_server = null;
    }
    
    public function test_get_favorite_posts() {
        $request = new WP_REST_Request( 'GET', '/log-favorite-posts/v1/favorite-posts' );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 200, $response->get_status() );

        $data = $response->get_data();
        $this->assertArrayHasKey( 'data', $data );
    }

    public function test_update_favorite_post_add() {
        $request = new WP_REST_Request( 'PUT', '/log-favorite-posts/v1/favorite-posts' );
        $request->set_param( 'post_id', $this->post_id );
        $response = $this->server->dispatch( $request );

        $this->assertEquals( 200, $response->get_status() );

        $data = $response->get_data();
        $this->assertEquals( 'marked', $data['status'] );
    }
}