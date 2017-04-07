<?php
/*
Template Name: Custom RSS Feed
*/

$numposts = 10;

function pronamic_rss_date( $timestamp = null ) {
	$timestamp = ( $timestamp == null ) ? time() : $timestamp;

	echo date( DATE_RSS, $timestamp );
}

function wp_get_thumbnail_url( $id ) {
	if ( has_post_thumbnail( $id ) ) {
		$imgArray = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
		$imgURL = $imgArray[0];
			return $imgURL;
	}
	else {
		return false;	
	}
}

function pronamic_rss_text_limit( $string, $length, $replacer = '...' ) { 
	$string = strip_tags( $string );

	if ( strlen( $string ) > $length ) {
		return ( preg_match( '/^(.*)\W.*$/', substr( $string, 0, $length +1 ), $matches ) ? $matches[1] : substr( $string, 0, $length ) ) . $replacer;   

		return $string;
	}
}

$posts = query_posts( array(
    'post_type'      => 'post',
    'posts_per_page' => $numposts,
) );

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0"?>'; ?>
<rss version="2.0">
	<channel>
		<title>Smile Connects Nieuwsberichten</title>
		<link>https://smileconnects.nl/</link>
		<description>The latest posts from Smileconnects.nl.</description>
		<language>nl-NL</language>
		<pubDate><?php pronamic_rss_date( strtotime( $posts[0]->post_date_gmt ) ); ?></pubDate>
		<lastBuildDate><?php pronamic_rss_date( strtotime( $posts[0]->post_date_gmt ) ); ?></lastBuildDate>

		<?php foreach ( $posts as $post ) : ?>

		<item>
			<title><?php echo get_the_title( $post->ID ); ?></title>
			<link><?php echo get_permalink( $post->ID ); ?></link>
			<enclosure url="<?php echo wp_get_thumbnail_url(get_the_ID()); ?>" type="image/jpeg" />
	 		<description><?php echo '<![CDATA[' . pronamic_rss_text_limit( $post->post_content, 500 ) . ']]>';  ?></description>
			<description><?php echo get_post_meta( $post->ID, 'name-of-meta-veld', true ); ?></description>
			<pubDate><?php pronamic_rss_date( strtotime( $post->post_date_gmt ) ); ?></pubDate>
			<guid><?php echo get_permalink( $post->ID ); ?></guid>
		</item>

		<?php endforeach; ?>
	</channel>
</rss>
