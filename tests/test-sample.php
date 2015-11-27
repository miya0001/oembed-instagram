<?php

class oEmbed_Instagram_Test extends WP_UnitTestCase
{
	/**
	 * @test
	 */
	function oembed_01()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'http://instagram.com/p/iIkL99CI87/',
			'post_status' => 'publish',
		);

		$this->setup_postdata( $args );

		/*
		 * the_content()の結果に対してテスト
		 */
		$this->expectOutputString( '<div class="oembed-instagram"><iframe src="//instagram.com/p/iIkL99CI87/embed" width="612" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe></div>' . "\n" );
		the_content();
	}

	/**
	 * @test
	 */
	function oembed_02()
	{
		$args = array(
			'post_title' => 'Hello',
			'post_author' => 1,
			'post_content' => 'https://www.instagram.com/p/iIkL99CI87/',
			'post_status' => 'publish',
		);

		$this->setup_postdata( $args );

		/*
		 * the_content()の結果に対してテスト
		 */
		$this->expectOutputString( '<div class="oembed-instagram"><iframe src="//www.instagram.com/p/iIkL99CI87/embed" width="612" height="710" frameborder="0" scrolling="no" allowtransparency="true"></iframe></div>' . "\n" );
		the_content();
	}

	/**
	 * Add post and post be set to current.
	 *
	 * @param  array $args A hash array of the post object.
	 * @return none
	 */
	public function setup_postdata( $args )
	{
		global $post;
		global $wp_query;

		$wp_query->is_singular = true;

		$post_id = $this->factory->post->create( $args );
		$post = get_post( $post_id );
		setup_postdata( $post );
	}
}
