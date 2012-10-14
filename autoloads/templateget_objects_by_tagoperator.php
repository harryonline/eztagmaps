<?php 

class TemplateGet_objects_by_tagOperator
{
	/*!
	 Constructor, staat standaard ingesteld op non-actief.
	*/
	function __construct()
	{
	}

	/*!
	 \return an array with the template operator name.
	*/
	function operatorList()
	{
		return array( 'get_objects_by_tag' );
	}

	/*!
	 \return true to tell the template engine that the parameter list exists per operator type,
	this is needed for operator classes that have multiple operators.
	*/
	function namedParameterPerOperator()
	{
		return true;
	}

	/*!
	 See eZTemplateOperator::namedParameterList
	*/
	function namedParameterList()
	{
		return array( 'get_objects_by_tag' => array( 'first_param' => array( 'type' => 'integer',
				'required' => false,
				'default' => '0' ),
				'second_param' => array( 'type' => 'integer',
						'required' => false,
						'default' => 0 ) ) );
	}


	/*!
	  Get the coordinates and list of related objects in subtree for all tags  
	*/
	function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
	{
		$topNode = eZContentObjectTreeNode::fetch( $namedParameters['first_param'] );
		$pathFilter = $topNode->PathString;
		$pathLen = strlen( $pathFilter);
		$subTags = eZTagsObject::subTreeByTagID( array(), $operatorValue );
		$geoCode = new eZGeoCode;
		$operatorValue = array();
		foreach( $subTags as $tag ){
			// Get coordinates of tag
			$location = $geoCode->getLatLng( $tag->Keyword );
			if( is_object($location )) {
				//Find objects with tag
				$objects = $tag->getRelatedObjects();
				$nodeList = array();
				foreach( $objects as $i => $obj ) {
					$node = $obj->attribute( 'main_node');
					if( strncmp( $node->PathString,
							$pathFilter, $pathLen ) == 0 ) {
						$nodeList[] = $node;
					}
				}
				if( count( $nodeList) > 0 ) {
					$operatorValue[$tag->ID]['keyword'] = $tag->Keyword;
					$operatorValue[$tag->ID]['location'] = array(
							'lat' => $location->lat,
							'lng' => $location->lng );
					$operatorValue[$tag->ID]['nodes'] = $nodeList;
				}
			}
		}
	}
}

?>