<?php

class eZGeoCode
{
	var $url;

	function __construct()
	{
		$this->url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&';
		$ini = eZINI::instance( 'site.ini' );
		if( $ini->hasVariable( 'SiteSettings', 'GeoRegion' )) {
			$this->url .= 'region='. $ini->variable('SiteSettings', 'GeoRegion');
		}
		$this->url .= '&address=';
	}
	
	/*
	 * getLatLng for given address
	 * TODO: store results from geocode reqeust locally, to avoid too many requests
	 * @parameter string $address: address for which latitude and longitude are requested
	 * @return: object with lat, lng attributes 
	 */
	function getLatLng( $address )
	{
		$result = json_decode( file_get_contents( $this->url . urlencode( $address )));
		if( $result->status == 'OK' ) {
			return $result->results[0]->geometry->location;
		}			
		return false;
	}

}
?>