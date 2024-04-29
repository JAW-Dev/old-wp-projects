<div class="wrap">
	<h2>CFP Report</h2>

	<form action="<?php echo esc_url( home_url( '?page=ce_report' ) ); ?>" method="GET">
		<input type="hidden" name="download_report" value="true" />
		<p>
		<label>Start Date<br/>
		<input type="date" name="start_date">
		</label>
		</p>

		<p>
			<label>End Date<br/>
			<input type="date" name="end_date">
			</label>
		</p>

		<?php submit_button( 'Download Report' ); ?>
	</form>
	<ul>
		<?php
		$start = $month = strtotime( 'April 1, 2015' );
		$end   = strtotime( '-1 month' );

		while ( $month < $end ) :
			$y        = date( 'Y', $month );
			$m        = date( 'm', $month );
			$last_day = date( 'd', mktime( 0, 0, 0, $month + 1, 0, $y ) );
			$url      = add_query_arg(
				array(
					'download_report' => 'true',
					'start_date'      => "$y-$m-01",
					'end_date'        => "$y-$m-$last_day",
				),
				home_url( '?page=ce_report' )
			);

			?>
			<li>
				<form action="<?php echo esc_url( home_url( '?page=ce_report' ) ); ?>" method="GET">
					<input type="hidden" name="download_report" value="true" />
					<input type="hidden" name="start_date" value="<?php echo esc_attr( "$y-$m-01" ); ?>" />
					<input type="hidden" name="end_date" value="<?php echo esc_attr( "$y-$m-$last_day" ); ?>" />
					<input type="submit" name="submit" class="button button-primary" value="<?php echo esc_html( date( 'F Y', $month ) ); ?>" style="color: #007cba; background-color: transparent; padding: 0; border: none; text-decoration: underline; min-height: 0; line-height: 1;">
				</form>
			</li>
			<?php $month = strtotime( '+1 month', $month ); ?>
		<?php endwhile; ?>
	</ul>
</div>