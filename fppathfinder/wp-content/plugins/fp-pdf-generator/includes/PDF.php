<?php

namespace FP_PDF_Generator;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use FpAccountSettings\Includes\Utilites\Media\Image;
use FpAccountSettings\Includes\Classes\Logo;

class PDF {
	/**
	 * @var string
	 */
	public $licensee;

	/**
	 * @var string
	 */
	public $logo;

	/**
	 * @var \Mpdf\Mpdf
	 */
	public $mpdf;

	/**
	 * @var string
	 */
	public $output_filename;

	/**
	 * @var array
	 */
	public $svg_paths;

	/**
	 * @var array
	 */
	public $back_page_settings = array();

	/**
	 * @var int
	 */
	public $post_id;

	/**
	 * @var int
	 */
	public $user_id;

	/**
	 * @var array
	 */
	public $colors = array(
		'#0f1e2c', // primary
		'#9e251f', // secondary
		'#596924', // accent
		'#cea232', // location
	);

	/**
	 * @var array
	 */
	public $logo_dimensions = array(
		'width'  => 229,
		'height' => 66,
	);

	/**
	 * Construct
	 *
	 * @param array  $svg_paths        An array of strings of paths to svg files
	 * @param string $licensee         (Optional) The string to use as the licensee in the copyright statement at the bottom of pages
	 * @param string $output_filename  (Optional) The filename for the final output file
	 */
	public function __construct( array $svg_paths, string $licensee = '', string $output_filename = 'output.pdf' ) {
		$this->svg_paths       = $svg_paths;
		$this->output_filename = $output_filename;
		$this->licensee        = $licensee;
		$this->logo            = $this->get_default_logo();
	}

	/**
	 * Generate
	 *
	 * Generate a pdf and ouput according to parameter passed in.
	 *
	 * @param $output_mode For use in \Mpdf::Output()
	 *
	 * @return string|void
	 */
	public function generate( string $output_mode, string $dir_path = null, array $user_settings = [] ) {
		try {
			$this->mpdf = new Mpdf( $this->get_mpdf_options() );
			$this->mpdf->setBasePath( home_url() );

			$this->mpdf->curlAllowUnsafeSslRequests = true;

			// $this->mpdf->showImageErrors = true; // phpcs:ignore

			$this->write_svg_pages( $user_settings );

			if ( $this->get_back_page_settings() ) {
				$this->write_back_page( $user_settings );
			}

			$this->maybe_log_download( $output_mode );

			return $this->mpdf->Output( $dir_path . $this->output_filename, $output_mode );
		} catch ( \Mpdf\MpdfException $e ) { // borrowed from Brandon's code, should be reviewed
			echo json_encode( [ 'error' => $e->getMessage() ], JSON_FORCE_OBJECT );
			wp_die();
		}
	}

	/**
	 * Maybe Log Download
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_log_download( $output_mode ) {
		if ( $output_mode !== 'D' ) {
			return;
		}

		$user_id = $this->get_user_id();

		$excluded_users = [
			4084,
		];

		if ( in_array( (int) $user_id, $excluded_users, true ) ) {
			return;
		}

		$pdf_id   = $this->get_post_id();
		$datetime = new \DateTime();

		$new_download = [
			'user_id' => $user_id,
			'date'    => $datetime->format( 'Y-m-d H:i:s' ),
		];

		$log   = get_post_meta( $pdf_id, 'times_downloaded', true ) ? get_post_meta( $pdf_id, 'times_downloaded', true ) : array();
		$log[] = $new_download;

		update_post_meta( $pdf_id, 'times_downloaded', $log );
	}

	/**
	 * Generate Inline
	 *
	 * Generate a pdf file and send it inline to the browser.
	 *
	 * @return void
	 */
	public function generate_inline() {
		$this->generate( 'I' );
	}

	/**
	 * Generate String
	 *
	 * Generate a pdf file and return its contents as a string. Filename is ignored.
	 *
	 * @return string
	 */
	public function generate_string() {
		return $this->generate( 'S' );
	}

	/**
	 * Generate Download
	 *
	 * Generate a pdf file and send it as a download to the browser.
	 *
	 * @return void
	 */
	public function generate_download() {
		$this->generate( 'D' );
	}

	/**
	 * Write File
	 *
	 * Write the new pdf to file at the given location.
	 *
	 * @param string $dir_path
	 *
	 * @return string path to new file
	 */
	public function write_file( string $dir_path, array $user_settings = [] ) {
		$this->generate( 'F', trailingslashit( $dir_path ), $user_settings );
		return trailingslashit( $dir_path ) . $this->output_filename;
	}

	/**
	 * Write SVG Pages
	 *
	 * Iterating through the SVG file paths given at construction, prepare each svg with any necessary customizations and write it to $this->mpdf.
	 *
	 * @return void Modifies $this->mpdf
	 */
	public function write_svg_pages() {
		$svg_paths = $this->svg_paths;

		$svgs         = array_map( array( $this, 'prepare_svg' ), $svg_paths );
		$page_opening = $this->get_pdf_page_opening_html();
		$page_closing = $this->get_pdf_page_closing_html();
		$logo_html    = $this->get_logo_html();

		$this->mpdf->useSubstitutions = false;
		$this->mpdf->simpleTables     = true;

		foreach ( $svgs as $index => $svg ) {
			$this->mpdf->WriteHTML( $page_opening );

			$this->mpdf->BeginLayer( 1 );
			$this->mpdf->WriteHTML( $logo_html );
			$this->mpdf->EndLayer();

			$svg_div = $this->get_prepared_svg_html( $svg );

			$this->mpdf->BeginLayer( 2 );
			$this->mpdf->WriteHTML( $svg_div );
			$this->mpdf->EndLayer();

			$this->mpdf->WriteHTML( $page_closing );

			$more_svgs = $index + 1 < count( $svgs );

			if ( $more_svgs ) {
				$this->mpdf->AddPage();
			}
		}
	}

	/**
	 * Prepare SVG
	 *
	 * Take in a path for an svg file and output a customized svg element ready to write to pdf.
	 *
	 * @param string $svg_path The file path for the svg file.
	 *
	 * @see $this->write_svg_pages
	 *
	 * @return string
	 */
	public function prepare_svg( string $svg_path ) {
		if ( ! file_exists( $svg_path ) ) {
			return;
		}

		$svg = file_get_contents( $svg_path );

		$dom_document = new \DOMDocument();
		$dom_document->loadXML( $svg );

		$this->customize_colors( $dom_document );
		$this->uppercase_titles( $dom_document );
		$this->update_copyright( $dom_document );

		return $dom_document->saveXML();
	}

	/**
	 * Update Copyright
	 *
	 * Given a \DOMDocument, update the copyright statement.
	 *
	 * @param \DOMDocument &$dom_document
	 *
	 * @return void Modifies the \DOMDocument whose pointer it was given
	 */
	public function update_copyright( \DOMDocument $dom_document ) {
		$text_elements = $dom_document->getElementsByTagName( 'text' );

		foreach ( $text_elements as $element ) {
			$id                     = $element->getAttribute( 'id' );
			$is_copyright_statement = strpos( $id, 'copyright-replace' ) !== false;

			if ( $is_copyright_statement ) {
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
				$element->textContent = $this->get_copyright_statement( $element->textContent );
			}
		}
	}

	/**
	 * Get Copyright Statement
	 *
	 * Given the copyright statement that exists on the svg, parse out the necessary pieces and output the final correct statement.
	 *
	 * @param string $existing_copyright_string
	 *
	 * @return string
	 */
	public function get_copyright_statement( string $existing_copyright_string ) {
		$pattern = '/^(.*)(\. All rights reserved. Used with permission\.)/';

		if ( $this->licensee ) {
			$replacement = "© fpPathfinder.com. Licensed for the sole use of {$this->licensee}$2";
		} else {
			$replacement = '© fpPathfinder.com. Not to be reproduced, redistributed, or retransmitted in whole or in part$2';
		}

		return preg_replace( $pattern, $replacement, $existing_copyright_string );
	}

	/**
	 * Uppercase Titles
	 *
	 * Given a \DOMDocument, update the elements that need their text content transformed to uppercase. Not all SVGS need this but some legacy ones do. We can't use \DOMDocument::getElementById because there's no doctype set up on the \DOMDocument. This was written by the previous developer to only look for those two ids. It may come to pass that exactly how this works should be revisited.
	 *
	 * @param \DOMDocument &$dom_document
	 *
	 * @return void Modifies the \DOMDocument whose pointer it was given
	 */
	public function uppercase_titles( \DOMDocument $dom_document ) {
		$text_elements = $dom_document->getElementsByTagName( 'text' );

		foreach ( $text_elements as $element ) {
			$id              = $element->getAttribute( 'id' );
			$needs_uppercase = in_array( $id, array( 'svg-title-text', 'svg-title-text2' ) );

			if ( $needs_uppercase ) {
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
				$element->textContent = strtoupper( $element->textContent );
			}
		}
	}

	/**
	 * Get Element Fill
	 *
	 * Given a \DOMElement determine which, if any, custom fill color should be used for it.
	 *
	 * @param \DOMElement $element
	 *
	 * @return bool|string A hex code or false.
	 */
	public function get_element_custom_fill( \DOMElement $element ) {
		$id = $element->getAttribute( 'id' );

		if ( preg_match( '/Color(\d+)-Zone\d+/', $id, $matches ) ) {
			$color_number = $matches[1];
			$color_index  = intval( $color_number ) - 1;

			return $this->get_colors()[ $color_index ];
		} else {
			return false;
		}
	}

	/**
	 * Customize Colors
	 *
	 * Given a \DOMDocument, update the elements that get a custom fill color.
	 *
	 * @param \DOMDocument &$dom_document
	 *
	 * @return void Modifies the \DOMDocument whose pointer it was given
	 */
	public function customize_colors( \DOMDocument $dom_document ) {
		$elements = $dom_document->getElementsByTagName( '*' );

		foreach ( $elements as $element ) {
			$fill_color = $this->get_element_custom_fill( $element );

			if ( $fill_color ) {
				$this->change_element_fill( $element, $fill_color );
			}
		}
	}

	/**
	 * Change Element Fill
	 *
	 * Given a \DOMElement, update the inline style attribute value for fill to be the given $fill_color
	 *
	 * @param \DOMElement $element
	 * @param string      $fill_color A hex value.
	 *
	 * @return void Modifies the \DOMElement whose pointer it was given
	 */
	public function change_element_fill( \DOMElement $element, string $fill_color ) {
		$style          = $element->getAttribute( 'style' );
		$styles         = $this->arrayify_style_string( $style );
		$styles['fill'] = $fill_color;
		$new_style      = $this->stringify_style_array( $styles );

		$element->setAttribute( 'style', $new_style );
	}

	/**
	 * Arrayify Style String
	 *
	 * Given a string of inline styles, return an array of array( 'property' => 'value'... ).
	 *
	 * @see $this->stringify_style_array
	 *
	 * @param string $style A string of css styles
	 *
	 * @return array An array of styles broken out into key value pairs
	 */
	public function arrayify_style_string( string $style ) {
		$results = array();

		preg_match_all( '/([\w-]+)\s*:\s*([^;]+)\s*;?/', $style, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$results[ $match[1] ] = $match[2];
		}

		return $results;
	}

	/**
	 * Stringify Style Array
	 *
	 * Given an array of style properties and values, return a string for use in an inline style attribute.
	 *
	 * @see $this->arrayify_style_string
	 *
	 * @param array $styles An array of styles broken out into key value pairs
	 *
	 * @return string A string of css styles
	 */
	public function stringify_style_array( array $styles ) {
		$string = '';

		foreach ( $styles as $property => $value ) {
			$string .= "{$property}: {$value};";
		}

		return $string;
	}

	public function write_back_page( $user_settings = [], $attachment = '' ) {
		$logo_html    = $this->get_logo_html() ? $this->get_logo_html() : '';
		$notch        = $this->get_notch_html() ? $this->get_notch_html() : '';
		$page_opening = $this->get_pdf_page_opening_html() ? $this->get_pdf_page_opening_html() : '';
		$page_closing = $this->get_pdf_page_closing_html() ? $this->get_pdf_page_closing_html() : '';
		$back_page    = $this->get_back_page_html( $user_settings, $attachment ) ? $this->get_back_page_html( $user_settings, $attachment ) : '';

		if ( ! empty( $page_opening ) ) {
			$this->mpdf->AddPage();
			$this->mpdf->WriteHTML( $page_opening );
		}

		if ( ! empty( $logo_html ) ) {
			$this->mpdf->BeginLayer( 1 );
			$this->mpdf->WriteHTML( $logo_html );
			$this->mpdf->EndLayer();
		}

		if ( ! empty( $notch ) ) {
			$this->mpdf->BeginLayer( 2 );
			$this->mpdf->WriteHTML( $notch );
			$this->mpdf->EndLayer();
		}

		if ( ! empty( $back_page ) ) {
			$this->mpdf->BeginLayer( 3 );
			$this->mpdf->WriteHTML( $back_page );
			$this->mpdf->EndLayer();
		}

		if ( ! empty( $page_closing ) ) {
			// Note: Solves back page title font issue. Not sure why. ¯\_(ツ)_/¯
			$this->mpdf->BeginLayer( 4 );
			$this->mpdf->WriteHTML( '' );
			$this->mpdf->EndLayer();

			$this->mpdf->WriteHTML( $page_closing );
		}
	}

	public function get_back_page_html( $user_settings = [], $attachment = '' ) {
		$settings               = fp_get_pdf_settings( $user_settings );
		$chevron_image          = $this->get_chevron_image();
		$title_background_color = $this->get_colors()[0];

		$settings_advisor_name           = ! empty( $settings['advisor_name'] ) ? $settings['advisor_name'] : '';
		$settings_job_title              = ! empty( $settings['job_title'] ) ? $settings['job_title'] : '';
		$settings_email                  = ! empty( $settings['email'] ) ? $settings['email'] : '';
		$settings_address                = ! empty( $settings['address'] ) ? $settings['address'] : '';
		$settings_phone                  = ! empty( $settings['phone'] ) ? $settings['phone'] : '';
		$settings_website                = ! empty( $settings['website'] ) ? $settings['website'] : '';
		$settings_second_page_body_title = ! empty( $settings['second_page_body_title'] ) ? $settings['second_page_body_title'] : '';
		$settings_second_page_title      = ! empty( $settings['second_page_title'] ) ? $settings['second_page_title'] : '';
		$settings_second_page_body_copy  = ! empty( $settings['second_page_body_copy'] ) ? $settings['second_page_body_copy'] : '';
		$settings_use_advanced           = ! empty( $settings['use_advanced'] ) ? $settings['use_advanced'] : false;
		$settings_advanced_body          = ! empty( $settings['advanced_body'] ) ? stripslashes( $settings['advanced_body'] ) : '';

		$adviser_detail_line_1 = join( ', ', array_filter( array( $settings_advisor_name, $settings_job_title ) ) );
		$adviser_detail_line_3 = join( ' | ', array_filter( array( $settings_email, $settings_phone, $settings_website ) ) );
		$no_advisor_name       = fp_use_advisor_name();

		if ( $no_advisor_name ) {
			$adviser_detail_line_1 = $settings_advisor_name;
		}

		$chevron_div                    = "<div style='text-align:center; margin:0 auto 48px; width:10px; height:7px;'><img style='width: 11px; height: 6px;' src='{$chevron_image}'></div>";
		$second_page_body_title_section = empty( $settings_second_page_body_title ) ? '' : "<div style='text-align:center; background:none; margin-bottom:48px; font-family:opensans-bold;'>{$settings_second_page_body_title}</div>{$chevron_div}";

		ob_start();
		?>
		<div style="position: relative">
			<div style=" width:77%; background:#ffffff; position:relative;">
				<div style="text-align: center; background: <?php echo $title_background_color; ?>; color: rgb(255, 255, 255); font-size: 1.5em; height: 81px; line-height: 81px;">
					<div style='color: #ffffff; font-family:opensans-regular;'><?php echo $settings_second_page_title; ?></div>
				</div>
			</div>
			<?php
			if ( $settings_use_advanced === 'true' ) {
				?>
				<div style="background:#f3f3f4; width:100%; float:none; padding-left: 32px; padding-right: 32px;">
					<?php
					if ( ! empty($settings_advanced_body ) && ! empty( $second_page_body_title_section ) ) {
						?>
						<div style="padding-top: 80px"><?php echo $second_page_body_title_section; ?></div>
						<?php
					}
					?>
					<div style="text-align:left;">
						<pre style="color: #000; font-family:opensans-regular;"><?php echo$settings_advanced_body; ?></pre>
					</div>
				</div>
				<?php
			} else {
				if ( ! empty( $attachment ) ) {
					?>
					<div style="width:100%; float:none; position: absolute; top: 100px;">
						<?php echo $attachment; ?>
					</div>
					<?php
				} else {
					if ( ! empty( $attachment ) ) {
						?>
						<div style="background:#f3f3f4; width:100%; float:none; position: absolute; top: 100px;">
							<div><?php echo $attachment; ?></div>
						</div>
						<?php
					} else {
						?>
						<div style="background:#f3f3f4; padding:80px; width:100%; float:none; position: absolute; top: 100px;">
							<div><?php echo $second_page_body_title_section; ?></div>
							<div style="text-align:left; color: #000; font-family:opensans-regular;">
								<?php echo $settings_second_page_body_copy; ?>
							</div>
							<div style="margin-top:80px; text-align:center;">
								<div style="font-family:opensans-bold;"><?php echo $adviser_detail_line_1; ?></div>
								<div style="font-family:museosansrounded-100;"><?php echo $settings_address ; ?></div>
								<div style="font-family:museosansrounded-100;"><?php echo $adviser_detail_line_3; ?></div>
							</div>
						</div>
						<?php
					}

				}
			}
			?>
		</div>
		<?php

		$content = ob_get_clean();

		return $content;
	}

	public function get_chevron_image() {
		$path    = trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/images/chevron.png';
		$file    = file_get_contents( $path );
		$encoded = base64_encode( $file );
		return 'data:image/png;base64,' . $encoded;
	}

	public function get_default_logo() {
		$field   = get_field( 'pdf_generator_default_logo', 'option' )['ID'];
		$path    = get_attached_file( $field );
		$file    = file_get_contents( $path );
		$encoded = base64_encode( $file );
		$image   = 'data:image/png;base64,' . $encoded;

		return $image;
	}

	/**
	 * Get MPDF Options
	 *
	 * Get options to configure mpdf.
	 *
	 * @return array
	 */
	public function get_mpdf_options() {
		$default_config      = ( new ConfigVariables() )->getDefaults();
		$default_font_dirs   = $default_config['fontDir'];
		$default_font_config = ( new FontVariables() )->getDefaults();
		$default_font_data   = $default_font_config['fontdata'];
		$custom_font_dirs    = array(
			trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/fonts',
		);
		$custom_font_data    = array(
			'opensans-regular'     => array( 'R' => 'OpenSans-Regular.ttf' ),
			'opensans-bold'        => array( 'R' => 'OpenSans-Bold.ttf' ),
			'museosansrounded-700' => array( 'R' => 'museosansrounded-700-webfont.ttf' ),
			'museosansrounded-100' => array( 'R' => 'museosansrounded-100-webfont.ttf' ),
		);

		$options = array(
			'fontDir'      => array_merge( $default_font_dirs, $custom_font_dirs ),
			'fontdata'     => array_merge( $default_font_data, $custom_font_data ),
			'mode'         => 'utf-8',
			'default_font' => 'opensans-regular',
			'format'       => array( 278, 215 ),
		);

		return $options;
	}

	/**
	 * Get Notch HTML
	 *
	 * Get the html for the color notch with the indicated color.
	 *
	 * @param string $notch_color hex color code.
	 *
	 * @return string html div of notch.
	 */
	public function get_notch_html() {
		$color = $this->get_colors()[0];
		return "<div style='position: fixed; right: 232px; top: 32px;'>
					<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' aria-hidden='true' width='20' height='20' style='-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);' preserveAspectRatio='xMidYMid meet' viewBox='0 0 8 8'>
						<path d='M2 0v8l4-4-4-4z' fill='{$color}'/>
					</svg>
				</div>";
	}

	/**
	 * Get Prepared SVG HTML
	 *
	 * Converts the SVG element into a div that contains an image of the base64 encoded svg.
	 *
	 * @param $svg
	 *
	 * @return string
	 */
	public function get_prepared_svg_html( $svg ) {
		$encoded_svg = base64_encode( $svg );
		return "<div style='position: fixed; width: 100%; height: 100%; top: 0; overflow: hidden;'>
		            <img src='data:image/svg;base64,{$encoded_svg}' style='width: 100%; height: 100%;' />
				</div>";
	}

	/**
	 * Get Logo HTML
	 *
	 * @return string
	 */
	public function get_logo_html() {
		$width  = $this->get_logo_dimensions()['width'];
		$height = $this->get_logo_dimensions()['height'];
		$logo   = $this->logo;

		return "<div style='position: fixed; width: {$width}px; height: {$height}px; background: white; right: 0px; top: 0px; border: 7px solid #FFFFFF;'>
					<table style='border-collapse: collapse; table-layout: fixed; width: 100%; height: {$height}px;'>
						<tr style='width: 100%; height: {$height}px;'>
							<td style='vertical-align: middle; padding: 5px; text-align: center; height: {$height}px;'>
								<img src='{$logo}'>
							</td>
						</tr>
					</table>
				</div>";

	}

	/**
	 * Get PDF Page Opening HTML
	 *
	 * HTML Header and Head styles for pages that removes the margin.
	 *
	 * @see $this->get_pdf_page_closing_html()
	 *
	 * @return string
	 */
	public function get_pdf_page_opening_html() {
		return '<html>
			<head>
				<style>
				@page {
					margin: 0px;
					background: #f3f3f4;
					-webkit-appearance: none;
				}
				</style>
			</head>
		<body>';
	}

	/**
	 * Get PDF Page Closing HTML
	 *
	 * Closing tags for body and html elements
	 *
	 * @see $this->get_pdf_page_opening_html()
	 *
	 * @return string
	 */
	public function get_pdf_page_closing_html() {
		return '</body></html>';
	}

	/**
	 * GETTERS AND SETTERS
	**/

	public function get_logo() {
		return $this->logo;
	}

	public function set_logo( string $encoded_logo ) {
		if ( empty( $encoded_logo ) ) {
			return $encoded_logo;
		}

		if ( fp_is_feature_active( 'real_image_logos' ) ) {
			$this->logo = $encoded_logo;

			if ( ! ( new Logo() )->is_base_image( $encoded_logo ) ) {
				$parts  = parse_url( $encoded_logo );
				$this->logo = $parts['path'];
			}
		} else {
			if ( base64_decode( $encoded_logo ) ) {
				$this->logo = $encoded_logo;
			} else {
				throw new \Exception( 'Logo must be base64 encoded.' );
			}
		}
	}

	public function set_svg_paths( array $paths ) {
		$this->svg_paths = $paths;
	}

	public function get_svg_paths() {
		return $this->svg_paths;
	}

	public function set_back_page_settings( array $settings ) {
		$this->back_page_settings = $settings;
	}

	public function get_back_page_settings() {
		return $this->back_page_settings;
	}

	/**
	 * @return array
	 */
	public function get_colors() {
		return $this->colors;
	}

	/**
	 * @param array $colors
	 *
	 * @see $this->colors_are_valid
	 *
	 * @return PDF
	 */
	public function set_colors( array $colors ) {
		$colors       = array_values( $colors ); // We don't want any keys
		$this->colors = $this->colors_are_valid( $colors ) ? $colors : $this->colors;
	}

	/**
	 * Colors Are Valid
	 *
	 * @param array $colors
	 *
	 * @return bool
	 */
	public function colors_are_valid( array $colors ) {
		$valid_type_reducer = function( $all_valid_type, $value ) {
			if ( is_string( $value ) ) {
				return $all_valid_type && strlen( $value ) === 7 && ctype_xdigit( substr( $value, 1 ) );
			} else {
				return false;
			}
		};
		$valid_type         = array_reduce( $colors, $valid_type_reducer, true );
		$valid_amount       = 4 === count( $colors );

		return $valid_amount && $valid_type;
	}

	public function set_post_id( $post_id ) {
		$this->post_id = $post_id;
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;
	}

	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * @return object
	 */
	public function get_logo_dimensions() {
		return $this->logo_dimensions;
	}

	/**
	 * @param array $logo_dimensions
	 *
	 * @return PDF
	 */
	public function set_logo_dimensions( array $logo_dimensions ) {
		$this->logo_dimensions = $logo_dimensions;
	}

	public function get_filename() {
		return $this->output_filename;
	}
}
