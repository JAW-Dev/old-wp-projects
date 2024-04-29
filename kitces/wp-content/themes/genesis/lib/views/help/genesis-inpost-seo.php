<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package StudioPress\Genesis
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

?>
<p>
	<strong><?php esc_html_e( 'Document Title', 'genesis' ); ?></strong>
	<?php esc_html_e( ' &mdash; Output in the document <title> Tag.', 'genesis' ); ?>
</p>
<p>
	<strong><?php esc_html_e( 'Meta Description', 'genesis' ); ?></strong>
	<?php esc_html_e( ' &mdash; Output in the document <meta name="description" /> Tag.', 'genesis' ); ?>
</p>
<p>
	<strong><?php esc_html_e( 'Meta Keywords', 'genesis' ); ?></strong>
	<?php esc_html_e( ' &mdash; Output in the document <meta name="keywords" /> Tag.', 'genesis' ); ?>
</p>
<p>
	<strong><?php esc_html_e( 'Canonical URL', 'genesis' ); ?></strong>
	<?php
	/* translators: %s: Reference link. */
	printf( esc_html__( ' &mdash; Output in the document <link rel="canonical" />. %s', 'genesis' ), '<a href="http://www.mattcutts.com/blog/canonical-link-tag/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read more about Custom Canonical URL', 'genesis' ) . '</a>' );
	?>
</p>
<p>
	<strong><?php esc_html_e( 'Custom Redirect URL', 'genesis' ); ?></strong>
	<?php
	/* translators: %s: Reference link. */
	printf( esc_html__( ' &mdash; Redirect this post/page to this URL. %s.', 'genesis' ), '<a href="http://www.google.com/support/webmasters/bin/answer.py?hl=en&amp;answer=93633" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read more about 301 Redirects', 'genesis' ) . '</a>' );
	?>
</p>
<p>
	<strong><?php esc_html_e( 'Apply noindex to this post/page', 'genesis' ); ?></strong>
	<?php
	/* translators: %s: Reference link. */
	printf( esc_html__( ' &mdash; Output in the document <meta name="robots" />. %s', 'genesis' ), '<a href="http://yoast.com/articles/robots-meta-tags/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read more about noindex', 'genesis' ) . '</a>' );
	?>
</p>
<p>
	<strong><?php esc_html_e( 'Apply nofollow to this post/page', 'genesis' ); ?></strong>
	<?php
	/* translators: %s: Reference link. */
	printf( esc_html__( ' &mdash; Output in the document <meta name="robots" />. %s', 'genesis' ), '<a href="http://yoast.com/articles/robots-meta-tags/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read more about nofollow', 'genesis' ) . '</a>' );
	?>
</p>
<p>
	<strong><?php esc_html_e( 'Apply noarchive to this post/page', 'genesis' ); ?></strong>
	<?php
	/* translators: %s: Reference link. */
	printf( esc_html__( ' &mdash; Output in the document <meta name="robots" />. %s.', 'genesis' ), '<a href="http://yoast.com/articles/robots-meta-tags/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Read more about noarchive', 'genesis' ) . '</a>' );
	?>
</p>
