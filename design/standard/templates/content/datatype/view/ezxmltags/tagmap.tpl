{* TagMap template *}

{def $tagList=$top_tag|get_objects_by_tag($top_node)
	$size=array( $width, $height ) }

<script type="text/javascript">

{literal}
function initialize() {
	var myOptions = {
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var bounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var geocoder = new google.maps.Geocoder();
	var data=[];

{/literal}

{foreach $tagList as $tag}
	{set-block variable=$html}
		<h2>{$tag.keyword}</h2><ul>
		{foreach $tag.nodes as $node}
			<li><a href={$node.url_alias|ezurl}>{$node.name}</a></li>
		{/foreach}
		</ul>
	{/set-block}
	data.push( {ldelim}
		keyword:"{$tag.keyword}",
		info:'{$html|trim|addslashes|explode("\n")|implode("\\\n")}',
		point: new google.maps.LatLng ( {$tag.location.lat}, {$tag.location.lng} )
	{rdelim});
{/foreach}
	{literal}
	for( var i=0;  i < data.length;  i ++ ) {
		makeMarker( map, data[i] );
		bounds.extend(data[i].point);
		map.fitBounds(bounds);
	}
	{/literal}

{literal}
}

function makeMarker(map, data) {
	var marker = new google.maps.Marker({
		map: map,
		title: data.keyword,
		position: data.point
	});
	var infowindow = new google.maps.InfoWindow({
		content: data.info
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

$(document).ready( function() {
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initialize";
	document.body.appendChild(script);
});
{/literal}
</script>

<div id="map_canvas" style="width: {$size[0]}; height: {$size[1]}"></div>
	
{undef}

