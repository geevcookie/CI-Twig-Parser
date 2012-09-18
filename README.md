CI-Twig-Parser
==============

Replace the default CodeIgniter parser with Twig.

Installation
------------
+ Download Twig @ http://twig.sensiolabs.org/
+ Copy the Twig directory to **application/third_party**
+ Copy **MY_Parser.php** from CI-Twig-Parser to **application/libraries**

And that should be it. :)

Usage
-----
Because CI-Twig-Parser simply extends the default CI parser you can use it like you would the standard parser.

```$this->parser->parse(<view>, <data>, <return>);```

Where **view** is the view file you would like to parse, **data** is the data you would like to send through to the view and **return** is either *true* or *false*. If set to *true* the parser will return the result as a string. If set to *false* or omitted the parser will automatically append the result to the output.

Compatibility
-------------
CI-Twig-Parser is compatible with both the HMVC extension and Phil Sturgeon's epic Templating library. Links below:
+ HMVC: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home
+ Template: http://philsturgeon.co.uk/demos/codeigniter-template/user_guide/

Bonus
-----
CI-Twig-Parser also makes CI functions available to you in the views.

By default all functions provided by libraries and helpers in the **autoload.php** config file are available.

If you have loaded more helpers or libraries in your controller simply run the below command to make the functions available to Twig.
```
$this->parser->checkFunctions();
```
This will check for all registered functions again and register them all as Twig functions.

If you would like to make a specific function available do this:
```
$this->load->helper('url');

$this->parser->addFunction('anchor');
```
In your view you can now use the **anchor** like this:
```
{{anchor('users/dashboard', 'Dashboard')}}
```
You can also specify an alias for further compatibility:
```
$this->load->helper('url');

$this->parser->addFunction('anchor', 'hyper');
```
The function can then be refered to by the alias like so:
```
{{hyper('users/dashboard', 'Dashboard')}}
```

Notes
-----
This is my first library for CI so please be gentle. :) Also, let me know if you happen to run across any bugs or have any feature requests.