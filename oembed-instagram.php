<?php
/*
Plugin Name: oEmbed Instagram
Plugin URI: http://wpist.me/wp/oembed-instagram
Description: Embed source from instagram.
Author: Takayuki Miyauchi
Version: 1.4.0
Author URI: http://wpist.me/
*/

new oEmbedInstagram();

class oEmbedInstagram {

function __construct()
{
    add_action('plugins_loaded', array(&$this, 'plugins_loaded'));
}

public function plugins_loaded()
{
    wp_embed_register_handler(
        'instagram',
        '#http://instagram.com/.*/?$#i',
        array($this, 'oembed_handler')
    );
}

public function oembed_handler($m, $attr, $url, $rattr)
{
    $html = '<iframe src="%s/embed" height="710" width="612" frameborder="0" scrolling="no"></iframe>';

    $res = parse_url($m[0]);
    $uri = untrailingslashit('//'.$res['host'].$res['path']);

    return sprintf($html, $uri);
}

}

// EOF
