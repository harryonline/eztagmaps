<?php

class eZGeoCode
{
	var $url;
	var $region='';

	function __construct()
	{
		$this->url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&';
		$ini = eZINI::instance( 'site.ini' );
		if( $ini->hasVariable( 'SiteSettings', 'GeoRegion' )) {
			$this->region = $ini->variable('SiteSettings', 'GeoRegion');
			$this->url .= 'region='. urlencode( $this->region );
		}
		$this->url .= '&address=';
	}

	/*
	 * getLatLng for given address
	 * @parameter string $address: address for which latitude and longitude are requested
	 * @return: object with lat, lng attributes 
	 */
	function getLatLng( $address )
	{
		$db = eZDB::instance();
		$rows = $db->arrayQuery( sprintf( 'SELECT lat, lng FROM ezgeocode WHERE address="%s" AND region="%s"', 
					$db->escapeString( $address ), $db->escapeString( $this->region ) ));
		if( count( $rows ) > 0 ) {
			// Convert to object
			return json_decode( json_encode ($rows[0])); 
		} else {
			// Get information from call to google geocode api
			$geocode = json_decode( file_get_contents( $this->url . urlencode( $address )));
			if( $geocode->status == 'OK' ) {
				$location = $geocode->results[0]->geometry->location;
				$db->query( sprintf( 'INSERT INTO ezgeocode (address, region, lat, lng ) VALUES ( "%s", "%s", %.8f, %.8f )',
							$db->escapeString( $address ), $db->escapeString( $this->region ), $location->lat, $location->lng ));
				return $location;
			}			
			return false;
		}
	}

}
?>