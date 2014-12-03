<?php

class Yoast_GP_SEO extends GP_Plugin {
	private $projects_slug  = '/projects';
	private $homepage_title = 'Projects';
	private $separator      = '<';
	private $site_name      = 'GlotPress';
	private $description    = '';

	public function set_homepage_title( $title ) {
		$this->homepage_title = $title;
	}

	public function set_separator( $separator ) {
		$this->separator = $separator;
	}

	public function set_site_name( $site_name ) {
		$this->site_name = $site_name;
	}

	public function set_homepage_description( $description ) {
		$this->homepage_description = $description;
	}

	/**
	 * Confirm defaults and hook into GlotPress.
	 */
	public function run() {
		$this->add_filter( 'gp_title' );
		$this->add_action( 'gp_head', array( 'priority' => 9 ) );
		$this->add_filter( 'gp_redirect_status', array( 'args' => 2 ) );
	}

	/**
	 * Modifies the title to not contain the "< GlotPress" but instead contain your own branding
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	public function gp_title( $title ) {
		if ( $this->projects_slug === $this->get_path() ) {
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
	public function gp_head() {
		$path    = $this->get_path();
		$project = GP::$project->by_path( str_replace( $this->projects_slug . '/', '', $path ) );

		$description = '';

		switch ( $path ) {
			case $this->projects_slug:
				$description = $this->homepage_description;
				break;
			default:
				if ( isset( $project ) && $project ) {
					$description = strip_tags( $project->description );
					$description = explode( "\n", $description );
					$description = $description[0];
				}
				break;
		}

		if ( $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '"/>' . "\n\n";
		}
	}

	/**
	 * Changes the projects page redirect from a 302 to a 301
	 *
	 * @param int    $status   Redirect status code.
	 * @param string $location The target location.
	 *
	 * @return int Redirect status code.
	 */
	public function gp_redirect_status( $status, $location ) {
		if ( 302 === $status && $this->projects_slug === $location ) {
			return 301;
		}

		return $status;
	}

	/**
	 * Get the current URL path.
	 *
	 * @return string
	 */
	private function get_path() {
		$url  = gp_url_current();
		$path = gp_url_path( $url );

		return $path;
	}
}
