eztagmaps
=========

Create a map in eZ Publish with links to object that have a geographical location as tag.
This extension builds on the eZTags extension.

1. In eZTags, create a tag 'locations' and remember the ID of this tag.
2. Mark objects with a tag describing the location, e.g. address, city. These tags should be placed under the 'locations' tag.
3. In an XML field, insert the custom tag 'tagmap', specify the top_node to possibly limit the objects to a certain subtree (root node if no limitations), and the top_tag, the ID of the 'locations' tag. Optionally, adapt the width and/or height settings.

