<?php

class AC_Ajax {

	private static $ajax_url = '';

	public function __construct() {

		self::$ajax_url = admin_url( 'admin-ajax.php' );

		add_action( 'wp_ajax_get_locations', 'AC_Ajax::get_locations' );
		add_action( 'wp_ajax_nopriv_get_locations', 'AC_Ajax::get_locations' );

		add_action( 'wp_ajax_get_people', 'AC_Ajax::get_people' );
		add_action( 'wp_ajax_nopriv_get_people', 'AC_Ajax::get_people' );

	}

	public static function set_ajax_url() {

		$url = array(
			'ajax' => self::$ajax_url,
		);

		wp_localize_script( 'location-map', 'url', $url );

	}

	public static function get_locations() {

		$locations = json_encode( AC_Query::get_locations( true ) );

		$data = array(
			'locations' => $locations,
		);

		wp_localize_script( 'location-map', 'data', $data );

	}

	public static function get_people() {

		$agrilife_people = get_transient( 'agrilife_people' );

		if ( false === $agrilife_people ) {
			// Get from PeopleAPI
			include plugin_dir_path( dirname( dirname(__FILE__) ) ) . '/agrilife-core/src/PeopleAPI.php';
			$soap = new \SoapClient( 'https://agrilifepeople-beta.tamu.edu/api/v4.cfc?wsdl' );
			$api = new \AgriLife\Core\PeopleAPI( $soap );
			$agrilife_people = $api->get_people( AGRILIFE_GET_PERSONNEL_HASH, array( 294, 286, 291, 379, 290, 297, 292, 300, 366, 298, 295, 396, 314, 302, 304 ) );
			set_transient( 'agrilife_people', $agrilife_people, WEEK_IN_SECONDS );
		}

		die( json_encode( $agrilife_people ) );

	}

}