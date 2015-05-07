AWStats Updater 1.3
===================

Update AWStats databases and/or generate HTML pages. By default, ```awstats-update``` updates the database (```--update```) and creates HTML pages (```--rebuild```) for the current month. You can create HTML pages for all historic data using ```--rebuild-all```.

Installation
------------
* Disable default AWStats updater by setting ```AWSTATS_ENABLE_BUILDSTATICPAGES="no"``` and ```AWSTATS_ENABLE_CRONTABS="no"``` in ```/etc/default/awstats```
* Remove maybe existing old HTML pages: ```rm -r /var/cache/awstats/awstats/```
* Create new target directories: ```mkdir -p /var/cache/awstats/www/{apache2,apache2-ssl,postfix}```
* Create ```/etc/cron.daily/awstats-update``` and ```/usr/local/sbin/awstats-update``` and make them executable
* Create a distinct config file for each virtual host, e.g. ```/etc/awstats/awstats.example.com.conf``` or ```/etc/awstats/awstats.example.net.conf``` (Tip: AWStats supports ```Include``` statements...)
* Consider using ```awstats.php``` and ```.htaccess```

License & Copyright
-------------------
Copyright (C) 2011-2015  Daniel Rudolf <http://www.daniel-rudolf.de/>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, version 3 of the License only.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the [GNU General Public License](https://www.gnu.org/licenses/gpl-3.0) for more details.
