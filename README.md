# The Missing NPR Podcasts -- The php port

This is very much a straight-up php port of the super-useful [The
Missing NPR Podcasts](https://github.com/jetheis/MissingNPRPodcasts).

Basically, it queries the [NPR](http://www.npr.org/)
[API](http://www.npr.org/api/index) to create podcast RSS feeds of
[Morning Edition](http://www.npr.org/programs/morning-edition/), [All
Things Considered](http://www.npr.org/programs/all-things-considered/),
[Weekend Edition
Saturday](http://www.npr.org/programs/weekend-edition-saturday/), and
[Weekend Edition
Sunday](http://www.npr.org/programs/weekend-edition-sunday/).

As stated many times throughout this document, and throughout the [NPR
API Terms of Use](https://www.npr.org/api/apiterms.php), this software
and its output are **for personal use only**. I am in no way affiliated
with NPR or any NPR program.  
Please read the [NPR API Terms of
Use](https://www.npr.org/api/apiterms.php) before using this software.

## Disclaimer

This is not an official podcast! NPR makes a good portion of its money
from licensing programs like Morning Edition and All Things Considered
to member stations. Do not let the existence of this code prevent you
from both listening to these programs from your local NPR member station
and contributing to that station.

Personally, i use this because i am currently living abroad, which makes
it very difficult to listen to AM/FM radio, or even streams thereof
because of the time difference.  
If you don't have a "home" NPR member station, feel free to donate to
the ones i support:

* [WAMU (Washington DC)](http://wamu.org)
* [WBUR (Boston)](http://www.wbur.org)

## Acceptable Use

Please read the [NPR API Terms of Use](https://www.npr.org/api/apiterms.php)
before using this software. As stated in those terms, API content is for
personal use only. While this software can easily be deployed in a publicly
accessible way, it is up to the user to comply with the API's terms and be the
sole, noncommercial user of the content. Anyone using this software must do so
using his own NPR API key, making any actions taken by the software or the users
of the software the responsibility of the registered API key holder.

### Deploying this Site

Running this site is very easy: just upload the files on a web server
(or host one locally), and point your browser to the root of that folder.  
All you need is a standard [php](http://php.net) installation... Only
standard libraries are used such as
[SimpleXML](http://php.net/manual/en/class.domdocument.php) and
[DOMDoc](http://php.net/manual/en/class.domdocument.php) (though that
last one is optional)

* Note: if you're going to use this on a Mac, there is a curl bug that
breaks SSL, so you need to change line 19 as follows:
```php
$MainVar['NPRQueryURL'] = 'http://api.npr.org/query?'; // On Mac, must use http because of OpenSSL+curl problem
```

* Note: [DOMDoc](http://php.net/manual/en/class.domdocument.php) is
optional, if you don't want to (or can't) use it, simply comment lines
99-102, and un-comment line 104 as follows (it'll also save you about 4KB or RAM at execution
time too), and it should not affect anything negatively:
```php
		//$xmlClean = new DOMDocument(); // seems like a huge memory cost (4184 bytes), just to make it 'pretty'; used DOMDoc: http://php.net/manual/en/class.domdocument.php
		//$xmlClean->loadXML($xml->asXML()); 
		//$xmlClean->formatOutput = true; 
		//echo str_replace('itunesZXZX','itunes:',$xmlClean->saveXML())/* .PHP_EOL.memory_get_usage().PHP_EOL */;
		/*  end DOMDoc; it's really useless, just vanity formatting... to disable, comment 4 lines above, and un-comment 1 line below:  */
		echo str_replace('itunesZXZX','itunes:',$xml->asXML())/* .PHP_EOL.memory_get_usage().PHP_EOL */;
```

### Super-Useless Info

The original app used JSON-formatted data from [NPR](http://www.npr.org/)...  
However, since they also offer XML, i chose to use that instead. Also,
this is pretty much procedural-style php because, frankly, i saw no
reason to consume twice the RAM just to make it look more modern and
OO-ey...

## License

All media content is property of
[NPR: National Public Radio](https://www.npr.org/).

Information about the licensing of the programs can be found on the
[Morning Edition weg page](http://www.npr.org/programs/morning-edition/), as well
as the
[All Things Considered web page](https://www.npr.org/programs/all-things-considered/).

The original source code contained in this project is released under the MIT License:

    Copyright (C) 2012 Jimmy Theis
    
    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to
    deal in the Software without restriction, including without limitation the
    rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
    sell copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.

The [php](http://php.net) code contained in this project is released under the MIT License:

    Copyright (C) 2017 Peter Zieseniss
    
    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to
    deal in the Software without restriction, including without limitation the
    rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
    sell copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.

The site icon/logo is a derivative work of [Glyphicons](http://glyphicons.com/),
which I purchased a license for, so it cannot be released under any permissible
license. If you like the logo and would like to make a similar one, I highly
recommend buying a [Glyphicons PRO](http://glyphicons.com/glyphicons-licenses/)
license.
