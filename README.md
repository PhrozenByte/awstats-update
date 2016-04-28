AWStats Updater 2.2
===================

Update AWStats database and/or generate static HTML pages, either of the specified or all known hosts. By default, `awstats-update` updates the database (`--update`) and creates HTML pages (`--rebuild`) for the current month. You can create HTML pages for all historic data using `--rebuild-all`.

Usage
-----
```shell
awstats-update [OPTION]...
awstats-update [OPTION]... CONFIG...
```
`CONFIG` can either be:
* a absolute path to a config file
* a relative path to a config file, starting from `/etc/awstats/`
* a config spec in the form `[ HOST_TYPE "/" ] HOST`

`awstats-update` supports the following options:
* Help options:
  * `-h`, `--help`: display this help and exit
  * `--version`: output version information and exit
* Application options:
  * `--[no-]update`: [don't] update AWStats database
  * `--[no-]rebuild`: [don't] generate static HTML pages (current month only)
  * `-a`, `--rebuild-all`: generate static HTML pages (all historic data)
  * `-o`, `--overwrite`: re-create existing HTML pages (with `--rebuild-all`)
  * `--format=FORMAT`: output HTML pages according to `FORMAT`: `plain`, or `gzip`. You also pass both comma separated. default: `plain`
  * `--no-config[=CONFIG]`: don't skip domains without a config; use `CONFIG`, otherwise use default config (with `--rebuild-all`)
  * `--www-path=PATH`: where to store the generated HTML pages? default: `/var/cache/awstats/www`
  * `-v`, `--verbose`: increase verbosity
  * `-q`, `--quiet`: decrease verbosity

Installation
------------
* Disable default AWStats updater by setting `AWSTATS_ENABLE_BUILDSTATICPAGES="no"` and `AWSTATS_ENABLE_CRONTABS="no"` in `/etc/default/awstats`
* Remove maybe existing old HTML pages: `rm -r /var/cache/awstats/awstats/`
* Create new target directories: `mkdir -p /var/cache/awstats/www/{apache2,apache2-ssl,postfix}`
* Create `/etc/cron.daily/awstats-update` and `/usr/local/sbin/awstats-update` and make them executable
* Create a distinct config file for each virtual host, e.g. `/etc/awstats/awstats.example.com.conf` or `/etc/awstats/awstats.example.net.conf` (Tip: AWStats supports `Include` statements...)
* Consider using `/var/cache/awstats/www/awstats.php` and `/var/cache/awstats/www/.htaccess`

License & Copyright
-------------------
Copyright (C) 2011-2016  Daniel Rudolf <http://www.daniel-rudolf.de/>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, version 3 of the License only.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the [GNU General Public License](LICENSE) for more details.
