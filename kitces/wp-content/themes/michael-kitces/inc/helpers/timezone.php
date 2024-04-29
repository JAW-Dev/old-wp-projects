<?php

function kitces_timezone( $date, $format = 'n/d/Y' ) {
	$set_date = new \DateTime( $date, new \DateTimeZone( 'America/New_York' ) );
	return $set_date->format( $format );
}