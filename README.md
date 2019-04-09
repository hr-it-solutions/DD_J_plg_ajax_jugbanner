# J_plg_ajax_dd_jugbanner
Generates a XML from com_banners for the JUG Websites

[![GPL Licence](https://badges.frapsoft.com/os/gpl/gpl.png?v=102)](https://opensource.org/licenses/GPL-2.0/)

### Features
- Generates a XML from com_banners. Accessible via index.php?option=com_ajax&format=xml&plugin=BannerList&group=ajax
- Generates hash from the xml. Accessible via index.php?option=com_ajax&format=raw&plugin=BannerHash&group=ajax

### XML Structure

```xml
<?xml version="1.0"?>
<banners>
<banner>
<image>...</image>
<link>...</link>
<verify>...</verify>
</banner>
</banners>
```

# System requirements
Joomla 3.9 +                                                                                <br>
PHP 5.6.13 or newer is recommended.

<br>
Author: HR-IT-Solutions GmbH Florian Häusler https://www.hr-it-solution.com                 <br>
Copyright: (C) 2018 - 2019 HR-IT-Solutions GmbH >> Adapted from Joomla! plg_search_content  <br>
http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
