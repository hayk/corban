Corban, dear! — PHP debug output library. Easy to install and use, this library greatly simplifies the process of debugging PHP applications.

## Usage ##
The library provides several functions that make application debugging easier:
  * `cd()`, `cdx()` — outputs information about a variable;
  * `cm()`, `cmx()` — outputs a debug message;
  * `ci()`, `cs()` — timer initialization and output.

Simple example:
```php
include_once('corban.lib.php');
cd ($_SERVER);
```

### corban.lib.php inclusion ###
It can be included into PHP in several ways: directly in the script using the `require` or `include` statements, or `auto_prepend_file` directive, which can be set directly in `php.ini` or in `.htaccess` file (recommended). Before including the file you can define library configuration constants.

An example of inclusion directly in the script by using the `require`, or `include` statements:
```php
define('CORBAN_OUTPUT', 4);
include_once('corban.lib.php');
```

An example of inclusion using `auto_prepend_file` directive in `.htaccess` file:
```
SetEnv  CORBAN_OUTPUT 4
php_value auto_prepend_file /path/to/corban.lib.php
```

**Note:** You can use special value `none` to disable auto-prepending if necessary.
```
php_value auto_prepend_file none
```

### Function reference ###
`cd($val, $msg=null, $die=false)` — Outputs a variable value (including objects and resources) with comments. Parameters:
  * `$val` — The variable to be output.
  * `$msg` — Displayed variable name and comment in the «varname#comment» format, where «#» — delimiter (optional, `null` by default).
  * `$die` — Determines whether to abort the script execution after debug output (optional, `false` by default).

Example:
```php
$a = array (1, 2, 3);
cd($a, 'a#Array a');
```

Output:
```
Array a
  $a  [0] = 1
  $a  [1] = 2
  $a  [2] = 3
```

`cdx($val, $msg=null)` — Similar to the `cd()` function call with `$die` parameter set to `true`.

`cm($msg, $param=null, $die=false)` — Outputs a debug message and a value of a variable without any information about it. Parameters:
  * `$msg` — Debug message.
  * `$param` — Variable (optional, `null` by default).
  * `$die` — Determines whether to abort the script execution after debug output (optional, `false` by default).

`cmx($msg, $param=null)` — Similar to the `cm()` function call with `$die` parameter set to `true`.

`ci($name=0)` — Initializes timer with the specified name. Used together with `cs()`. Parameters:
  * `$name` — Timer name (optional). If not set, default timer is initialized.

`cs($name=0, $comment=null)` — Displays the value of the timer that was initialized with the specified name `$name`, or the value of the default timer. Used together with `ci()`. Parameters:
  * `$name` — Timer name (optional).
  * `$comment` — Comment text (optional).

Example:
```php
ci ('a');
sleep(10);
cs ('a', 'Script execution time:');
```

Output:
```
Script execution time:
10.060
```

`corban_fix_output()` — Places the debug output before the closing `</body>` tag, thereby preventing `html` [devalidation](#Valid_html.md) as a result of library usage. Should be called before any output is produced by the script.

`corban_shutup()` — Forces the debug output to stop. Useful when it is necessary to cease output debug information. For example, to prevent the breaking of outputted data structure, such as output data in the `json` format or binary data.

### Configuration constants ###
`CORBAN_OUTPUT`, by default 2 (`CORBAN_OUTPUT_BUFFER`). Defines the type of debug output. Can take the following values:
  * 1 (`CORBAN_OUTPUT_INLINE`) to output directly as the function cd() or other functions are called,
  * 2 (`CORBAN_OUTPUT_BUFFER`) for buffered output at the end of the script execution,
  * 3 (`CORBAN_OUTPUT_CONSOLE`) to output in the pop-up window,
  * 4 (`CORBAN_OUTPUT_FILE`) for output to a file (you will also need to define the `CORBAN_FILE_PATH` constant and set the write permissions).

`CORBAN_FORMAT`, by default 1 (`CORBAN_FORMAT_HTML`). Defines the format of the debug output. Can take the following values:
  * 1 (`CORBAN_FORMAT_HTML`) for output in the html format,
  * 2 (`CORBAN_FORMAT_TEXT`) for output in plain text.

`CORBAN_FIX_OUTPUT`, by default not defined. Prevents `html` [devalidation](#valid-html) as a result of library usage. Can take any value.

`CORBAN_FILE_PATH`, by default not defined. Specifies the path to the `corban.log`, to which the debug output will be written. When this constant is not defined, the library will attempt to write to the file `corban.log` located in the folder with the script currently executed. It is also necessary to set the write permissions on the file `corban.log`.

`CORBAN_SHOW_CLASS_METHOD`, by default not defined. Defines whether object methods will be displayed when dumping an object.

`CORBAN_EGG`, by default not defined. Defines the format of the prefix (`CORBAN_PREFIX`): when this constant is defined, the prefix contains “Corban, dear!”.

`CORBAN_SILENCIO`, by default not defined. Specifies whether to suppress display of the prefix (`CORBAN_PREFIX`) before debug output or not.

**Note:** You can define constants through the `.htaccess` file set by the `Apache` directive `SetEnv`. Example:
```
SetEnv  CORBAN_OUTPUT 4
```

## Valid html ##
By default, in the buffered output or output in a popup window modes, to prevent distortions in the design, all debug messages are outputted after the closing `</body>` tag. This leads to `html` that is no longer valid. To avoid this, it is necessary either to define the constant `CORBAN_FIX_OUTPUT` before library inclusion, or to call the `corban_fix_output()` after inclusion, but always before any output is produced. After that, all debug messages will be outputted before the closing `</body>` tag.

## Creation history ##
Development of the “Corban, dear!” library began in 1999 in the Softerra company, in its early days. In 2002 it was released under the terms of GNU GPL as a part of [PHP Developer Library](http://www.softerra.com/products_php-library.htm). The library was supported and developed outside the company later on.

«Corban» — misspelling of the name of Korben Dallas (the character of The Fifth Element movie).

