<?php

class AC_Ajax {

	private static $ajax_url = '';

	public function __construct() {

		self::$ajax_url = admin_url( 'admin-ajax.php' );

		add_action( 'wp_ajax_get_locations', array( $this, 'get_locations' ) );
		add_action( 'wp_ajax_nopriv_get_locations', array( $this, 'get_locations' ) );

	}

	public static function set_ajax_url() {

		$url = array(
			'ajax' => self::$ajax_url,
		);

		wp_localize_script( 'location-map', 'url', $url );

	}

	public function get_locations() {


		$locations = json_encode( AC_Query::get_locations( true ) );
		die($locations);

	}

}