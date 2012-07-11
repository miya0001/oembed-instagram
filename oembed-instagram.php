<?php
/*
Plugin Name: oEmbed Instagram
Plugin URI: http://wpist.me/wp/oembed-instagram
Description: Embed source from instagram.
Author: Takayuki Miyauchi
Version: 1.0.0
Author URI: http://wpist.me/
*/

new oEmbedInstagram();

class oEmbedInstagram {

private $api = "http://api.instagram.com/oembed?url=%s&maxwidth=%d";
private $large_img = "%s/media/?size=l";
private $prefix = '_instagram_';

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
    }

    $title = $data->title;
    $image = $data->url;
    $large = sprintf($this->large_img, $url);
    $width = $data->width;
    $height = $data->height;

    return sprintf(
        $this->get_template(),
        intval($width)+10,
        esc_url($large),
        esc_attr($title),
        esc_url($image),
        intval($width),
        intval($height),
        esc_html($title)
    );
}

private function get_from_api($url)
{
    global $content_width;

    $max_width = 0;
    if (isset($content_width) && intval($content_width)) {
        $max_width = intval($content_width);
    }

    $api = sprintf(
        $this->api,
        $url,
        $max_width
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
<div class="wp-caption alignleft oembed-instagram" style="width:%dpx">
    <a href="%s"><img class="size-full" title="%s" src="%s" alt="" width="%d" height="%d" /></a>
    <p class="wp-caption-text">%s</p>
</div>
EOL;

    return $html;
}

}

// EOF
