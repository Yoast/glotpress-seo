<?php

class Yoast_GlotPress_SEO {

	private $homepage_desc = 'This the home of the Yoast Translate project, where all Yoast WordPress Plugins and Themes are being translated. Join today!';

	private $homepage_title = 'Translate Yoast Plugins to your language!';

	private $separator = '•';

	private $site_name = 'Yoast Translate';

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'gp_title', array( $this, 'modify_title' ) );
		add_action( 'gp_head', array( $this, 'meta_desc' ), 9 );
		add_filter( 'gp_redirect_status', array( $this, 'modify_redirect_status' ), 10, 2 );
	}

	/**
	 * Get the current URL path
	 *
	 * @return string
	 */
	private function get_path() {
		$url  = gp_url_current();
		$path = gp_url_path( $url );

		return $path;
	}

	/**
	 * Modifies the title to not contain the "< GlotPress" but instead contain your own branding
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	public function modify_title( $title ) {
		if ( '/projects' == $this->get_path() ) {
			return $this->homepage_title . ' ' . $this->separator . ' ' . $this->site_name;
		}

		// Replace the site name
		$title = preg_replace( '/GlotPress$/', $this->site_name, $title );

		// Replace the separator
		$title = str_replace( ' &lt; ', ' ' . $this->separator . ' ', $title );

		return $title;
	}

	/**
	 * Adds a meta description to all the important pages
	 */
	public function meta_desc() {
		$path    = $this->get_path();
		$project = GP::$project->by_path( str_replace( '/projects/', '', $path ) );

		$meta_desc = '';

		switch ( $path ) {
			case '/projects':
				$meta_desc = $this->homepage_desc;
				break;
			default:
				if ( isset( $project ) && $project ) {
					$meta_desc = strip_tags( $project->description );
					$meta_desc = explode( "\n", $meta_desc );
					$meta_desc = $meta_desc[0];
				}
				break;
		}
		if ( $meta_desc != '' ) {
			echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '"/>' . "\n\n";
		}
	}

	/**
	 * Changes the projects page redirect from a 302 to a 301
	 *
	 * @param int $status Redirect status code
	 * @param string $location The target location
	 *
	 * @return int Redirect status code
	 */
	public function modify_redirect_status( $status, $location ) {
		if ( 302 == $status && '/projects' == $location ) {
			return 301;
		}

		return $status;
	}

}

new Yoast_GlotPress_SEO();
