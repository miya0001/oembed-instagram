<?php
/*
Plugin Name: oEmbed Instagram
Plugin URI: http://wpist.me/wp/oembed-instagram
Description: Embed source from instagram.
Author: Takayuki Miyauchi
Version: 1.2.0
Author URI: http://wpist.me/
*/

new oEmbedInstagram();

class oEmbedInstagram {

private $api = "http://api.instagram.com/oembed?url=%s&maxwidth=%d";
private $large_img = "%s/media/?size=l";
private $prefix = '_instagram_';
private $max_width = 306;

function __construct()
{
    add_action('plugins_loaded', array(&$this, 'plugins_loaded'));
}

public function plugins_loaded()
{
    wp_embed_register_handler(
        'instagram',
        '#http://instagram.com/p/[a-zA-Z0-9]+/?$#i',
        array(&$this, 'oembed_handler')
    );
}

public function oembed_handler($m, $attr, $url, $rattr)
{
    $url = preg_replace("#/$#", "", $m[0]);

    $post_id = get_the_id();
    if ($meta = get_post_meta($post_id, $this->prefix.$url)) {
        $data = $meta[0];
    } else {
        $data = $this->get_from_api($url);
        if (!$data) {
            return ; // nothing to do on error
        }
    }

    $title = $data->title;
    $title = str_replace(array("\r\n", "\r", "\n"), "", $title);
    $image = $data->url;
    $large = sprintf($this->large_img, $url);
    $width = $data->width;
    $height = $data->height;

    $html = $this->get_template();
    $html = str_replace('%title%', esc_html($title), $html);
    $html = str_replace('%large%', esc_url($large), $html);
    $html = str_replace('%image%', esc_url($image), $html);
    $html = str_replace('%width%', intval($width), $html);
    $html = str_replace('%height%', intval($height), $html);

    return $html;
}

private function get_from_api($url)
{
    $api = sprintf(
        $this->api,
        $url,
        apply_filters("oembed-instagram-maxwidth", $this->max_width)
    );

    $res = wp_remote_get($api);
    if ($res['response']['code'] === 200) {
        $json = json_decode($res['body']);
        $post_id = get_the_id();
        update_post_meta($post_id, $this->prefix.$url, $json);
        return $json;
    }
}

private function get_template()
{
    $html =<<<EOL
<div class="oembed-instagram">
[caption width="%width%" caption="%title%"]
<a href="%large%"><img class="size-full" src="%image%" alt="" width="%width%" height="%height%" /></a>
[/caption]
</div><!--.oembed-instagram-->
EOL;

    return apply_filters("oembed-instagram-template", $html);
}

}

// EOF
