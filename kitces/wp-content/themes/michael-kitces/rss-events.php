<?php

/*
Template Name: Custom RSS Template - Events
*/

$events    = obj_get_ot_events( date( 'Y/m/d' ) );
$ot_events = obj_flat_events_array( $events );
header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
?>

<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		<?php do_action( 'rss2_ns' ); ?>>
<channel>
		<title><?php bloginfo_rss( 'name' ); ?> - Events</title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo_rss( 'url' ); ?></link>
		<description><?php bloginfo_rss( 'description' ); ?></description>
		<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
		<language>en-US</language>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
		<?php do_action( 'rss2_head' ); ?>
		<?php
		foreach ( $ot_events as $event ) :

			$event_date          = obj_get_event_start_date( $event );
			$date_string         = date_i18n( 'F j, Y', $event_date );
			$speaker_name        = obj_get_speaker_name( $event );
			$speaker_name_label  = 'Speaker: ';
			$location            = obj_get_event_location( $event );
			$link                = obj_get_event_link( $event );
			$event_title         = obj_get_event_title( $event );
			$excerpt             = obj_get_event_topics( $event );
			$additional_speakers = obj_get_event_additional_speakers( $event );

			if ( is_array( $link ) && array_key_exists( 'url', $link ) ) {
				$link = $link['url'];
			}

			if ( is_array( $additional_speakers ) ) {
				foreach ( $additional_speakers as $additional_speaker ) {
					$as_speaker         = mk_key_value( $additional_speaker, 'speaker' );
					$as_speaker_name    = obj_get_speaker_name( null, $as_speaker );
					$speaker_name_label = 'Speakers: ';

					if ( ! empty( $as_speaker_name ) ) {
						$speaker_name .= ', ' . $as_speaker_name;
					}
				}
			}

			?>
			<?php if ( $event_date > strtotime( 'now' ) ) : ?>
				<item>
					<title><?php echo $event_title; ?></title>
					<link><?php echo site_url(); ?></link>
					<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
					<dc:creator><?php the_author(); ?></dc:creator>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<description><![CDATA[<?php echo $excerpt; ?>]]></description>
					<content:encoded><![CDATA[
						<?php
						if ( ! empty( $speaker_name ) ) {
							echo $speaker_name_label . $speaker_name;
							echo '<br>';
						}
						if ( ! empty( $date_string ) ) {
							echo 'Date: ' . $date_string;
							echo '<br>';
						}
						if ( ! empty( $location ) ) {
							echo 'Location: ' . $location;
							echo '<br>';
						}
						if ( ! empty( $link ) ) {
							echo "<a href='$link'>View Event Details</a>";
						}
						?>
						]]></content:encoded>
						<?php rss_enclosure(); ?>
						<?php do_action( 'rss2_item' ); ?>
				</item>
			<?php endif; ?>
			<?php
		endforeach;
		wp_reset_postdata();
		?>
</channel>
</rss>
