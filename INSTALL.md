# eZ TagMaps extension installation instructions

## Requirements

   * eZ Publish 4.3+
   * eZ JSCore

## Required extensions

   * eZ Tags

## Installation

### Unpack/unzip

Unpack the downloaded package into the `extension` directory of your eZ Publish installation.

### Create SQL tables in your eZ Publish database

Extension requires an additional tables to be added to your database. Use the following command from your eZ Publish
root folder, replacing `user`, `password`, `host` and `database` with correct values and removing double quotes

    mysql -u "user" -p"password" -h"host" "database" < extension/eztagmaps/sql/mysql/schema.sql

### Set your region

In `extension/eztagmaps/settings/site.ini.append.php`, change the `SiteSettings` `GeoRegion` to your region code,
specified as a ccTLD ("top-level domain") two-character value. This parameter will only influence,
not fully restrict, results from the geocoder. 

### Activate extension

Activate the extension by using the admin interface ( Setup -> Extensions ) or by
prepending `eztags` to `ActiveExtensions[]` in `settings/override/site.ini.append.php`:

    [ExtensionSettings]
    ActiveExtensions[]=eztagmaps

### Regenerate autoload array

Run the following from your eZ Publish root folder

    php bin/php/ezpgenerateautoloads.php --extension

Or go to Setup -> Extensions and click the "Regenerate autoload arrays" button

### Clear caches

Clear all caches (from admin 'Setup' tab or from command line).
