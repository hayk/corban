<?php if (!defined('CORBAN_INCLUDED')):
	//
	// +----------------------------------------------------------------------+
	// | Corban, dear! - PHP debug output library                             |
	// +----------------------------------------------------------------------+
	// | Copyright (c) 1999-2005 Softerra, LLC <phplib@softerra.com>          |
	// | Copyright (c) 2008-2011 Dmitry Bobeyko <dead.star.fish@gmail.com>    |
	// | Copyright (c) 2005-2011 Hayk Chamyan <hamshen@gmail.com>             |
	// +----------------------------------------------------------------------+
	// | This library is free software; you can redistribute it and/or modify |
	// | it  under  the  terms  of  the  GNU Lesser General Public License as |
	// | published by the Free Software Foundation; either version 2.1 of the |
	// | License, or (at your option) any later version.                      |
	// |                                                                      |
	// | This  library is distributed in the hope that it will be useful, but |
	// | WITHOUT   ANY  WARRANTY;  without  even  the  implied  warranty  of  |
	// | MERCHANTABILITY  or  FITNESS  FOR A PARTICULAR PURPOSE.  See the GNU |
	// | Lesser General Public License for more details.                      |
	// +----------------------------------------------------------------------+
	//
	// $Id: corban.lib.php 98 2013-01-03 16:04:50Z hayk $
	//

	/**
	 *
	 * Corban, dear! - PHP debug output library.
	 *
	 * @module		Corban
	 * @author		Softerra <phplib@softerra.com>
	 * @author		Dmitry Bobeyko <dead.star.fish@gmail.com>
	 * @author		Hayk Chamyan <hamshen@gmail.com>
	 * @copyright	(c) 2005-2011 Hayk Chamyan
	 * @copyright	(c) 2008-2011 Dmitry Bobeyko
	 * @copyright	(c) 1999-2005 Softerra, LLC
	 * @link		http://500plus.org/corban/ [Corban, dear!]
	 * @version		1.4.7
	 * @access		public
	 * @since		PHP 4.3.0
	 */

	define ('CORBAN_OUTPUT_INLINE', 1);
	define ('CORBAN_OUTPUT_BUFFER', 2);
	define ('CORBAN_OUTPUT_CONSOLE', 3);
	define ('CORBAN_OUTPUT_FILE', 4);

	define ('CORBAN_FORMAT_HTML', 1);
	define ('CORBAN_FORMAT_TEXT', 2);

	if (!defined('CORBAN_OUTPUT'))
	{
		if (getenv('CORBAN_OUTPUT'))
		{
			define ('CORBAN_OUTPUT', getenv('CORBAN_OUTPUT'));
		}
		else
		{
			define ('CORBAN_OUTPUT', CORBAN_OUTPUT_BUFFER);
		}
	}

	if (!defined('CORBAN_FORMAT'))
	{
		if (CORBAN_OUTPUT == CORBAN_OUTPUT_FILE || PHP_SAPI == 'cli')
		{
			define ('CORBAN_FORMAT', CORBAN_FORMAT_TEXT);
		}
		else
		{
			define ('CORBAN_FORMAT', CORBAN_FORMAT_HTML);
		}
	}

	if (!defined('CORBAN_FILE_PATH'))
	{
		if (getenv('CORBAN_FILE_PATH'))
		{
			define ('CORBAN_FILE_PATH', getenv('CORBAN_FILE_PATH'));
		}
	}

	if (CORBAN_FORMAT == CORBAN_FORMAT_TEXT)
	{
		define ('CORBAN_BR', PHP_EOL);
		define ('CORBAN_HR', PHP_EOL.'------------------------------------------------------------------------'.PHP_EOL);
		define ('CORBAN_NBSP', ' ');
		define ('BOLDTEXT', '_%s_');
		define ('CORBAN_MESSAGE_BEGIN', '');
		define ('CORBAN_MESSAGE_CLOSE', PHP_EOL);
		define ('CORBAN_STYLE', '');
	}
	else
	{
		define ('CORBAN_BR', '<br />');
		define ('CORBAN_HR', '<hr />');
		define ('CORBAN_NBSP', '&nbsp;');
		define ('BOLDTEXT', '<strong>%s</strong>');

		define ('TEXTBLOCK', '<span class="%s">%s</span>');
		define ('CORBAN_MESSAGE_BEGIN', '<div class="corban">');
		define ('CORBAN_MESSAGE_CLOSE', '</div>');

		define ('CLASS_CORBAN', '.corban{font-family:Arial;font-size:11px;margin:4px;padding:4px;background:#fff;text-align:left;}');
		define ('CLASS_PREFIX', '.corban .prefix{color:#999;}');
		define ('CLASS_COMMENT', '.corban .comment{font-size:11px;font-weight:bold;color:#699;}');
		define ('CLASS_DIE', '.corban .die{font-size:8px;font-weight:bold;color:#f66;}');
		define ('CLASS_EMPTY', '.corban .empty{font-weight:bold;color:#666;}');
		define ('CLASS_UNK', '.corban .unknown{font-weight:bold;color:#c96;}');
		define ('CLASS_BOOL', '.corban .boolean{color:#393;}');
		define ('CLASS_INT', '.corban .integer{color:#d33;}');
		define ('CLASS_DBL', '.corban .double{color:#d3c;}');
		define ('CLASS_STR', '.corban .string{color:#33f;background:#f0f0f0;}');
		define ('CLASS_RES', '.corban .resource{color:#963;}');
		define ('CLASS_ARR', '.corban .array{color:#33c;}');
		define ('CLASS_CLS', '.corban .class{color:#bbb;}');
		define ('CLASS_FUN', '.corban .method{color:#b6c;}');
		define ('CLASS_PROP', '.corban .property{color:#339;}');
		define ('CORBAN_STYLE', '<style type="text/css">' . CLASS_CORBAN . CLASS_PREFIX . CLASS_COMMENT . CLASS_DIE . CLASS_EMPTY . CLASS_UNK . CLASS_BOOL . CLASS_INT . CLASS_DBL . CLASS_STR . CLASS_RES . CLASS_ARR . CLASS_CLS . CLASS_FUN . CLASS_PROP . '</style>');

	}

	switch (CORBAN_OUTPUT)
	{
		case CORBAN_OUTPUT_CONSOLE:
			define ('CORBAN_CONSOLE_SCRIPT', '<script type="text/javascript">if(self.name==""){var title="Console";}else{var title="Console_"+self.name;}_corban_console=window.open("",title.value,"width=680,height=600,resizable,scrollbars=yes");_corban_console.document.write("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">\n<head>\n<title>Corban Output Console<\/title>\n%s\n<\/head>\n<body>\n\n<h1>Corban Output Console<\/h1>\n%s\n<\/body>\n<\/html>\n");_corban_console.document.close();</script>');
			break;

		case CORBAN_OUTPUT_FILE:
			if (defined('CORBAN_FILE_PATH'))
			{
				if (is_dir(CORBAN_FILE_PATH))
				{
					define ('CORBAN_FILE_NAME', rtrim(CORBAN_FILE_PATH, '/') . '/corban.log');
				}
				else
				{
					define ('CORBAN_FILE_NAME', CORBAN_FILE_PATH);
				}
			}
			else
			{
				define ('CORBAN_FILE_NAME', 'corban.log');
			}
			break;
	}

	define ('CORBAN_DIE_SCREAM', CORBAN_BR . 'CORBAN MUST DIE!' . CORBAN_BR);
	define ('CORBAN_SEPARATOR', CORBAN_NBSP . '=' . CORBAN_NBSP);

	if ((defined('CORBAN_EGG') && CORBAN_EGG) || getenv('CORBAN_EGG'))
	{
		define ('CORBAN_PREFIX', 'Corban, dear! [%s]: [%s][%s]');
	}
	else
	{
		define ('CORBAN_PREFIX', '[%s]: [%s][%s]');
	}

	if (!defined('CORBAN_SHOW_CLASS_METHOD') && getenv('CORBAN_SHOW_CLASS_METHOD'))
	{
		define ('CORBAN_SHOW_CLASS_METHOD', getenv('CORBAN_SHOW_CLASS_METHOD'));
	}

	$_corban_blabla = !((defined('CORBAN_SILENCIO') && CORBAN_SILENCIO) || getenv('CORBAN_SILENCIO'));

	$_corban_counter = 0;

	function cd($val, $msg=null, $die=false)
	{
		$msg = explode('#', $msg, 2);
		$name = strlen($msg[0]) ? $msg[0] : null;
		$comment = count($msg) > 1 ? $msg[1] : null;

		if (strlen($name) && $name[0] == '@')
		{
			if (strlen($comment))
			{
				$comment .= CORBAN_NBSP;
			}
			$comment .= 'function ' . substr($name, 1) . '() returns';
			$name = null;
		}

		_corban_dear($val, $name, $comment, $die);
	} // end function cd

	function cdx($val, $msg=null)
	{
		cd($val, $msg, true);
	} // end function cdx

	function cm($msg, $param=null, $die=false)
	{
		_corban_dear($param, null, $msg, $die, null, true);
	} // end function cm

	function cmx($msg, $param=null)
	{
		cm($msg, $param, true);
	} // end function cmx

	function ci($name=0)
	{
		_corban_inittimer($name);
	} // end function ci

	function cs($name=0, $comment=null)
	{
		_corban_showtimer($name, $comment);
	} // end function cs

	function corban_shutup()
	{
		if (!defined('CORBAN_SUPPRESS_OUTPUT'))
		{
			define ('CORBAN_SUPPRESS_OUTPUT', 1);
		}
	} // end function corban_shutup

	function corban_fix_output()
	{
		if (!defined('CORBAN_FIX_OUTPUT'))
		{
			define('CORBAN_FIX_OUTPUT', 1);
		}
		_corban_fix_output_init();
	} // end function corban_fix_output

	function _corban_dear($val, $name=null, $comment=null, $die=false, $internal=null, $eatprefix=false)
	{
		global $_corban_counter, $_corban_blabla;

		$type = gettype($val);

		if (is_null($internal))
		{
			$_corban_counter++;

			if (function_exists('debug_backtrace'))
			{
				$dba = debug_backtrace();
				foreach ($dba as $call)
				{
					if ($call['file'] != __FILE__)
					{
						$file = $call['file'];
						$line = $call['line'];
						$function = $call['function'];
						break;
					}
				}
			}

			if ($_corban_blabla)
			{
				$corban_message = _corban_format(sprintf(CORBAN_PREFIX, _corban_bold($_corban_counter), $file, _corban_bold($line)), 'prefix') . CORBAN_BR;
			}
			else
			{
				$corban_message = '';
			}

			if ($comment)
			{
				$corban_message .= _corban_format($comment, 'comment') . CORBAN_BR;
			}

			_corban_echo(CORBAN_MESSAGE_BEGIN);
			_corban_echo($corban_message);

			$corban_prefix = CORBAN_NBSP . CORBAN_NBSP;

			if ($name)
			{
				$corban_prefix .= _corban_bold('$' . $name) . CORBAN_NBSP;
			}
			elseif (!isset($val))
			{
				$corban_prefix .= _corban_bold('VARIABLE') . CORBAN_NBSP;
			}
			else
			{
				$corban_prefix .= _corban_bold(strtoupper($type), 'bold') . CORBAN_NBSP;
				if ($type == 'string')
					$corban_prefix .= '[' . strlen($val) . ']' . CORBAN_NBSP;
			}

			if ($eatprefix) $corban_prefix = '';
		}
		else
		{
			$corban_prefix = $internal;
		}

		$separator = $eatprefix ? '' : CORBAN_SEPARATOR;

		switch ($type)
		{
			case 'boolean':
				$val = $val ? 'true' : 'false';

			case 'integer':
			case 'double':
				_corban_scalar(strval($val), $type, $corban_prefix . $separator);
				break;

			case 'string':
				_corban_scalar(_corban_string($val), 'string', $corban_prefix . $separator);
				break;

			case 'array':
				_corban_array($val , $corban_prefix);
				break;

			case 'object':
				_corban_object($val, $corban_prefix);
				break;

			case 'resource':
				_corban_resource($val, $corban_prefix);
				break;

			case 'NULL':
				if (!$eatprefix) _corban_empty('(NULL)', $corban_prefix);
				break;

			default:
				_corban_scalar(_corban_string(strval($val)), 'unknown', $corban_prefix . $separator);
				break;
		}

		if (is_null($internal))
		{
			_corban_echo(CORBAN_MESSAGE_CLOSE);
		}

		if ($die)
		{
			_corban_die($die);
		}
	} // end function _corban_dear

	function _corban_string($str)
	{
		return CORBAN_FORMAT == CORBAN_FORMAT_HTML ? htmlspecialchars($str) : $str;
	} // end function _corban_string

	function _corban_die($die)
	{
		global $_corban_blabla;

		if (!is_string($die))
		{
			$die = ($_corban_blabla) ? CORBAN_DIE_SCREAM : '';
		}

		$die = _corban_format($die, 'die');

		_corban_echo(CORBAN_MESSAGE_BEGIN);
		_corban_echo($die);
		_corban_echo(CORBAN_MESSAGE_CLOSE);

		die();
	} // end function _corban_die

	function _corban_bold($text)
	{
		return sprintf(BOLDTEXT, $text);
	} // end function corban_bold

	function _corban_format($text, $class)
	{
		return (CORBAN_FORMAT == CORBAN_FORMAT_HTML)
			? sprintf(TEXTBLOCK, $class, $text)
			: $text;
	} // end function _corban_format

	function _corban_scalar($val, $type, $prefix)
	{
		_corban_echo($prefix . _corban_format($val, $type) . CORBAN_BR);
	} // end function _corban_scalar

	function _corban_empty($type, $prefix)
	{
		_corban_echo($prefix . _corban_format(' is empty ' . $type, 'empty') . CORBAN_BR);
	} // end function _corban_empty

	function _corban_array($arr, $prefix)
	{
		if (empty($arr))
		{
			_corban_empty('array', $prefix);
		}

		foreach ($arr as $key => $value)
		{
			$curprefix = $prefix . CORBAN_NBSP . '[' . _corban_format($key , 'array') . ']';
			_corban_dear($value, null, null, false, $curprefix);
		}
	} // end function _corban_array

	function _corban_methods($methods, $prefix)
	{
		foreach ($methods as $method)
		{
			_corban_scalar($method . '()' , 'method', $prefix);
		}
	} // end function _corban_methods

	function _corban_unserialize(&$varlist)
	{
		if ((strtolower($varlist[0]) == 'o') ||
			(strtolower($varlist[0]) == 'a'))
		{
			$pos = strpos($varlist, '{') + 1;
			$intcnt = 0;
			while ($pos < strlen($varlist))
			{
				if ($varlist[$pos] == '{')
				{
					$intcnt++;
				}
				elseif ($varlist[$pos] == '}')
				{
					if ($intcnt)
					{
						$intcnt--;
					}
					else
					{
						$pos++;
						break;
					}
				}
				$pos++;
			}
		}
		else
		{
			list($type, $length, ) = explode(':', substr($varlist, 0, strpos($varlist, ';')), 3);
			switch ($type)
			{
				case 's':
					$pos = strlen($type) + 1 + strlen($length) + 1 + 1 + $length + 1 + 1;
					break;
				case 'i':
					$pos = strlen($type) + 1 + strlen($length) + 1;
					break;
				default:
					if ($length == 0)
					{
						$pos = strlen($type) + 1 + strlen($length) + 1;
					}
					else
					{
						$pos = strlen($type) + 1 + strlen($length) + 1 + $length + 1;
					}
					break;
			}
		}
		$value = unserialize(substr($varlist, 0, $pos));
		$varlist = substr($varlist, $pos);
		return $value;
	} // end function _corban_unserialize

	function _corban_object($obj, $prefix)
	{
		$ver = phpversion();
		$php4add = (($ver[0] == '4') || ($ver[0] == '5')) ? 2 : 0;
		$objAsStr = serialize($obj);
		$objAsArr = explode(':', $objAsStr, 3 + $php4add);
		$objPropCount = $objAsArr[3];

		if (function_exists('get_class'))
		{
			$objClassName = ucfirst(get_class($obj));
		}
		elseif ($php4add)
		{
			$objClassName = ucfirst(substr($objAsArr[2], 1, $objAsArr[1]));
		}
		else
		{
			$objClassName = 'Unknown';
		}

		$objPropList = substr($objAsArr[2+$php4add], 1, -1);

		$prefix .= '->' . _corban_format($objClassName, 'class');

		if (strlen($objPropList) == 0)
		{
			_corban_empty('object', $prefix); return;
		}

		$prefix .= '::';

		if (function_exists('get_class_methods') && defined('CORBAN_SHOW_CLASS_METHOD'))
		{
			_corban_methods(get_class_methods($obj), $prefix);
		}

		if (function_exists('get_object_vars') && count(get_object_vars($obj)))
		{
			$propList = get_object_vars($obj);
			foreach ($propList as $prop => $value)
			{
				$curprefix = $prefix . _corban_format($prop, 'property');
				_corban_dear($value, null, null, false, $curprefix);
			}
		}
		else
		{
			while (strlen($objPropList))
			{
				$curprefix = $prefix . _corban_format(_corban_unserialize($objPropList), 'property');
				_corban_dear(_corban_unserialize($objPropList), null, null, false, $curprefix);
			}
		}

	} // end function _corban_object

	function _corban_resource($res, $prefix)
	{
		if (empty($res))
		{
			_corban_empty('resource', $prefix);
		}

		$curprefix = $prefix . CORBAN_NBSP . '{' . _corban_format(ucwords(get_resource_type($res)) , 'resource') . '}' . CORBAN_NBSP;
		_corban_scalar(strval($res), 'resource', $curprefix);
	} // end function _corban_resource

	function _corban_microtime()
	{
		$time = explode(' ', microtime ());
		return doubleval($time[0]) + doubleval($time[1]);
	} // end function _corban_microtime

	function _corban_timer($name=0)
	{
		global $corban_timer;
		return _corban_microtime() - $corban_timer[$name];
	} // end function _corban_timer

	function _corban_inittimer($name=0)
	{
		global $corban_timer;
		$corban_timer[$name] = _corban_microtime();
	} // end function _corban_inittimer

	function _corban_showtimer($name=0, $comment=null)
	{
		if (empty($comment))
		{
			$comment = 'CORBAN TIMER ' . $name . ' (sec)';
		}
		_corban_dear(sprintf('%.3f', _corban_timer($name)), null, $comment, false, null, true);
	} // end function _corban_showtimer

	function _corban_echo($message)
	{
		global $_corban_counter;

		switch (CORBAN_OUTPUT)
		{
			case CORBAN_OUTPUT_INLINE:
				if (!defined('CORBAN_SUPPRESS_OUTPUT'))
				{
					if ($_corban_counter == 1)
					{
						echo CORBAN_STYLE;
					}
					echo $message;
				}
				break;

			default:

			case CORBAN_OUTPUT_BUFFER:
			case CORBAN_OUTPUT_CONSOLE:
				global $corban_buffer;
				$corban_buffer .= $message;
				break;

			case CORBAN_OUTPUT_FILE:
				error_log($message, 3, CORBAN_FILE_NAME);
				break;
		}
	} // end function _corban_echo

	function _corban_is_fix_required()
	{
		return !defined('CORBAN_SUPPRESS_OUTPUT') &&
			(CORBAN_OUTPUT == CORBAN_OUTPUT_BUFFER || CORBAN_OUTPUT == CORBAN_OUTPUT_CONSOLE);
	} // end function _corban_is_fix_required

	function _corban_fix_output_init()
	{
		if (_corban_is_fix_required() && !defined('_CORBAN_FIX_OUTPUT_'))
		{
			define('_CORBAN_FIX_OUTPUT_', 1);
			ob_start('__corban_output_callback');
		}
	} // end function corban_fix_output_init

	function __corban_output_callback($output)
	{
		global $corban_buffer;
		if (_corban_is_fix_required() && strlen($corban_buffer))
		{
			corban_shutup();
			if (stripos($output, '</body>') !== false)
			{
				if (CORBAN_OUTPUT == CORBAN_OUTPUT_BUFFER)
				{
					if (stripos($output, '</head>') !== false)
					{
						$output = str_ireplace('</head>', (CORBAN_STYLE . "\n" . '</head>'), $output);
					}
					else
					{
						$output = str_ireplace('</body>', (CORBAN_STYLE . "\n" . '</body>'), $output);
					}
					$output = str_ireplace('</body>', (CORBAN_HR . $corban_buffer . "\n" . '</body>'), $output);
				}
				elseif (CORBAN_OUTPUT == CORBAN_OUTPUT_CONSOLE)
				{
					$output = str_ireplace('</body>', sprintf(CORBAN_CONSOLE_SCRIPT . "\n" . '</body>', CORBAN_STYLE, addslashes($corban_buffer)), $output);
				}
			}
			else
			{
				if (CORBAN_OUTPUT == CORBAN_OUTPUT_BUFFER)
				{
					$output .= CORBAN_STYLE;
					$output .= CORBAN_HR;
					$output .= $corban_buffer;
				}
				elseif (CORBAN_OUTPUT == CORBAN_OUTPUT_CONSOLE)
				{
					$output .= sprintf(CORBAN_CONSOLE_SCRIPT, CORBAN_STYLE, addslashes($corban_buffer));
				}
			}
		}
		return $output;
	} // end function _corban_output_callback

	function __corban_shutdown_buffer()
	{
		global $corban_buffer;

		if (strlen($corban_buffer) && !defined('CORBAN_SUPPRESS_OUTPUT') && !defined('_CORBAN_FIX_OUTPUT_'))
		{
			echo CORBAN_STYLE;
			echo CORBAN_HR;
			echo $corban_buffer;
		}
	} // end function __corban_shutdown_buffer

	function __corban_shutdown_console()
	{
		global $corban_buffer;

		if (strlen($corban_buffer) && !defined('CORBAN_SUPPRESS_OUTPUT') && !defined('_CORBAN_FIX_OUTPUT_'))
		{
			// output JavaScript
			printf(CORBAN_CONSOLE_SCRIPT, CORBAN_STYLE, addslashes($corban_buffer));
		}
	} // end function __corban_shutdown_console

	switch (CORBAN_OUTPUT)
	{
		default:
		case CORBAN_OUTPUT_BUFFER:
			$corban_buffer = '';
			register_shutdown_function('__corban_shutdown_buffer');
			break;

		case CORBAN_OUTPUT_CONSOLE:
			$corban_buffer = '';
			register_shutdown_function('__corban_shutdown_console');
			break;

		case CORBAN_OUTPUT_INLINE:
			break;

		case CORBAN_OUTPUT_FILE:
			break;
	}

	if (defined('CORBAN_FIX_OUTPUT'))
	{
		_corban_fix_output_init();
	}

	define ('CORBAN_INCLUDED', true);

endif;
