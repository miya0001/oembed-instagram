<?php
/*
Plugin Name: oEmbed Instagram
Plugin URI: https://github.com/miya0001/oembed-instagram
Description: Embed image and video from instagram.
Author: Takayuki Miyauchi
Version: 1.7.0
Author URI: http://wpist.me/
*/

$oembed_instagram = new oEmbedInstagram();
$oembed_instagram->register();

class oEmbedInstagram {

	function register()
	{
		add_action( 'plugins_loaded', array(  $this, 'plugins_loaded' ) );
		add_action( 'wp_enqueue_scripts', array(  $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	public function wp_head(  )
	{
		$css = '<style type="text/css">';
		$css .= '.oembed-instagram{box-shadow: 1px 1px 3px #efefef;  background-color: #ffffff; border: 1px solid #f5f5f5; margin: 1em 5px; padding: 8px;}';
		$css .= '.oembed-instagram iframe{display: block; margin: 0 auto; max-width: 100%; box-sizing: border-box;}';
		$css .= '</style>'."\n";

		echo apply_filters( 'oembed_instagram_style', $css);
	}

	public function wp_enqueue_scripts()
	{
		wp_enqueue_script(
			'oembed-instagram',
			plugins_url( 'oembed-instagram.js', __FILE__ ),
			array( 'jquery' ),
			'1.5.1',
			true
		);
	}

	public function plugins_loaded()
	{
		wp_embed_register_handler(
			'instagram',
			'#http(?:s?)://(www.)?instagram.com/.*/?$#i',
			array( $this, 'oembed_handler' )
		 );
	}

	public function oembed_handler( $m, $attr, $url, $rattr )
	{
		$html = '<div class="oembed-instagram"><iframe src="%s/embed" width="612" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe></div>';

		$res = parse_url( $m[0] );
		$uri = untrailingslashit( '//' . $res['host'] . $res['path'] );

		return sprintf( $html, $uri );
	}

}

// EOF
