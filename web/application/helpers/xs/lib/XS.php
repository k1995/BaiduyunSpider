<?php
/**
 * Xunsearch PHP-SDK 引导文件
 *
 * 这个文件是由开发工具中的 'build lite' 指令智能合并类定义的源码文件
 * 并删除所有注释而自动生成的。
 * 
 * 当您编写搜索项目时，先通过 require 引入该文件即可使用所有的 PHP-SDK
 * 功能。合并的主要目的是便于拷贝，只要复制这个库文件即可，而不用拷贝一
 * 大堆文件。详细文档请阅读 {@link:http://www.xunsearch.com/doc/php/}
 * 
 * 切勿手动修改本文件！生成时间：2015/04/02 21:20:56 
 *
 * @author hightman
 * @link http://www.xunsearch.com/
 * @copyright Copyright &copy; 2011 HangZhou YunSheng Network Technology Co., Ltd.
 * @license http://www.xunsearch.com/license/
 * @version $Id$
 */
define('XS_CMD_NONE',	0);
define('XS_CMD_DEFAULT',	XS_CMD_NONE);
define('XS_CMD_PROTOCOL',	20110707);
define('XS_CMD_USE',	1);
define('XS_CMD_HELLO',	1);
define('XS_CMD_DEBUG',	2);
define('XS_CMD_TIMEOUT',	3);
define('XS_CMD_QUIT',	4);
define('XS_CMD_INDEX_SET_DB',	32);
define('XS_CMD_INDEX_GET_DB',	33);
define('XS_CMD_INDEX_SUBMIT',	34);
define('XS_CMD_INDEX_REMOVE',	35);
define('XS_CMD_INDEX_EXDATA',	36);
define('XS_CMD_INDEX_CLEAN_DB',	37);
define('XS_CMD_DELETE_PROJECT',	38);
define('XS_CMD_INDEX_COMMIT',	39);
define('XS_CMD_INDEX_REBUILD',	40);
define('XS_CMD_FLUSH_LOGGING',	41);
define('XS_CMD_INDEX_SYNONYMS',	42);
define('XS_CMD_INDEX_USER_DICT',	43);
define('XS_CMD_SEARCH_DB_TOTAL',	64);
define('XS_CMD_SEARCH_GET_TOTAL',	65);
define('XS_CMD_SEARCH_GET_RESULT',	66);
define('XS_CMD_SEARCH_SET_DB',	XS_CMD_INDEX_SET_DB);
define('XS_CMD_SEARCH_GET_DB',	XS_CMD_INDEX_GET_DB);
define('XS_CMD_SEARCH_ADD_DB',	68);
define('XS_CMD_SEARCH_FINISH',	69);
define('XS_CMD_SEARCH_DRAW_TPOOL',	70);
define('XS_CMD_SEARCH_ADD_LOG',	71);
define('XS_CMD_SEARCH_GET_SYNONYMS',	72);
define('XS_CMD_SEARCH_SCWS_GET',	73);
define('XS_CMD_QUERY_GET_STRING',	96);
define('XS_CMD_QUERY_GET_TERMS',	97);
define('XS_CMD_QUERY_GET_CORRECTED',	98);
define('XS_CMD_QUERY_GET_EXPANDED',	99);
define('XS_CMD_OK',	128);
define('XS_CMD_ERR',	129);
define('XS_CMD_SEARCH_RESULT_DOC',	140);
define('XS_CMD_SEARCH_RESULT_FIELD',	141);
define('XS_CMD_SEARCH_RESULT_FACETS',	142);
define('XS_CMD_SEARCH_RESULT_MATCHED',	143);
define('XS_CMD_DOC_TERM',	160);
define('XS_CMD_DOC_VALUE',	161);
define('XS_CMD_DOC_INDEX',	162);
define('XS_CMD_INDEX_REQUEST',	163);
define('XS_CMD_IMPORT_HEADER',	191);
define('XS_CMD_SEARCH_SET_SORT',	192);
define('XS_CMD_SEARCH_SET_CUT',	193);
define('XS_CMD_SEARCH_SET_NUMERIC',	194);
define('XS_CMD_SEARCH_SET_COLLAPSE',	195);
define('XS_CMD_SEARCH_KEEPALIVE',	196);
define('XS_CMD_SEARCH_SET_FACETS',	197);
define('XS_CMD_SEARCH_SCWS_SET',	198);
define('XS_CMD_SEARCH_SET_CUTOFF',	199);
define('XS_CMD_SEARCH_SET_MISC',	200);
define('XS_CMD_QUERY_INIT',	224);
define('XS_CMD_QUERY_PARSE',	225);
define('XS_CMD_QUERY_TERM',	226);
define('XS_CMD_QUERY_RANGEPROC',	227);
define('XS_CMD_QUERY_RANGE',	228);
define('XS_CMD_QUERY_VALCMP',	229);
define('XS_CMD_QUERY_PREFIX',	230);
define('XS_CMD_QUERY_PARSEFLAG',	231);
define('XS_CMD_SORT_TYPE_RELEVANCE',	0);
define('XS_CMD_SORT_TYPE_DOCID',	1);
define('XS_CMD_SORT_TYPE_VALUE',	2);
define('XS_CMD_SORT_TYPE_MULTI',	3);
define('XS_CMD_SORT_TYPE_MASK',	0x3f);
define('XS_CMD_SORT_FLAG_RELEVANCE',	0x40);
define('XS_CMD_SORT_FLAG_ASCENDING',	0x80);
define('XS_CMD_QUERY_OP_AND',	0);
define('XS_CMD_QUERY_OP_OR',	1);
define('XS_CMD_QUERY_OP_AND_NOT',	2);
define('XS_CMD_QUERY_OP_XOR',	3);
define('XS_CMD_QUERY_OP_AND_MAYBE',	4);
define('XS_CMD_QUERY_OP_FILTER',	5);
define('XS_CMD_RANGE_PROC_STRING',	0);
define('XS_CMD_RANGE_PROC_DATE',	1);
define('XS_CMD_RANGE_PROC_NUMBER',	2);
define('XS_CMD_VALCMP_LE',	0);
define('XS_CMD_VALCMP_GE',	1);
define('XS_CMD_PARSE_FLAG_BOOLEAN',	1);
define('XS_CMD_PARSE_FLAG_PHRASE',	2);
define('XS_CMD_PARSE_FLAG_LOVEHATE',	4);
define('XS_CMD_PARSE_FLAG_BOOLEAN_ANY_CASE',	8);
define('XS_CMD_PARSE_FLAG_WILDCARD',	16);
define('XS_CMD_PARSE_FLAG_PURE_NOT',	32);
define('XS_CMD_PARSE_FLAG_PARTIAL',	64);
define('XS_CMD_PARSE_FLAG_SPELLING_CORRECTION',	128);
define('XS_CMD_PARSE_FLAG_SYNONYM',	256);
define('XS_CMD_PARSE_FLAG_AUTO_SYNONYMS',	512);
define('XS_CMD_PARSE_FLAG_AUTO_MULTIWORD_SYNONYMS',	1536);
define('XS_CMD_PREFIX_NORMAL',	0);
define('XS_CMD_PREFIX_BOOLEAN',	1);
define('XS_CMD_INDEX_WEIGHT_MASK',	0x3f);
define('XS_CMD_INDEX_FLAG_WITHPOS',	0x40);
define('XS_CMD_INDEX_FLAG_SAVEVALUE',	0x80);
define('XS_CMD_INDEX_FLAG_CHECKSTEM',	0x80);
define('XS_CMD_VALUE_FLAG_NUMERIC',	0x80);
define('XS_CMD_INDEX_REQUEST_ADD',	0);
define('XS_CMD_INDEX_REQUEST_UPDATE',	1);
define('XS_CMD_INDEX_SYNONYMS_ADD',	0);
define('XS_CMD_INDEX_SYNONYMS_DEL',	1);
define('XS_CMD_SEARCH_MISC_SYN_SCALE',	1);
define('XS_CMD_SEARCH_MISC_MATCHED_TERM',	2);
define('XS_CMD_SCWS_GET_VERSION',	1);
define('XS_CMD_SCWS_GET_RESULT',	2);
define('XS_CMD_SCWS_GET_TOPS',	3);
define('XS_CMD_SCWS_HAS_WORD',	4);
define('XS_CMD_SCWS_GET_MULTI',	5);
define('XS_CMD_SCWS_SET_IGNORE',	50);
define('XS_CMD_SCWS_SET_MULTI',	51);
define('XS_CMD_SCWS_SET_DUALITY',	52);
define('XS_CMD_SCWS_SET_DICT',	53);
define('XS_CMD_SCWS_ADD_DICT',	54);
define('XS_CMD_ERR_UNKNOWN',	600);
define('XS_CMD_ERR_NOPROJECT',	401);
define('XS_CMD_ERR_TOOLONG',	402);
define('XS_CMD_ERR_INVALIDCHAR',	403);
define('XS_CMD_ERR_EMPTY',	404);
define('XS_CMD_ERR_NOACTION',	405);
define('XS_CMD_ERR_RUNNING',	406);
define('XS_CMD_ERR_REBUILDING',	407);
define('XS_CMD_ERR_WRONGPLACE',	450);
define('XS_CMD_ERR_WRONGFORMAT',	451);
define('XS_CMD_ERR_EMPTYQUERY',	452);
define('XS_CMD_ERR_TIMEOUT',	501);
define('XS_CMD_ERR_IOERR',	502);
define('XS_CMD_ERR_NOMEM',	503);
define('XS_CMD_ERR_BUSY',	504);
define('XS_CMD_ERR_UNIMP',	505);
define('XS_CMD_ERR_NODB',	506);
define('XS_CMD_ERR_DBLOCKED',	507);
define('XS_CMD_ERR_CREATE_HOME',	508);
define('XS_CMD_ERR_INVALID_HOME',	509);
define('XS_CMD_ERR_REMOVE_HOME',	510);
define('XS_CMD_ERR_REMOVE_DB',	511);
define('XS_CMD_ERR_STAT',	512);
define('XS_CMD_ERR_OPEN_FILE',	513);
define('XS_CMD_ERR_TASK_CANCELED',	514);
define('XS_CMD_ERR_XAPIAN',	515);
define('XS_CMD_OK_INFO',	200);
define('XS_CMD_OK_PROJECT',	201);
define('XS_CMD_OK_QUERY_STRING',	202);
define('XS_CMD_OK_DB_TOTAL',	203);
define('XS_CMD_OK_QUERY_TERMS',	204);
define('XS_CMD_OK_QUERY_CORRECTED',	205);
define('XS_CMD_OK_SEARCH_TOTAL',	206);
define('XS_CMD_OK_RESULT_BEGIN',	XS_CMD_OK_SEARCH_TOTAL);
define('XS_CMD_OK_RESULT_END',	207);
define('XS_CMD_OK_TIMEOUT_SET',	208);
define('XS_CMD_OK_FINISHED',	209);
define('XS_CMD_OK_LOGGED',	210);
define('XS_CMD_OK_RQST_FINISHED',	250);
define('XS_CMD_OK_DB_CHANGED',	251);
define('XS_CMD_OK_DB_INFO',	252);
define('XS_CMD_OK_DB_CLEAN',	253);
define('XS_CMD_OK_PROJECT_ADD',	254);
define('XS_CMD_OK_PROJECT_DEL',	255);
define('XS_CMD_OK_DB_COMMITED',	256);
define('XS_CMD_OK_DB_REBUILD',	257);
define('XS_CMD_OK_LOG_FLUSHED',	258);
define('XS_CMD_OK_DICT_SAVED',	259);
define('XS_CMD_OK_RESULT_SYNONYMS',	280);
define('XS_CMD_OK_SCWS_RESULT',	290);
define('XS_CMD_OK_SCWS_TOPS',	291);
define('XS_PACKAGE_BUGREPORT',	"http://www.xunsearch.com/bugs");
define('XS_PACKAGE_NAME',	"xunsearch");
define('XS_PACKAGE_TARNAME',	"xunsearch");
define('XS_PACKAGE_URL',	"");
define('XS_PACKAGE_VERSION',	"1.4.9");
define('XS_LIB_ROOT', dirname(__FILE__));
class XSException extends Exception
{
	public function __toString()
	{
		$string = '[' . __CLASS__ . '] ' . $this->getRelPath($this->getFile()) . '(' . $this->getLine() . '): ';
		$string .= $this->getMessage() . ($this->getCode() > 0 ? '(S#' . $this->getCode() . ')' : '');
		return $string;
	}
	public static function getRelPath($file)
	{
		$from = getcwd();
		$file = realpath($file);
		if (is_dir($file)) {
			$pos = false;
			$to = $file;
		} else {
			$pos = strrpos($file, '/');
			$to = substr($file, 0, $pos);
		}
		for ($rel = '';; $rel .= '../') {
			if ($from === $to) {
				break;
			}
			if ($from === dirname($from)) {
				$rel .= substr($to, 1);
				break;
			}
			if (!strncmp($from . '/', $to, strlen($from) + 1)) {
				$rel .= substr($to, strlen($from) + 1);
				break;
			}
			$from = dirname($from);
		}
		if (substr($rel, -1, 1) === '/') {
			$rel = substr($rel, 0, -1);
		}
		if ($pos !== false) {
			$rel .= substr($file, $pos);
		}
		return $rel;
	}
}
class XSErrorException extends XSException
{
	private $_file, $_line;
	public function __construct($code, $message, $file, $line, $previous = null)
	{
		$this->_file = $file;
		$this->_line = $line;
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			parent::__construct($message, $code, $previous);
		} else {
			parent::__construct($message, $code);
		}
	}
	public function __toString()
	{
		$string = '[' . __CLASS__ . '] ' . $this->getRelPath($this->_file) . '(' . $this->_line . '): ';
		$string .= $this->getMessage() . '(' . $this->getCode() . ')';
		return $string;
	}
}
class XSComponent
{
	public function __get($name)
	{
		$getter = 'get' . $name;
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		$msg = method_exists($this, 'set' . $name) ? 'Write-only' : 'Undefined';
		$msg .= ' property: ' . get_class($this) . '::$' . $name;
		throw new XSException($msg);
	}
	public function __set($name, $value)
	{
		$setter = 'set' . $name;
		if (method_exists($this, $setter)) {
			return $this->$setter($value);
		}
		$msg = method_exists($this, 'get' . $name) ? 'Read-only' : 'Undefined';
		$msg .= ' property: ' . get_class($this) . '::$' . $name;
		throw new XSException($msg);
	}
	public function __isset($name)
	{
		return method_exists($this, 'get' . $name);
	}
	public function __unset($name)
	{
		$this->__set($name, null);
	}
}
class XS extends XSComponent
{
	private $_index;
	private $_search;
	private $_scws;
	private $_scheme, $_bindScheme;
	private $_config;
	private static $_lastXS;
	public function __construct($file)
	{
		if (strlen($file) < 255 && !is_file($file)) {
			$appRoot = getenv('XS_APP_ROOT');
			if ($appRoot === false) {
				$appRoot = defined('XS_APP_ROOT') ? XS_APP_ROOT : XS_LIB_ROOT . '/../app';
			}
			$file2 = $appRoot . '/' . $file . '.ini';
			if (is_file($file2)) {
				$file = $file2;
			}
		}
		$this->loadIniFile($file);
		self::$_lastXS = $this;
	}
	public function __destruct()
	{
		$this->_index = null;
		$this->_search = null;
	}
	public static function getLastXS()
	{
		return self::$_lastXS;
	}
	public function getScheme()
	{
		return $this->_scheme;
	}
	public function setScheme(XSFieldScheme $fs)
	{
		$fs->checkValid(true);
		$this->_scheme = $fs;
		if ($this->_search !== null) {
			$this->_search->markResetScheme();
		}
	}
	public function restoreScheme()
	{
		if ($this->_scheme !== $this->_bindScheme) {
			$this->_scheme = $this->_bindScheme;
			if ($this->_search !== null) {
				$this->_search->markResetScheme(true);
			}
		}
	}
	public function getConfig()
	{
		return $this->_config;
	}
	public function getName()
	{
		return $this->_config['project.name'];
	}
	public function setName($name)
	{
		$this->_config['project.name'] = $name;
	}
	public function getDefaultCharset()
	{
		return isset($this->_config['project.default_charset']) ?
				strtoupper($this->_config['project.default_charset']) : 'UTF-8';
	}
	public function setDefaultCharset($charset)
	{
		$this->_config['project.default_charset'] = strtoupper($charset);
	}
	public function getIndex()
	{
		if ($this->_index === null) {
			$adds = array();
			$conn = isset($this->_config['server.index']) ? $this->_config['server.index'] : 8383;
			if (($pos = strpos($conn, ';')) !== false) {
				$adds = explode(';', substr($conn, $pos + 1));
				$conn = substr($conn, 0, $pos);
			}
			$this->_index = new XSIndex($conn, $this);
			$this->_index->setTimeout(0);
			foreach ($adds as $conn) {
				$conn = trim($conn);
				if ($conn !== '') {
					$this->_index->addServer($conn)->setTimeout(0);
				}
			}
		}
		return $this->_index;
	}
	public function getSearch()
	{
		if ($this->_search === null) {
			$conns = array();
			if (!isset($this->_config['server.search'])) {
				$conns[] = 8384;
			} else {
				foreach (explode(';', $this->_config['server.search']) as $conn) {
					$conn = trim($conn);
					if ($conn !== '') {
						$conns[] = $conn;
					}
				}
			}
			if (count($conns) > 1) {
				shuffle($conns);
			}
			for ($i = 0; $i < count($conns); $i++) {
				try {
					$this->_search = new XSSearch($conns[$i], $this);
					$this->_search->setCharset($this->getDefaultCharset());
					return $this->_search;
				} catch (XSException $e) {
					if (($i + 1) === count($conns)) {
						throw $e;
					}
				}
			}
		}
		return $this->_search;
	}
	public function getScwsServer()
	{
		if ($this->_scws === null) {
			$conn = isset($this->_config['server.search']) ? $this->_config['server.search'] : 8384;
			$this->_scws = new XSServer($conn, $this);
		}
		return $this->_scws;
	}
	public function getFieldId()
	{
		return $this->_scheme->getFieldId();
	}
	public function getFieldTitle()
	{
		return $this->_scheme->getFieldTitle();
	}
	public function getFieldBody()
	{
		return $this->_scheme->getFieldBody();
	}
	public function getField($name, $throw = true)
	{
		return $this->_scheme->getField($name, $throw);
	}
	public function getAllFields()
	{
		return $this->_scheme->getAllFields();
	}
	public static function autoload($name)
	{
		$file = XS_LIB_ROOT . '/' . $name . '.class.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
	public static function convert($data, $to, $from)
	{
		if ($to == $from) {
			return $data;
		}
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = self::convert($value, $to, $from);
			}
			return $data;
		}
		if (is_string($data) && preg_match('/[\x81-\xfe]/', $data)) {
			if (function_exists('mb_convert_encoding')) {
				return mb_convert_encoding($data, $to, $from);
			} elseif (function_exists('iconv')) {
				return iconv($from, $to . '//TRANSLIT', $data);
			} else {
				throw new XSException('Cann\'t find the mbstring or iconv extension to convert encoding');
			}
		}
		return $data;
	}
	private function parseIniData($data)
	{
		$ret = array();
		$cur = &$ret;
		$lines = explode("\n", $data);
		foreach ($lines as $line) {
			if ($line === '' || $line[0] == ';' || $line[0] == '#') {
				continue;
			}
			$line = trim($line);
			if ($line === '') {
				continue;
			}
			if ($line[0] === '[' && substr($line, -1, 1) === ']') {
				$sec = substr($line, 1, -1);
				$ret[$sec] = array();
				$cur = &$ret[$sec];
				continue;
			}
			if (($pos = strpos($line, '=')) === false) {
				continue;
			}
			$key = trim(substr($line, 0, $pos));
			$value = trim(substr($line, $pos + 1), " '\t\"");
			$cur[$key] = $value;
		}
		return $ret;
	}
	private function loadIniFile($file)
	{
		$cache = false;
		$cache_write = '';
		if (strlen($file) < 255 && file_exists($file)) {
			$cache_key = md5(__CLASS__ . '::ini::' . realpath($file));
			if (function_exists('apc_fetch')) {
				$cache = apc_fetch($cache_key);
				$cache_write = 'apc_store';
			} elseif (function_exists('xcache_get') && php_sapi_name() !== 'cli') {
				$cache = xcache_get($cache_key);
				$cache_write = 'xcache_set';
			} elseif (function_exists('eaccelerator_get')) {
				$cache = eaccelerator_get($cache_key);
				$cache_write = 'eaccelerator_put';
			}
			if ($cache && isset($cache['mtime']) && isset($cache['scheme'])
					&& filemtime($file) <= $cache['mtime']) {
				$this->_scheme = $this->_bindScheme = unserialize($cache['scheme']);
				$this->_config = $cache['config'];
				return;
			}
			$data = file_get_contents($file);
		} else {
			$data = $file;
			$file = substr(md5($file), 8, 8) . '.ini';
		}
		$this->_config = $this->parseIniData($data);
		if ($this->_config === false) {
			throw new XSException('Failed to parse project config file/string: \'' . substr($file, 0, 10) . '...\'');
		}
		$scheme = new XSFieldScheme;
		foreach ($this->_config as $key => $value) {
			if (is_array($value)) {
				$scheme->addField($key, $value);
			}
		}
		$scheme->checkValid(true);
		if (!isset($this->_config['project.name'])) {
			$this->_config['project.name'] = basename($file, '.ini');
		}
		$this->_scheme = $this->_bindScheme = $scheme;
		if ($cache_write != '') {
			$cache['mtime'] = filemtime($file);
			$cache['scheme'] = serialize($this->_scheme);
			$cache['config'] = $this->_config;
			call_user_func($cache_write, $cache_key, $cache);
		}
	}
}
function xsErrorHandler($errno, $error, $file, $line)
{
	if (($errno & ini_get('error_reporting')) && !strncmp($file, XS_LIB_ROOT, strlen(XS_LIB_ROOT))) {
		throw new XSErrorException($errno, $error, $file, $line);
	}
	return false;
}
set_error_handler('xsErrorHandler');
class XSDocument implements ArrayAccess, IteratorAggregate
{
	private $_data;
	private $_terms, $_texts;
	private $_charset, $_meta;
	private static $_resSize = 20;
	private static $_resFormat = 'Idocid/Irank/Iccount/ipercent/fweight';
	public function __construct($p = null, $d = null)
	{
		$this->_data = array();
		if (is_array($p)) {
			$this->_data = $p;
		} elseif (is_string($p)) {
			if (strlen($p) !== self::$_resSize) {
				$this->setCharset($p);
				return;
			}
			$this->_meta = unpack(self::$_resFormat, $p);
		}
		if ($d !== null && is_string($d)) {
			$this->setCharset($d);
		}
	}
	public function __get($name)
	{
		if (!isset($this->_data[$name])) {
			return null;
		}
		return $this->autoConvert($this->_data[$name]);
	}
	public function __set($name, $value)
	{
		if ($this->_meta !== null) {
			throw new XSException('Magick property of result document is read-only');
		}
		$this->setField($name, $value);
	}
	public function __call($name, $args)
	{
		if ($this->_meta !== null) {
			$name = strtolower($name);
			if (isset($this->_meta[$name])) {
				return $this->_meta[$name];
			}
		}
		throw new XSException('Call to undefined method `' . get_class($this) . '::' . $name . '()\'');
	}
	public function getCharset()
	{
		return $this->_charset;
	}
	public function setCharset($charset)
	{
		$this->_charset = strtoupper($charset);
		if ($this->_charset == 'UTF8') {
			$this->_charset = 'UTF-8';
		}
	}
	public function getFields()
	{
		return $this->_data;
	}
	public function setFields($data)
	{
		if ($data === null) {
			$this->_data = array();
			$this->_meta = $this->_terms = $this->_texts = null;
		} else {
			$this->_data = array_merge($this->_data, $data);
		}
	}
	public function setField($name, $value, $isMeta = false)
	{
		if ($value === null) {
			if ($isMeta) {
				unset($this->_meta[$name]);
			} else {
				unset($this->_data[$name]);
			}
		} else {
			if ($isMeta) {
				$this->_meta[$name] = $value;
			} else {
				$this->_data[$name] = $value;
			}
		}
	}
	public function f($name)
	{
		return $this->__get(strval($name));
	}
	public function getAddTerms($field)
	{
		$field = strval($field);
		if ($this->_terms === null || !isset($this->_terms[$field])) {
			return null;
		}
		$terms = array();
		foreach ($this->_terms[$field] as $term => $weight) {
			$term = $this->autoConvert($term);
			$terms[$term] = $weight;
		}
		return $terms;
	}
	public function getAddIndex($field)
	{
		$field = strval($field);
		if ($this->_texts === null || !isset($this->_texts[$field])) {
			return null;
		}
		return $this->autoConvert($this->_texts[$field]);
	}
	public function addTerm($field, $term, $weight = 1)
	{
		$field = strval($field);
		if (!is_array($this->_terms)) {
			$this->_terms = array();
		}
		if (!isset($this->_terms[$field])) {
			$this->_terms[$field] = array($term => $weight);
		} elseif (!isset($this->_terms[$field][$term])) {
			$this->_terms[$field][$term] = $weight;
		} else {
			$this->_terms[$field][$term] += $weight;
		}
	}
	public function addIndex($field, $text)
	{
		$field = strval($field);
		if (!is_array($this->_texts)) {
			$this->_texts = array();
		}
		if (!isset($this->_texts[$field])) {
			$this->_texts[$field] = strval($text);
		} else {
			$this->_texts[$field] .= "\n" . strval($text);
		}
	}
	public function getIterator()
	{
		if ($this->_charset !== null && $this->_charset !== 'UTF-8') {
			$from = $this->_meta === null ? $this->_charset : 'UTF-8';
			$to = $this->_meta === null ? 'UTF-8' : $this->_charset;
			return new ArrayIterator(XS::convert($this->_data, $to, $from));
		}
		return new ArrayIterator($this->_data);
	}
	public function offsetExists($name)
	{
		return isset($this->_data[$name]);
	}
	public function offsetGet($name)
	{
		return $this->__get($name);
	}
	public function offsetSet($name, $value)
	{
		if (!is_null($name)) {
			$this->__set(strval($name), $value);
		}
	}
	public function offsetUnset($name)
	{
		unset($this->_data[$name]);
	}
	public function beforeSubmit(XSIndex $index)
	{
		if ($this->_charset === null) {
			$this->_charset = $index->xs->getDefaultCharset();
		}
		return true;
	}
	public function afterSubmit($index)
	{
	}
	private function autoConvert($value)
	{
		if ($this->_charset === null || $this->_charset == 'UTF-8'
				|| !is_string($value) || !preg_match('/[\x81-\xfe]/', $value)) {
			return $value;
		}
		$from = $this->_meta === null ? $this->_charset : 'UTF-8';
		$to = $this->_meta === null ? 'UTF-8' : $this->_charset;
		return XS::convert($value, $to, $from);
	}
}
class XSFieldScheme implements IteratorAggregate
{
	const MIXED_VNO = 255;
	private $_fields = array();
	private $_typeMap = array();
	private $_vnoMap = array();
	private static $_logger;
	public function __toString()
	{
		$str = '';
		foreach ($this->_fields as $field) {
			$str .= $field->toConfig() . "\n";
		}
		return $str;
	}
	public function getFieldId()
	{
		if (isset($this->_typeMap[XSFieldMeta::TYPE_ID])) {
			$name = $this->_typeMap[XSFieldMeta::TYPE_ID];
			return $this->_fields[$name];
		}
		return false;
	}
	public function getFieldTitle()
	{
		if (isset($this->_typeMap[XSFieldMeta::TYPE_TITLE])) {
			$name = $this->_typeMap[XSFieldMeta::TYPE_TITLE];
			return $this->_fields[$name];
		}
		foreach ($this->_fields as $name => $field) {
			if ($field->type === XSFieldMeta::TYPE_STRING && !$field->isBoolIndex()) {
				return $field;
			}
		}
		return false;
	}
	public function getFieldBody()
	{
		if (isset($this->_typeMap[XSFieldMeta::TYPE_BODY])) {
			$name = $this->_typeMap[XSFieldMeta::TYPE_BODY];
			return $this->_fields[$name];
		}
		return false;
	}
	public function getField($name, $throw = true)
	{
		if (is_int($name)) {
			if (!isset($this->_vnoMap[$name])) {
				if ($throw === true) {
					throw new XSException('Not exists field with vno: `' . $name . '\'');
				}
				return false;
			}
			$name = $this->_vnoMap[$name];
		}
		if (!isset($this->_fields[$name])) {
			if ($throw === true) {
				throw new XSException('Not exists field with name: `' . $name . '\'');
			}
			return false;
		}
		return $this->_fields[$name];
	}
	public function getAllFields()
	{
		return $this->_fields;
	}
	public function getVnoMap()
	{
		return $this->_vnoMap;
	}
	public function addField($field, $config = null)
	{
		if (!$field instanceof XSFieldMeta) {
			$field = new XSFieldMeta($field, $config);
		}
		if (isset($this->_fields[$field->name])) {
			throw new XSException('Duplicated field name: `' . $field->name . '\'');
		}
		if ($field->isSpeical()) {
			if (isset($this->_typeMap[$field->type])) {
				$prev = $this->_typeMap[$field->type];
				throw new XSException('Duplicated ' . strtoupper($config['type']) . ' field: `' . $field->name . '\' and `' . $prev . '\'');
			}
			$this->_typeMap[$field->type] = $field->name;
		}
		$field->vno = ($field->type == XSFieldMeta::TYPE_BODY) ? self::MIXED_VNO : count($this->_vnoMap);
		$this->_vnoMap[$field->vno] = $field->name;
		if ($field->type == XSFieldMeta::TYPE_ID) {
			$this->_fields = array_merge(array($field->name => $field), $this->_fields);
		} else {
			$this->_fields[$field->name] = $field;
		}
	}
	public function checkValid($throw = false)
	{
		if (!isset($this->_typeMap[XSFieldMeta::TYPE_ID])) {
			if ($throw) {
				throw new XSException('Missing field of type ID');
			}
			return false;
		}
		return true;
	}
	public function getIterator()
	{
		return new ArrayIterator($this->_fields);
	}
	public static function logger()
	{
		if (self::$_logger === null) {
			$scheme = new self;
			$scheme->addField('id', array('type' => 'id'));
			$scheme->addField('pinyin');
			$scheme->addField('partial');
			$scheme->addField('total', array('type' => 'numeric', 'index' => 'self'));
			$scheme->addField('lastnum', array('type' => 'numeric', 'index' => 'self'));
			$scheme->addField('currnum', array('type' => 'numeric', 'index' => 'self'));
			$scheme->addField('currtag', array('type' => 'string'));
			$scheme->addField('body', array('type' => 'body'));
			self::$_logger = $scheme;
		}
		return self::$_logger;
	}
}
class XSFieldMeta
{
	const MAX_WDF = 0x3f;
	const TYPE_STRING = 0;
	const TYPE_NUMERIC = 1;
	const TYPE_DATE = 2;
	const TYPE_ID = 10;
	const TYPE_TITLE = 11;
	const TYPE_BODY = 12;
	const FLAG_INDEX_SELF = 0x01;
	const FLAG_INDEX_MIXED = 0x02;
	const FLAG_INDEX_BOTH = 0x03;
	const FLAG_WITH_POSITION = 0x10;
	const FLAG_NON_BOOL = 0x80; // 强制让该字段参与权重计算 (非布尔)
	public $name;
	public $cutlen = 0;
	public $weight = 1;
	public $type = 0;
	public $vno = 0;
	private $tokenizer = XSTokenizer::DFL;
	private $flag = 0;
	private static $_tokenizers = array();
	public function __construct($name, $config = null)
	{
		$this->name = strval($name);
		if (is_array($config)) {
			$this->fromConfig($config);
		}
	}
	public function __toString()
	{
		return $this->name;
	}
	public function val($value)
	{
		if ($this->type == self::TYPE_DATE) {
			if (!is_numeric($value) || strlen($value) !== 8) {
				$value = date('Ymd', is_numeric($value) ? $value : strtotime($value));
			}
		}
		return $value;
	}
	public function withPos()
	{
		return ($this->flag & self::FLAG_WITH_POSITION) ? true : false;
	}
	public function isBoolIndex()
	{
		if ($this->flag & self::FLAG_NON_BOOL) {
			return false;
		}
		return (!$this->hasIndex() || $this->tokenizer !== XSTokenizer::DFL);
	}
	public function isNumeric()
	{
		return ($this->type == self::TYPE_NUMERIC);
	}
	public function isSpeical()
	{
		return ($this->type == self::TYPE_ID || $this->type == self::TYPE_TITLE || $this->type == self::TYPE_BODY);
	}
	public function hasIndex()
	{
		return ($this->flag & self::FLAG_INDEX_BOTH) ? true : false;
	}
	public function hasIndexMixed()
	{
		return ($this->flag & self::FLAG_INDEX_MIXED) ? true : false;
	}
	public function hasIndexSelf()
	{
		return ($this->flag & self::FLAG_INDEX_SELF) ? true : false;
	}
	public function hasCustomTokenizer()
	{
		return ($this->tokenizer !== XSTokenizer::DFL);
	}
	public function getCustomTokenizer()
	{
		if (isset(self::$_tokenizers[$this->tokenizer])) {
			return self::$_tokenizers[$this->tokenizer];
		} else {
			if (($pos1 = strpos($this->tokenizer, '(')) !== false
					&& ($pos2 = strrpos($this->tokenizer, ')', $pos1 + 1))) {
				$name = 'XSTokenizer' . ucfirst(trim(substr($this->tokenizer, 0, $pos1)));
				$arg = substr($this->tokenizer, $pos1 + 1, $pos2 - $pos1 - 1);
			} else {
				$name = 'XSTokenizer' . ucfirst($this->tokenizer);
				$arg = null;
			}
			if (!class_exists($name)) {
				$file = $name . '.class.php';
				if (file_exists($file)) {
					require_once $file;
				} else if (file_exists(XS_LIB_ROOT . DIRECTORY_SEPARATOR . $file)) {
					require_once XS_LIB_ROOT . DIRECTORY_SEPARATOR . $file;
				}
				if (!class_exists($name)) {
					throw new XSException('Undefined custom tokenizer `' . $this->tokenizer . '\' for field `' . $this->name . '\'');
				}
			}
			$obj = $arg === null ? new $name : new $name($arg);
			if (!$obj instanceof XSTokenizer) {
				throw new XSException($name . ' for field `' . $this->name . '\' dose not implement the interface: XSTokenizer');
			}
			self::$_tokenizers[$this->tokenizer] = $obj;
			return $obj;
		}
	}
	public function toConfig()
	{
		$str = "[" . $this->name . "]\n";
		if ($this->type === self::TYPE_NUMERIC) {
			$str .= "type = numeric\n";
		} elseif ($this->type === self::TYPE_DATE) {
			$str .= "type = date\n";
		} elseif ($this->type === self::TYPE_ID) {
			$str .= "type = id\n";
		} elseif ($this->type === self::TYPE_TITLE) {
			$str .= "type = title\n";
		} elseif ($this->type === self::TYPE_BODY) {
			$str .= "type = body\n";
		}
		if ($this->type !== self::TYPE_BODY && ($index = ($this->flag & self::FLAG_INDEX_BOTH))) {
			if ($index === self::FLAG_INDEX_BOTH) {
				if ($this->type !== self::TYPE_TITLE) {
					$str .= "index = both\n";
				}
			} elseif ($index === self::FLAG_INDEX_MIXED) {
				$str .= "index = mixed\n";
			} else {
				if ($this->type !== self::TYPE_ID) {
					$str .= "index = self\n";
				}
			}
		}
		if ($this->type !== self::TYPE_ID && $this->tokenizer !== XSTokenizer::DFL) {
			$str .= "tokenizer = " . $this->tokenizer . "\n";
		}
		if ($this->cutlen > 0 && !($this->cutlen === 300 && $this->type === self::TYPE_BODY)) {
			$str .= "cutlen = " . $this->cutlen . "\n";
		}
		if ($this->weight !== 1 && !($this->weight === 5 && $this->type === self::TYPE_TITLE)) {
			$str .= "weight = " . $this->weight . "\n";
		}
		if ($this->flag & self::FLAG_WITH_POSITION) {
			if ($this->type !== self::TYPE_BODY && $this->type !== self::TYPE_TITLE) {
				$str .= "phrase = yes\n";
			}
		} else {
			if ($this->type === self::TYPE_BODY || $this->type === self::TYPE_TITLE) {
				$str .= "phrase = no\n";
			}
		}
		if ($this->flag & self::FLAG_NON_BOOL) {
			$str .= "non_bool = yes\n";
		}
		return $str;
	}
	public function fromConfig($config)
	{
		if (isset($config['type'])) {
			$predef = 'self::TYPE_' . strtoupper($config['type']);
			if (defined($predef)) {
				$this->type = constant($predef);
				if ($this->type == self::TYPE_ID) {
					$this->flag = self::FLAG_INDEX_SELF;
					$this->tokenizer = 'full';
				} elseif ($this->type == self::TYPE_TITLE) {
					$this->flag = self::FLAG_INDEX_BOTH | self::FLAG_WITH_POSITION;
					$this->weight = 5;
				} elseif ($this->type == self::TYPE_BODY) {
					$this->vno = XSFieldScheme::MIXED_VNO;
					$this->flag = self::FLAG_INDEX_SELF | self::FLAG_WITH_POSITION;
					$this->cutlen = 300;
				}
			}
		}
		if (isset($config['index']) && $this->type != self::TYPE_BODY) {
			$predef = 'self::FLAG_INDEX_' . strtoupper($config['index']);
			if (defined($predef)) {
				$this->flag &= ~ self::FLAG_INDEX_BOTH;
				$this->flag |= constant($predef);
			}
			if ($this->type == self::TYPE_ID) {
				$this->flag |= self::FLAG_INDEX_SELF;
			}
		}
		if (isset($config['cutlen'])) {
			$this->cutlen = intval($config['cutlen']);
		}
		if (isset($config['weight']) && $this->type != self::TYPE_BODY) {
			$this->weight = intval($config['weight']) & self::MAX_WDF;
		}
		if (isset($config['phrase'])) {
			if (!strcasecmp($config['phrase'], 'yes')) {
				$this->flag |= self::FLAG_WITH_POSITION;
			} elseif (!strcasecmp($config['phrase'], 'no')) {
				$this->flag &= ~ self::FLAG_WITH_POSITION;
			}
		}
		if (isset($config['non_bool'])) {
			if (!strcasecmp($config['non_bool'], 'yes')) {
				$this->flag |= self::FLAG_NON_BOOL;
			} elseif (!strcasecmp($config['non_bool'], 'no')) {
				$this->flag &= ~ self::FLAG_NON_BOOL;
			}
		}
		if (isset($config['tokenizer']) && $this->type != self::TYPE_ID
				&& $config['tokenizer'] != 'default') {
			$this->tokenizer = $config['tokenizer'];
		}
	}
}
class XSIndex extends XSServer
{
	private $_buf = '';
	private $_bufSize = 0;
	private $_rebuild = false;
	private static $_adds = array();
	public function addServer($conn)
	{
		$srv = new XSServer($conn, $this->xs);
		self::$_adds[] = $srv;
		return $srv;
	}
	public function execCommand($cmd, $res_arg = XS_CMD_NONE, $res_cmd = XS_CMD_OK)
	{
		$res = parent::execCommand($cmd, $res_arg, $res_cmd);
		foreach (self::$_adds as $srv) {
			$srv->execCommand($cmd, $res_arg, $res_cmd);
		}
		return $res;
	}
	public function clean()
	{
		$this->execCommand(XS_CMD_INDEX_CLEAN_DB, XS_CMD_OK_DB_CLEAN);
		return $this;
	}
	public function add(XSDocument $doc)
	{
		return $this->update($doc, true);
	}
	public function update(XSDocument $doc, $add = false)
	{
		if ($doc->beforeSubmit($this) === false) {
			return $this;
		}
		$fid = $this->xs->getFieldId();
		$key = $doc->f($fid);
		if ($key === null || $key === '') {
			throw new XSException('Missing value of primary key (FIELD:' . $fid . ')');
		}
		$cmd = new XSCommand(XS_CMD_INDEX_REQUEST, XS_CMD_INDEX_REQUEST_ADD);
		if ($add !== true) {
			$cmd->arg1 = XS_CMD_INDEX_REQUEST_UPDATE;
			$cmd->arg2 = $fid->vno;
			$cmd->buf = $key;
		}
		$cmds = array($cmd);
		foreach ($this->xs->getAllFields() as $field) /* @var $field XSFieldMeta */ {
			if (($value = $doc->f($field)) !== null) {
				$varg = $field->isNumeric() ? XS_CMD_VALUE_FLAG_NUMERIC : 0;
				$value = $field->val($value);
				if (!$field->hasCustomTokenizer()) {
					$wdf = $field->weight | ($field->withPos() ? XS_CMD_INDEX_FLAG_WITHPOS : 0);
					if ($field->hasIndexMixed()) {
						$cmds[] = new XSCommand(XS_CMD_DOC_INDEX, $wdf, XSFieldScheme::MIXED_VNO, $value);
					}
					if ($field->hasIndexSelf()) {
						$wdf |= $field->isNumeric() ? 0 : XS_CMD_INDEX_FLAG_SAVEVALUE;
						$cmds[] = new XSCommand(XS_CMD_DOC_INDEX, $wdf, $field->vno, $value);
					}
					if (!$field->hasIndexSelf() || $field->isNumeric()) {
						$cmds[] = new XSCommand(XS_CMD_DOC_VALUE, $varg, $field->vno, $value);
					}
				} else {
					if ($field->hasIndex()) {
						$terms = $field->getCustomTokenizer()->getTokens($value, $doc);
						if ($field->hasIndexSelf()) {
							$wdf = $field->isBoolIndex() ? 1 : ($field->weight | XS_CMD_INDEX_FLAG_CHECKSTEM);
							foreach ($terms as $term) {
								if (strlen($term) > 200) {
									continue;
								}
								$term = strtolower($term);
								$cmds[] = new XSCommand(XS_CMD_DOC_TERM, $wdf, $field->vno, $term);
							}
						}
						if ($field->hasIndexMixed()) {
							$mtext = implode(' ', $terms);
							$cmds[] = new XSCommand(XS_CMD_DOC_INDEX, $field->weight, XSFieldScheme::MIXED_VNO, $mtext);
						}
					}
					$cmds[] = new XSCommand(XS_CMD_DOC_VALUE, $varg, $field->vno, $value);
				}
			}
			if (($terms = $doc->getAddTerms($field)) !== null) {
				$wdf1 = $field->isBoolIndex() ? 0 : XS_CMD_INDEX_FLAG_CHECKSTEM;
				foreach ($terms as $term => $wdf) {
					$term = strtolower($term);
					if (strlen($term) > 200) {
						continue;
					}
					$wdf2 = $field->isBoolIndex() ? 1 : $wdf * $field->weight;
					while ($wdf2 > XSFieldMeta::MAX_WDF) {
						$cmds[] = new XSCommand(XS_CMD_DOC_TERM, $wdf1 | XSFieldMeta::MAX_WDF, $field->vno, $term);
						$wdf2 -= XSFieldMeta::MAX_WDF;
					}
					$cmds[] = new XSCommand(XS_CMD_DOC_TERM, $wdf1 | $wdf2, $field->vno, $term);
				}
			}
			if (($text = $doc->getAddIndex($field)) !== null) {
				if (!$field->hasCustomTokenizer()) {
					$wdf = $field->weight | ($field->withPos() ? XS_CMD_INDEX_FLAG_WITHPOS : 0);
					$cmds[] = new XSCommand(XS_CMD_DOC_INDEX, $wdf, $field->vno, $text);
				} else {
					$wdf = $field->isBoolIndex() ? 1 : ($field->weight | XS_CMD_INDEX_FLAG_CHECKSTEM);
					$terms = $field->getCustomTokenizer()->getTokens($text, $doc);
					foreach ($terms as $term) {
						if (strlen($term) > 200) {
							continue;
						}
						$term = strtolower($term);
						$cmds[] = new XSCommand(XS_CMD_DOC_TERM, $wdf, $field->vno, $term);
					}
				}
			}
		}
		$cmds[] = new XSCommand(XS_CMD_INDEX_SUBMIT);
		if ($this->_bufSize > 0) {
			$this->appendBuffer(implode('', $cmds));
		} else {
			for ($i = 0; $i < count($cmds) - 1; $i++) {
				$this->execCommand($cmds[$i]);
			}
			$this->execCommand($cmds[$i], XS_CMD_OK_RQST_FINISHED);
		}
		$doc->afterSubmit($this);
		return $this;
	}
	public function del($term, $field = null)
	{
		$field = $field === null ? $this->xs->getFieldId() : $this->xs->getField($field);
		$cmds = array();
		$terms = is_array($term) ? array_unique($term) : array($term);
		$terms = XS::convert($terms, 'UTF-8', $this->xs->getDefaultCharset());
		foreach ($terms as $term) {
			$cmds[] = new XSCommand(XS_CMD_INDEX_REMOVE, 0, $field->vno, strtolower($term));
		}
		if ($this->_bufSize > 0) {
			$this->appendBuffer(implode('', $cmds));
		} elseif (count($cmds) == 1) {
			$this->execCommand($cmds[0], XS_CMD_OK_RQST_FINISHED);
		} else {
			$cmd = array('cmd' => XS_CMD_INDEX_EXDATA, 'buf' => implode('', $cmds));
			$this->execCommand($cmd, XS_CMD_OK_RQST_FINISHED);
		}
		return $this;
	}
	public function addExdata($data, $check_file = true)
	{
		if (strlen($data) < 255 && $check_file
				&& file_exists($data) && ($data = file_get_contents($data)) === false) {
			throw new XSException('Failed to read exdata from file');
		}
		$first = ord(substr($data, 0, 1));
		if ($first != XS_CMD_IMPORT_HEADER
				&& $first != XS_CMD_INDEX_REQUEST && $first != XS_CMD_INDEX_SYNONYMS
				&& $first != XS_CMD_INDEX_REMOVE && $first != XS_CMD_INDEX_EXDATA) {
			throw new XSException('Invalid start command of exdata (CMD:' . $first . ')');
		}
		$cmd = array('cmd' => XS_CMD_INDEX_EXDATA, 'buf' => $data);
		$this->execCommand($cmd, XS_CMD_OK_RQST_FINISHED);
		return $this;
	}
	public function addSynonym($raw, $synonym)
	{
		$raw = strval($raw);
		$synonym = strval($synonym);
		if ($raw !== '' && $synonym !== '') {
			$cmd = new XSCommand(XS_CMD_INDEX_SYNONYMS, XS_CMD_INDEX_SYNONYMS_ADD, 0, $raw, $synonym);
			if ($this->_bufSize > 0) {
				$this->appendBuffer(strval($cmd));
			} else {
				$this->execCommand($cmd, XS_CMD_OK_RQST_FINISHED);
			}
		}
		return $this;
	}
	public function delSynonym($raw, $synonym = null)
	{
		$raw = strval($raw);
		$synonym = $synonym === null ? '' : strval($synonym);
		if ($raw !== '') {
			$cmd = new XSCommand(XS_CMD_INDEX_SYNONYMS, XS_CMD_INDEX_SYNONYMS_DEL, 0, $raw, $synonym);
			if ($this->_bufSize > 0) {
				$this->appendBuffer(strval($cmd));
			} else {
				$this->execCommand($cmd, XS_CMD_OK_RQST_FINISHED);
			}
		}
		return $this;
	}
	public function setScwsMulti($level)
	{
		$level = intval($level);
		if ($level >= 0 && $level < 16) {
			$cmd = array('cmd' => XS_CMD_SEARCH_SCWS_SET, 'arg1' => XS_CMD_SCWS_SET_MULTI, 'arg2' => $level);
			$this->execCommand($cmd);
		}
		return $this;
	}
	public function getScwsMulti()
	{
		$cmd = array('cmd' => XS_CMD_SEARCH_SCWS_GET, 'arg1' => XS_CMD_SCWS_GET_MULTI);
		$res = $this->execCommand($cmd, XS_CMD_OK_INFO);
		return intval($res->buf);
	}
	public function openBuffer($size = 4)
	{
		if ($this->_buf !== '') {
			$this->addExdata($this->_buf, false);
		}
		$this->_bufSize = intval($size) << 20;
		$this->_buf = '';
		return $this;
	}
	public function closeBuffer()
	{
		return $this->openBuffer(0);
	}
	public function beginRebuild()
	{
		$this->execCommand(array('cmd' => XS_CMD_INDEX_REBUILD, 'arg1' => 0), XS_CMD_OK_DB_REBUILD);
		$this->_rebuild = true;
		return $this;
	}
	public function endRebuild()
	{
		if ($this->_rebuild === true) {
			$this->_rebuild = false;
			$this->execCommand(array('cmd' => XS_CMD_INDEX_REBUILD, 'arg1' => 1), XS_CMD_OK_DB_REBUILD);
		}
		return $this;
	}
	public function stopRebuild()
	{
		try {
			$this->execCommand(array('cmd' => XS_CMD_INDEX_REBUILD, 'arg1' => 2), XS_CMD_OK_DB_REBUILD);
			$this->_rebuild = false;
		} catch (XSException $e) {
			if ($e->getCode() !== XS_CMD_ERR_WRONGPLACE) {
				throw $e;
			}
		}
		return $this;
	}
	public function setDb($name)
	{
		$this->execCommand(array('cmd' => XS_CMD_INDEX_SET_DB, 'buf' => $name), XS_CMD_OK_DB_CHANGED);
		return $this;
	}
	public function flushLogging()
	{
		try {
			$this->execCommand(XS_CMD_FLUSH_LOGGING, XS_CMD_OK_LOG_FLUSHED);
		} catch (XSException $e) {
			if ($e->getCode() === XS_CMD_ERR_BUSY) {
				return false;
			}
			throw $e;
		}
		return true;
	}
	public function flushIndex()
	{
		try {
			$this->execCommand(XS_CMD_INDEX_COMMIT, XS_CMD_OK_DB_COMMITED);
		} catch (XSException $e) {
			if ($e->getCode() === XS_CMD_ERR_BUSY || $e->getCode() === XS_CMD_ERR_RUNNING) {
				return false;
			}
			throw $e;
		}
		return true;
	}
	public function getCustomDict()
	{
		$res = $this->execCommand(XS_CMD_INDEX_USER_DICT, XS_CMD_OK_INFO);
		return $res->buf;
	}
	public function setCustomDict($content)
	{
		$cmd = array('cmd' => XS_CMD_INDEX_USER_DICT, 'arg1' => 1, 'buf' => $content);
		$this->execCommand($cmd, XS_CMD_OK_DICT_SAVED);
	}
	public function close($ioerr = false)
	{
		$this->closeBuffer();
		parent::close($ioerr);
	}
	private function appendBuffer($buf)
	{
		$this->_buf .= $buf;
		if (strlen($this->_buf) >= $this->_bufSize) {
			$this->addExdata($this->_buf, false);
			$this->_buf = '';
		}
	}
	public function __destruct()
	{
		if ($this->_rebuild === true) {
			try {
				$this->endRebuild();
			} catch (Exception $e) {
			}
		}
		foreach (self::$_adds as $srv) {
			$srv->close();
		}
		self::$_adds = array();
		parent::__destruct();
	}
}
class XSSearch extends XSServer
{
	const PAGE_SIZE = 10;
	const LOG_DB = 'log_db';
	private $_defaultOp = XS_CMD_QUERY_OP_AND;
	private $_prefix, $_fieldSet, $_resetScheme = false;
	private $_query, $_terms, $_count;
	private $_lastCount, $_highlight;
	private $_curDb, $_curDbs = array();
	private $_lastDb, $_lastDbs = array();
	private $_facets = array();
	private $_limit = 0, $_offset = 0;
	private $_charset = 'UTF-8';
	public function open($conn)
	{
		parent::open($conn);
		$this->_prefix = array();
		$this->_fieldSet = false;
		$this->_lastCount = false;
	}
	public function setCharset($charset)
	{
		$this->_charset = strtoupper($charset);
		if ($this->_charset == 'UTF8') {
			$this->_charset = 'UTF-8';
		}
		return $this;
	}
	public function setFuzzy($value = true)
	{
		$this->_defaultOp = $value === true ? XS_CMD_QUERY_OP_OR : XS_CMD_QUERY_OP_AND;
		return $this;
	}
	public function setCutOff($percent, $weight = 0)
	{
		$percent = max(0, min(100, intval($percent)));
		$weight = max(0, (intval($weight * 10) & 255));
		$cmd = new XSCommand(XS_CMD_SEARCH_SET_CUTOFF, $percent, $weight);
		$this->execCommand($cmd);
		return $this;
	}
	public function setRequireMatchedTerm($value = true)
	{
		$arg1 = XS_CMD_SEARCH_MISC_MATCHED_TERM;
		$arg2 = $value === true ? 1 : 0;
		$cmd = new XSCommand(XS_CMD_SEARCH_SET_MISC, $arg1, $arg2);
		$this->execCommand($cmd);
		return $this;
	}
	public function setAutoSynonyms($value = true)
	{
		$flag = XS_CMD_PARSE_FLAG_BOOLEAN | XS_CMD_PARSE_FLAG_PHRASE | XS_CMD_PARSE_FLAG_LOVEHATE;
		if ($value === true) {
			$flag |= XS_CMD_PARSE_FLAG_AUTO_MULTIWORD_SYNONYMS;
		}
		$cmd = array('cmd' => XS_CMD_QUERY_PARSEFLAG, 'arg' => $flag);
		$this->execCommand($cmd);
		return $this;
	}
	public function setSynonymScale($value)
	{
		$arg1 = XS_CMD_SEARCH_MISC_SYN_SCALE;
		$arg2 = max(0, (intval($value * 100) & 255));
		$cmd = new XSCommand(XS_CMD_SEARCH_SET_MISC, $arg1, $arg2);
		$this->execCommand($cmd);
		return $this;
	}
	public function getAllSynonyms($limit = 0, $offset = 0, $stemmed = false)
	{
		$page = $limit > 0 ? pack('II', intval($offset), intval($limit)) : '';
		$cmd = array('cmd' => XS_CMD_SEARCH_GET_SYNONYMS, 'buf1' => $page);
		$cmd['arg1'] = $stemmed == true ? 1 : 0;
		$res = $this->execCommand($cmd, XS_CMD_OK_RESULT_SYNONYMS);
		$ret = array();
		if (!empty($res->buf)) {
			foreach (explode("\n", $res->buf) as $line) {
				$value = explode("\t", $line);
				$key = array_shift($value);
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
	public function getSynonyms($term)
	{
		$term = strval($term);
		if (strlen($term) === 0) {
			return false;
		}
		$cmd = array('cmd' => XS_CMD_SEARCH_GET_SYNONYMS, 'arg1' => 2, 'buf' => $term);
		$res = $this->execCommand($cmd, XS_CMD_OK_RESULT_SYNONYMS);
		$ret = $res->buf === '' ? array() : explode("\n", $res->buf);
		return $ret;
	}
	public function getQuery($query = null)
	{
		$query = $query === null ? '' : $this->preQueryString($query);
		$cmd = new XSCommand(XS_CMD_QUERY_GET_STRING, 0, $this->_defaultOp, $query);
		$res = $this->execCommand($cmd, XS_CMD_OK_QUERY_STRING);
		if (strpos($res->buf, 'VALUE_RANGE') !== false) {
			$regex = '/(VALUE_RANGE) (\d+) (\S+) (.+?)(?=\))/';
			$res->buf = preg_replace_callback($regex, array($this, 'formatValueRange'), $res->buf);
		}
		if (strpos($res->buf, 'VALUE_GE') !== false || strpos($res->buf, 'VALUE_LE') !== false) {
			$regex = '/(VALUE_[GL]E) (\d+) (.+?)(?=\))/';
			$res->buf = preg_replace_callback($regex, array($this, 'formatValueRange'), $res->buf);
		}
		return XS::convert($res->buf, $this->_charset, 'UTF-8');
	}
	public function setQuery($query)
	{
		$this->clearQuery();
		if ($query !== null) {
			$this->_query = $query;
			$this->addQueryString($query);
		}
		return $this;
	}
	public function setMultiSort($fields, $reverse = false, $relevance_first = false)
	{
		if (!is_array($fields)) {
			return $this->setSort($fields, !$reverse, $relevance_first);
		}
		$buf = '';
		foreach ($fields as $key => $value) {
			if (is_bool($value)) {
				$vno = $this->xs->getField($key, true)->vno;
				$asc = $value;
			} else {
				$vno = $this->xs->getField($value, true)->vno;
				$asc = false;
			}
			if ($vno != XSFieldScheme::MIXED_VNO) {
				$buf .= chr($vno) . chr($asc ? 1 : 0);
			}
		}
		if ($buf !== '') {
			$type = XS_CMD_SORT_TYPE_MULTI;
			if ($relevance_first) {
				$type |= XS_CMD_SORT_FLAG_RELEVANCE;
			}
			if (!$reverse) {
				$type |= XS_CMD_SORT_FLAG_ASCENDING;
			}
			$cmd = new XSCommand(XS_CMD_SEARCH_SET_SORT, $type, 0, $buf);
			$this->execCommand($cmd);
		}
		return $this;
	}
	public function setSort($field, $asc = false, $relevance_first = false)
	{
		if (is_array($field)) {
			return $this->setMultiSort($field, $asc, $relevance_first);
		}
		if ($field === null) {
			$cmd = new XSCommand(XS_CMD_SEARCH_SET_SORT, XS_CMD_SORT_TYPE_RELEVANCE);
		} else {
			$type = XS_CMD_SORT_TYPE_VALUE;
			if ($relevance_first) {
				$type |= XS_CMD_SORT_FLAG_RELEVANCE;
			}
			if ($asc) {
				$type |= XS_CMD_SORT_FLAG_ASCENDING;
			}
			$vno = $this->xs->getField($field, true)->vno;
			$cmd = new XSCommand(XS_CMD_SEARCH_SET_SORT, $type, $vno);
		}
		$this->execCommand($cmd);
		return $this;
	}
	public function setDocOrder($asc = false)
	{
		$type = XS_CMD_SORT_TYPE_DOCID | ($asc ? XS_CMD_SORT_FLAG_ASCENDING : 0);
		$cmd = new XSCommand(XS_CMD_SEARCH_SET_SORT, $type);
		$this->execCommand($cmd);
		return $this;
	}
	public function setCollapse($field, $num = 1)
	{
		$vno = $field === null ? XSFieldScheme::MIXED_VNO : $this->xs->getField($field, true)->vno;
		$max = min(255, intval($num));
		$cmd = new XSCommand(XS_CMD_SEARCH_SET_COLLAPSE, $max, $vno);
		$this->execCommand($cmd);
		return $this;
	}
	public function addRange($field, $from, $to)
	{
		if ($from === '' || $from === false) {
			$from = null;
		}
		if ($to === '' || $to === false) {
			$to = null;
		}
		if ($from !== null || $to !== null) {
			if (strlen($from) > 255 || strlen($to) > 255) {
				throw new XSException('Value of range is too long');
			}
			$vno = $this->xs->getField($field)->vno;
			$from = XS::convert($from, 'UTF-8', $this->_charset);
			$to = XS::convert($to, 'UTF-8', $this->_charset);
			if ($from === null) {
				$cmd = new XSCommand(XS_CMD_QUERY_VALCMP, XS_CMD_QUERY_OP_FILTER, $vno, $to, chr(XS_CMD_VALCMP_LE));
			} elseif ($to === null) {
				$cmd = new XSCommand(XS_CMD_QUERY_VALCMP, XS_CMD_QUERY_OP_FILTER, $vno, $from, chr(XS_CMD_VALCMP_GE));
			} else {
				$cmd = new XSCommand(XS_CMD_QUERY_RANGE, XS_CMD_QUERY_OP_FILTER, $vno, $from, $to);
			}
			$this->execCommand($cmd);
		}
		return $this;
	}
	public function addWeight($field, $term, $weight = 1)
	{
		return $this->addQueryTerm($field, $term, XS_CMD_QUERY_OP_AND_MAYBE, $weight);
	}
	public function setFacets($field, $exact = false)
	{
		$buf = '';
		if (!is_array($field)) {
			$field = array($field);
		}
		foreach ($field as $name) {
			$ff = $this->xs->getField($name);
			if ($ff->type !== XSFieldMeta::TYPE_STRING) {
				throw new XSException("Field `$name' cann't be used for facets search, can only be string type");
			}
			$buf .= chr($ff->vno);
		}
		$cmd = array('cmd' => XS_CMD_SEARCH_SET_FACETS, 'buf' => $buf);
		$cmd['arg1'] = $exact === true ? 1 : 0;
		$this->execCommand($cmd);
		return $this;
	}
	public function getFacets($field = null)
	{
		if ($field === null) {
			return $this->_facets;
		}
		return isset($this->_facets[$field]) ? $this->_facets[$field] : array();
	}
	public function setScwsMulti($level)
	{
		$level = intval($level);
		if ($level >= 0 && $level < 16) {
			$cmd = array('cmd' => XS_CMD_SEARCH_SCWS_SET, 'arg1' => XS_CMD_SCWS_SET_MULTI, 'arg2' => $level);
			$this->execCommand($cmd);
		}
		return $this;
	}
	public function setLimit($limit, $offset = 0)
	{
		$this->_limit = intval($limit);
		$this->_offset = intval($offset);
		return $this;
	}
	public function setDb($name)
	{
		$name = strval($name);
		$this->execCommand(array('cmd' => XS_CMD_SEARCH_SET_DB, 'buf' => strval($name)));
		$this->_lastDb = $this->_curDb;
		$this->_lastDbs = $this->_curDbs;
		$this->_curDb = $name;
		$this->_curDbs = array();
		return $this;
	}
	public function addDb($name)
	{
		$name = strval($name);
		$this->execCommand(array('cmd' => XS_CMD_SEARCH_ADD_DB, 'buf' => $name));
		$this->_curDbs[] = $name;
		return $this;
	}
	public function markResetScheme()
	{
		$this->_resetScheme = true;
	}
	public function terms($query = null, $convert = true)
	{
		$query = $query === null ? '' : $this->preQueryString($query);
		if ($query === '' && $this->_terms !== null) {
			$ret = $this->_terms;
		} else {
			$cmd = new XSCommand(XS_CMD_QUERY_GET_TERMS, 0, $this->_defaultOp, $query);
			$res = $this->execCommand($cmd, XS_CMD_OK_QUERY_TERMS);
			$ret = array();
			$tmps = explode(' ', $res->buf);
			for ($i = 0; $i < count($tmps); $i++) {
				if ($tmps[$i] === '' || strpos($tmps[$i], ':') !== false) {
					continue;
				}
				$ret[] = $tmps[$i];
			}
			if ($query === '') {
				$this->_terms = $ret;
			}
		}
		return $convert ? XS::convert($ret, $this->_charset, 'UTF-8') : $ret;
	}
	public function count($query = null)
	{
		$query = $query === null ? '' : $this->preQueryString($query);
		if ($query === '' && $this->_count !== null) {
			return $this->_count;
		}
		$cmd = new XSCommand(XS_CMD_SEARCH_GET_TOTAL, 0, $this->_defaultOp, $query);
		$res = $this->execCommand($cmd, XS_CMD_OK_SEARCH_TOTAL);
		$ret = unpack('Icount', $res->buf);
		if ($query === '') {
			$this->_count = $ret['count'];
		}
		return $ret['count'];
	}
	public function search($query = null, $saveHighlight = true)
	{
		if ($this->_curDb !== self::LOG_DB && $saveHighlight) {
			$this->_highlight = $query;
		}
		$query = $query === null ? '' : $this->preQueryString($query);
		$page = pack('II', $this->_offset, $this->_limit > 0 ? $this->_limit : self::PAGE_SIZE);
		$cmd = new XSCommand(XS_CMD_SEARCH_GET_RESULT, 0, $this->_defaultOp, $query, $page);
		$res = $this->execCommand($cmd, XS_CMD_OK_RESULT_BEGIN);
		$tmp = unpack('Icount', $res->buf);
		$this->_lastCount = $tmp['count'];
		$ret = $this->_facets = array();
		$vnoes = $this->xs->getScheme()->getVnoMap();
		while (true) {
			$res = $this->getRespond();
			if ($res->cmd == XS_CMD_SEARCH_RESULT_FACETS) {
				$off = 0;
				while (($off + 6) < strlen($res->buf)) {
					$tmp = unpack('Cvno/Cvlen/Inum', substr($res->buf, $off, 6));
					if (isset($vnoes[$tmp['vno']])) {
						$name = $vnoes[$tmp['vno']];
						$value = substr($res->buf, $off + 6, $tmp['vlen']);
						if (!isset($this->_facets[$name])) {
							$this->_facets[$name] = array();
						}
						$this->_facets[$name][$value] = $tmp['num'];
					}
					$off += $tmp['vlen'] + 6;
				}
			} elseif ($res->cmd == XS_CMD_SEARCH_RESULT_DOC) {
				$doc = new XSDocument($res->buf, $this->_charset);
				$ret[] = $doc;
			} elseif ($res->cmd == XS_CMD_SEARCH_RESULT_FIELD) {
				if (isset($doc)) {
					$name = isset($vnoes[$res->arg]) ? $vnoes[$res->arg] : $res->arg;
					$doc->setField($name, $res->buf);
				}
			} elseif ($res->cmd == XS_CMD_SEARCH_RESULT_MATCHED) {
				if (isset($doc)) {
					$doc->setField('matched', explode(' ', $res->buf), true);
				}
			} elseif ($res->cmd == XS_CMD_OK && $res->arg == XS_CMD_OK_RESULT_END) {
				break;
			} else {
				$msg = 'Unexpected respond in search {CMD:' . $res->cmd . ', ARG:' . $res->arg . '}';
				throw new XSException($msg);
			}
		}
		if ($query === '') {
			$this->_count = $this->_lastCount;
			if ($this->_curDb !== self::LOG_DB) {
				$this->logQuery();
				if ($saveHighlight) {
					$this->initHighlight();
				}
			}
		}
		$this->_limit = $this->_offset = 0;
		return $ret;
	}
	public function getLastCount()
	{
		return $this->_lastCount;
	}
	public function getDbTotal()
	{
		$cmd = new XSCommand(XS_CMD_SEARCH_DB_TOTAL);
		$res = $this->execCommand($cmd, XS_CMD_OK_DB_TOTAL);
		$tmp = unpack('Itotal', $res->buf);
		return $tmp['total'];
	}
	public function getHotQuery($limit = 6, $type = 'total')
	{
		$ret = array();
		$limit = max(1, min(50, intval($limit)));
		$this->xs->setScheme(XSFieldScheme::logger());
		try {
			$this->setDb(self::LOG_DB)->setLimit($limit);
			if ($type !== 'lastnum' && $type !== 'currnum') {
				$type = 'total';
			}
			$result = $this->search($type . ':1');
			foreach ($result as $doc) /* @var $doc XSDocument */ {
				$body = $doc->body;
				$ret[$body] = $doc->f($type);
			}
			$this->restoreDb();
		} catch (XSException $e) {
			if ($e->getCode() != XS_CMD_ERR_XAPIAN) {
				throw $e;
			}
		}
		$this->xs->restoreScheme();
		return $ret;
	}
	public function getRelatedQuery($query = null, $limit = 6)
	{
		$ret = array();
		$limit = max(1, min(20, intval($limit)));
		if ($query === null) {
			$query = $this->cleanFieldQuery($this->_query);
		}
		if (empty($query) || strpos($query, ':') !== false) {
			return $ret;
		}
		$op = $this->_defaultOp;
		$this->xs->setScheme(XSFieldScheme::logger());
		try {
			$result = $this->setDb(self::LOG_DB)->setFuzzy()->setLimit($limit + 1)->search($query);
			foreach ($result as $doc) /* @var $doc XSDocument */ {
				$doc->setCharset($this->_charset);
				$body = $doc->body;
				if (!strcasecmp($body, $query)) {
					continue;
				}
				$ret[] = $body;
				if (count($ret) == $limit) {
					break;
				}
			}
		} catch (XSException $e) {
			if ($e->getCode() != XS_CMD_ERR_XAPIAN) {
				throw $e;
			}
		}
		$this->restoreDb();
		$this->xs->restoreScheme();
		$this->_defaultOp = $op;
		return $ret;
	}
	public function getExpandedQuery($query, $limit = 10)
	{
		$ret = array();
		$limit = max(1, min(20, intval($limit)));
		try {
			$buf = XS::convert($query, 'UTF-8', $this->_charset);
			$cmd = array('cmd' => XS_CMD_QUERY_GET_EXPANDED, 'arg1' => $limit, 'buf' => $buf);
			$res = $this->execCommand($cmd, XS_CMD_OK_RESULT_BEGIN);
			while (true) {
				$res = $this->getRespond();
				if ($res->cmd == XS_CMD_SEARCH_RESULT_FIELD) {
					$ret[] = XS::convert($res->buf, $this->_charset, 'UTF-8');
				} elseif ($res->cmd == XS_CMD_OK && $res->arg == XS_CMD_OK_RESULT_END) {
					break;
				} else {
					$msg = 'Unexpected respond in search {CMD:' . $res->cmd . ', ARG:' . $res->arg . '}';
					throw new XSException($msg);
				}
			}
		} catch (XSException $e) {
			if ($e->getCode() != XS_CMD_ERR_XAPIAN) {
				throw $e;
			}
		}
		return $ret;
	}
	public function getCorrectedQuery($query = null)
	{
		$ret = array();
		try {
			if ($query === null) {
				if ($this->_count > 0 && $this->_count > ceil($this->getDbTotal() * 0.001)) {
					return $ret;
				}
				$query = $this->cleanFieldQuery($this->_query);
			}
			if (empty($query) || strpos($query, ':') !== false) {
				return $ret;
			}
			$buf = XS::convert($query, 'UTF-8', $this->_charset);
			$cmd = array('cmd' => XS_CMD_QUERY_GET_CORRECTED, 'buf' => $buf);
			$res = $this->execCommand($cmd, XS_CMD_OK_QUERY_CORRECTED);
			if ($res->buf !== '') {
				$ret = explode("\n", XS::convert($res->buf, $this->_charset, 'UTF-8'));
			}
		} catch (XSException $e) {
			if ($e->getCode() != XS_CMD_ERR_XAPIAN) {
				throw $e;
			}
		}
		return $ret;
	}
	public function addSearchLog($query, $wdf = 1)
	{
		$cmd = array('cmd' => XS_CMD_SEARCH_ADD_LOG, 'buf' => $query);
		if ($wdf > 1) {
			$cmd['buf1'] = pack('i', $wdf);
		}
		$this->execCommand($cmd, XS_CMD_OK_LOGGED);
	}
	public function highlight($value, $strtr = false)
	{
		if (empty($value)) {
			return $value;
		}
		if (!is_array($this->_highlight)) {
			$this->initHighlight();
		}
		if (isset($this->_highlight['pattern'])) {
			$value = preg_replace($this->_highlight['pattern'], $this->_highlight['replace'], $value);
		}
		if (isset($this->_highlight['pairs'])) {
			$value = $strtr ?
				strtr($value, $this->_highlight['pairs']) :
				str_replace(array_keys($this->_highlight['pairs']), array_values($this->_highlight['pairs']), $value);
		}
		return $value;
	}
	private function logQuery($query = null)
	{
		if ($this->isRobotAgent()) {
			return;
		}
		if ($query !== '' && $query !== null) {
			$terms = $this->terms($query, false);
		} else {
			$query = $this->_query;
			if (!$this->_lastCount || ($this->_defaultOp == XS_CMD_QUERY_OP_OR && strpos($query, ' '))
				|| strpos($query, ' OR ') || strpos($query, ' NOT ') || strpos($query, ' XOR ')) {
				return;
			}
			$terms = $this->terms(null, false);
		}
		$log = '';
		$pos = $max = 0;
		foreach ($terms as $term) {
			$pos1 = ($pos > 3 && strlen($term) === 6) ? $pos - 3 : $pos;
			if (($pos2 = strpos($query, $term, $pos1)) === false) {
				continue;
			}
			if ($pos2 === $pos) {
				$log .= $term;
			} elseif ($pos2 < $pos) {
				$log .= substr($term, 3);
			} else {
				if (++$max > 3 || strlen($log) > 42) {
					break;
				}
				$log .= ' ' . $term;
			}
			$pos = $pos2 + strlen($term);
		}
		$log = trim($log);
		if (strlen($log) < 2 || (strlen($log) == 3 && ord($log[0]) > 0x80)) {
			return;
		}
		$this->addSearchLog($log);
	}
	private function clearQuery()
	{
		$cmd = new XSCommand(XS_CMD_QUERY_INIT);
		if ($this->_resetScheme === true) {
			$cmd->arg1 = 1;
			$this->_prefix = array();
			$this->_fieldSet = false;
			$this->_resetScheme = false;
		}
		$this->execCommand($cmd);
		$this->_query = $this->_count = $this->_terms = null;
	}
	public function addQueryString($query, $addOp = XS_CMD_QUERY_OP_AND, $scale = 1)
	{
		$query = $this->preQueryString($query);
		$bscale = ($scale > 0 && $scale != 1) ? pack('n', intval($scale * 100)) : '';
		$cmd = new XSCommand(XS_CMD_QUERY_PARSE, $addOp, $this->_defaultOp, $query, $bscale);
		$this->execCommand($cmd);
		return $query;
	}
	public function addQueryTerm($field, $term, $addOp = XS_CMD_QUERY_OP_AND, $scale = 1)
	{
		$term = strtolower($term);
		$term = XS::convert($term, 'UTF-8', $this->_charset);
		$bscale = ($scale > 0 && $scale != 1) ? pack('n', intval($scale * 100)) : '';
		$vno = $field === null ? XSFieldScheme::MIXED_VNO : $this->xs->getField($field, true)->vno;
		$cmd = new XSCommand(XS_CMD_QUERY_TERM, $addOp, $vno, $term, $bscale);
		$this->execCommand($cmd);
		return $this;
	}
	private function restoreDb()
	{
		$db = $this->_lastDb;
		$dbs = $this->_lastDbs;
		$this->setDb($db);
		foreach ($dbs as $name) {
			$this->addDb($name);
		}
	}
	private function preQueryString($query)
	{
		$query = trim($query);
		if ($this->_resetScheme === true) {
			$this->clearQuery();
		}
		$this->initSpecialField();
		$newQuery = '';
		$parts = preg_split('/[ \t\r\n]+/', $query);
		foreach ($parts as $part) {
			if ($part === '') {
				continue;
			}
			if ($newQuery != '') {
				$newQuery .= ' ';
			}
			if (($pos = strpos($part, ':', 1)) !== false) {
				for ($i = 0; $i < $pos; $i++) {
					if (strpos('+-~(', $part[$i]) === false) {
						break;
					}
				}
				$name = substr($part, $i, $pos - $i);
				if (($field = $this->xs->getField($name, false)) !== false
					&& $field->vno != XSFieldScheme::MIXED_VNO) {
					$this->regQueryPrefix($name);
					if ($field->hasCustomTokenizer()) {
						$prefix = $i > 0 ? substr($part, 0, $i) : '';
						$suffix = '';
						$value = substr($part, $pos + 1);
						if (substr($value, -1, 1) === ')') {
							$suffix = ')';
							$value = substr($value, 0, -1);
						}
						$terms = array();
						$tokens = $field->getCustomTokenizer()->getTokens($value);
						foreach ($tokens as $term) {
							$terms[] = strtolower($term);
						}
						$terms = array_unique($terms);
						$newQuery .= $prefix . $name . ':' . implode(' ' . $name . ':', $terms) . $suffix;
					} elseif (substr($part, $pos + 1, 1) != '(' && preg_match('/[\x81-\xfe]/', $part)) {
						$newQuery .= substr($part, 0, $pos + 1) . '(' . substr($part, $pos + 1) . ')';
					} else {
						$newQuery .= $part;
					}
					continue;
				}
			}
			if (strlen($part) > 1 && ($part[0] == '+' || $part[0] == '-') && $part[1] != '('
				&& preg_match('/[\x81-\xfe]/', $part)) {
				$newQuery .= substr($part, 0, 1) . '(' . substr($part, 1) . ')';
				continue;
			}
			$newQuery .= $part;
		}
		return XS::convert($newQuery, 'UTF-8', $this->_charset);
	}
	private function regQueryPrefix($name)
	{
		if (!isset($this->_prefix[$name])
			&& ($field = $this->xs->getField($name, false))
			&& ($field->vno != XSFieldScheme::MIXED_VNO)) {
			$type = $field->isBoolIndex() ? XS_CMD_PREFIX_BOOLEAN : XS_CMD_PREFIX_NORMAL;
			$cmd = new XSCommand(XS_CMD_QUERY_PREFIX, $type, $field->vno, $name);
			$this->execCommand($cmd);
			$this->_prefix[$name] = true;
		}
	}
	private function initSpecialField()
	{
		if ($this->_fieldSet === true) {
			return;
		}
		foreach ($this->xs->getAllFields() as $field) /* @var $field XSFieldMeta */ {
			if ($field->cutlen != 0) {
				$len = min(127, ceil($field->cutlen / 10));
				$cmd = new XSCommand(XS_CMD_SEARCH_SET_CUT, $len, $field->vno);
				$this->execCommand($cmd);
			}
			if ($field->isNumeric()) {
				$cmd = new XSCommand(XS_CMD_SEARCH_SET_NUMERIC, 0, $field->vno);
				$this->execCommand($cmd);
			}
		}
		$this->_fieldSet = true;
	}
	private function cleanFieldQuery($query)
	{
		$query = strtr($query, array(' AND ' => ' ', ' OR ' => ' '));
		if (strpos($query, ':') !== false) {
			$regex = '/(^|\s)([0-9A-Za-z_\.-]+):([^\s]+)/';
			return preg_replace_callback($regex, array($this, 'cleanFieldCallback'), $query);
		}
		return $query;
	}
	private function cleanFieldCallback($match)
	{
		if (($field = $this->xs->getField($match[2], false)) === false) {
			return $match[0];
		}
		if ($field->isBoolIndex()) {
			return '';
		}
		if (substr($match[3], 0, 1) == '(' && substr($match[3], -1, 1) == ')') {
			$match[3] = substr($match[3], 1, -1);
		}
		return $match[1] . $match[3];
	}
	private function initHighlight()
	{
		$terms = array();
		$tmps = $this->terms($this->_highlight, false);
		for ($i = 0; $i < count($tmps); $i++) {
			if (strlen($tmps[$i]) !== 6 || ord(substr($tmps[$i], 0, 1)) < 0xc0) {
				$terms[] = XS::convert($tmps[$i], $this->_charset, 'UTF-8');
				continue;
			}
			for ($j = $i + 1; $j < count($tmps); $j++) {
				if (strlen($tmps[$j]) !== 6 || substr($tmps[$j], 0, 3) !== substr($tmps[$j - 1], 3, 3)) {
					break;
				}
			}
			if (($k = ($j - $i)) === 1) {
				$terms[] = XS::convert($tmps[$i], $this->_charset, 'UTF-8');
			} else {
				$i = $j - 1;
				while ($k--) {
					$j--;
					if ($k & 1) {
						$terms[] = XS::convert(substr($tmps[$j - 1], 0, 3) . $tmps[$j], $this->_charset, 'UTF-8');
					}
					$terms[] = XS::convert($tmps[$j], $this->_charset, 'UTF-8');
				}
			}
		}
		$pattern = $replace = $pairs = array();
		foreach ($terms as $term) {
			if (!preg_match('/[a-zA-Z]/', $term)) {
				$pairs[$term] = '<em>' . $term . '</em>';
			} else {
				$pattern[] = '/' . strtr($term, array('+' => '\\+', '/' => '\\/')) . '/i';
				$replace[] = '<em>$0</em>';
			}
		}
		$this->_highlight = array();
		if (count($pairs) > 0) {
			$this->_highlight['pairs'] = $pairs;
		}
		if (count($pattern) > 0) {
			$this->_highlight['pattern'] = $pattern;
			$this->_highlight['replace'] = $replace;
		}
	}
	private function formatValueRange($match)
	{
		$field = $this->xs->getField(intval($match[2]), false);
		if ($field === false) {
			return $match[0];
		}
		$val1 = $val2 = '~';
		if (isset($match[4])) {
			$val2 = $field->isNumeric() ? $this->xapianUnserialise($match[4]) : $match[4];
		}
		if ($match[1] === 'VALUE_LE') {
			$val2 = $field->isNumeric() ? $this->xapianUnserialise($match[3]) : $match[3];
		} else {
			$val1 = $field->isNumeric() ? $this->xapianUnserialise($match[3]) : $match[3];
		}
		return $field->name . ':[' . $val1 . ',' . $val2 . ']';
	}
	private function numfromstr($str, $index)
	{
		return $index < strlen($str) ? ord($str[$index]) : 0;
	}
	private function xapianUnserialise($value)
	{
		if ($value === "\x80") {
			return 0.0;
		}
		if ($value === str_repeat("\xff", 9)) {
			return INF;
		}
		if ($value === '') {
			return -INF;
		}
		$i = 0;
		$c = ord($value[0]);
		$c ^= ($c & 0xc0) >> 1;
		$negative = !($c & 0x80) ? 1 : 0;
		$exponent_negative = ($c & 0x40) ? 1 : 0;
		$explen = !($c & 0x20) ? 1 : 0;
		$exponent = $c & 0x1f;
		if (!$explen) {
			$exponent >>= 2;
			if ($negative ^ $exponent_negative) {
				$exponent ^= 0x07;
			}
		} else {
			$c = $this->numfromstr($value, ++$i);
			$exponent <<= 6;
			$exponent |= ($c >> 2);
			if ($negative ^ $exponent_negative) {
				$exponent &= 0x07ff;
			}
		}
		$word1 = ($c & 0x03) << 24;
		$word1 |= $this->numfromstr($value, ++$i) << 16;
		$word1 |= $this->numfromstr($value, ++$i) << 8;
		$word1 |= $this->numfromstr($value, ++$i);
		$word2 = 0;
		if ($i < strlen($value)) {
			$word2 = $this->numfromstr($value, ++$i) << 24;
			$word2 |= $this->numfromstr($value, ++$i) << 16;
			$word2 |= $this->numfromstr($value, ++$i) << 8;
			$word2 |= $this->numfromstr($value, ++$i);
		}
		if (!$negative) {
			$word1 |= 1 << 26;
		} else {
			$word1 = 0 - $word1;
			if ($word2 != 0) {
				++$word1;
			}
			$word2 = 0 - $word2;
			$word1 &= 0x03ffffff;
		}
		$mantissa = 0;
		if ($word2) {
			$mantissa = $word2 / 4294967296.0; // 1<<32
		}
		$mantissa += $word1;
		$mantissa /= 1 << ($negative === 1 ? 26 : 27);
		if ($exponent_negative) {
			$exponent = 0 - $exponent;
		}
		$exponent += 8;
		if ($negative) {
			$mantissa = 0 - $mantissa;
		}
		return round($mantissa * pow(2, $exponent), 2);
	}
	private function isRobotAgent()
	{
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
			$keys = array('bot', 'slurp', 'spider', 'crawl', 'curl');
			foreach ($keys as $key) {
				if (strpos($agent, $key) !== false) {
					return true;
				}
			}
		}
		return false;
	}
}
class XSCommand extends XSComponent
{
	public $cmd = XS_CMD_NONE;
	public $arg1 = 0;
	public $arg2 = 0;
	public $buf = '';
	public $buf1 = '';
	public function __construct($cmd, $arg1 = 0, $arg2 = 0, $buf = '', $buf1 = '')
	{
		if (is_array($cmd)) {
			foreach ($cmd as $key => $value) {
				if ($key === 'arg' || property_exists($this, $key)) {
					$this->$key = $value;
				}
			}
		} else {
			$this->cmd = $cmd;
			$this->arg1 = $arg1;
			$this->arg2 = $arg2;
			$this->buf = $buf;
			$this->buf1 = $buf1;
		}
	}
	public function __toString()
	{
		if (strlen($this->buf1) > 0xff) {
			$this->buf1 = substr($this->buf1, 0, 0xff);
		}
		return pack('CCCCI', $this->cmd, $this->arg1, $this->arg2, strlen($this->buf1), strlen($this->buf)) . $this->buf . $this->buf1;
	}
	public function getArg()
	{
		return $this->arg2 | ($this->arg1 << 8);
	}
	public function setArg($arg)
	{
		$this->arg1 = ($arg >> 8) & 0xff;
		$this->arg2 = $arg & 0xff;
	}
}
class XSServer extends XSComponent
{
	const FILE = 0x01;
	const BROKEN = 0x02;
	public $xs;
	protected $_sock, $_conn;
	protected $_flag;
	protected $_project;
	protected $_sendBuffer;
	public function __construct($conn = null, $xs = null)
	{
		$this->xs = $xs;
		if ($conn !== null) {
			$this->open($conn);
		}
	}
	public function __destruct()
	{
		$this->xs = null;
		$this->close();
	}
	public function open($conn)
	{
		$this->close();
		$this->_conn = $conn;
		$this->_flag = self::BROKEN;
		$this->_sendBuffer = '';
		$this->_project = null;
		$this->connect();
		$this->_flag ^= self::BROKEN;
		if ($this->xs instanceof XS) {
			$this->setProject($this->xs->getName());
		}
	}
	public function reopen($force = false)
	{
		if ($this->_flag & self::BROKEN || $force === true) {
			$this->open($this->_conn);
		}
		return $this;
	}
	public function close($ioerr = false)
	{
		if ($this->_sock && !($this->_flag & self::BROKEN)) {
			if (!$ioerr && $this->_sendBuffer !== '') {
				$this->write($this->_sendBuffer);
				$this->_sendBuffer = '';
			}
			if (!$ioerr && !($this->_flag & self::FILE)) {
				$cmd = new XSCommand(XS_CMD_QUIT);
				fwrite($this->_sock, $cmd);
			}
			fclose($this->_sock);
			$this->_flag |= self::BROKEN;
		}
	}
	public function getConnString()
	{
		$str = $this->_conn;
		if (is_int($str) || is_numeric($str)) {
			$str = 'localhost:' . $str;
		} elseif (strpos($str, ':') === false) {
			$str = 'unix://' . $str;
		}
		return $str;
	}
	public function getSocket()
	{
		return $this->_sock;
	}
	public function getProject()
	{
		return $this->_project;
	}
	public function setProject($name, $home = '')
	{
		if ($name !== $this->_project) {
			$cmd = array('cmd' => XS_CMD_USE, 'buf' => $name, 'buf1' => $home);
			$this->execCommand($cmd, XS_CMD_OK_PROJECT);
			$this->_project = $name;
		}
	}
	public function setTimeout($sec)
	{
		$cmd = array('cmd' => XS_CMD_TIMEOUT, 'arg' => $sec);
		$this->execCommand($cmd, XS_CMD_OK_TIMEOUT_SET);
	}
	public function execCommand($cmd, $res_arg = XS_CMD_NONE, $res_cmd = XS_CMD_OK)
	{
		if (!$cmd instanceof XSCommand) {
			$cmd = new XSCommand($cmd);
		}
		if ($cmd->cmd & 0x80) {
			$this->_sendBuffer .= $cmd;
			return true;
		}
		$buf = $this->_sendBuffer . $cmd;
		$this->_sendBuffer = '';
		$this->write($buf);
		if ($this->_flag & self::FILE) {
			return true;
		}
		$res = $this->getRespond();
		if ($res->cmd === XS_CMD_ERR && $res_cmd != XS_CMD_ERR) {
			throw new XSException($res->buf, $res->arg);
		}
		if ($res->cmd != $res_cmd || ($res_arg != XS_CMD_NONE && $res->arg != $res_arg)) {
			throw new XSException('Unexpected respond {CMD:' . $res->cmd . ', ARG:' . $res->arg . '}');
		}
		return $res;
	}
	public function sendCommand($cmd)
	{
		if (!$cmd instanceof XSCommand) {
			$cmd = new XSCommand($cmd);
		}
		$this->write(strval($cmd));
	}
	public function getRespond()
	{
		$buf = $this->read(8);
		$hdr = unpack('Ccmd/Carg1/Carg2/Cblen1/Iblen', $buf);
		$res = new XSCommand($hdr);
		$res->buf = $this->read($hdr['blen']);
		$res->buf1 = $this->read($hdr['blen1']);
		return $res;
	}
	public function hasRespond()
	{
		if ($this->_sock === null || $this->_flag & (self::BROKEN | self::FILE)) {
			return false;
		}
		$wfds = $xfds = array();
		$rfds = array($this->_sock);
		$res = stream_select($rfds, $wfds, $xfds, 0, 0);
		return $res > 0;
	}
	protected function write($buf, $len = 0)
	{
		$buf = strval($buf);
		if ($len == 0 && ($len = $size = strlen($buf)) == 0) {
			return true;
		}
		$this->check();
		while (true) {
			$bytes = fwrite($this->_sock, $buf, $len);
			if ($bytes === false || $bytes === 0 || $bytes === $len) {
				break;
			}
			$len -= $bytes;
			$buf = substr($buf, $bytes);
		}
		if ($bytes === false || $bytes === 0) {
			$meta = stream_get_meta_data($this->_sock);
			$this->close(true);
			$reason = $meta['timed_out'] ? 'timeout' : ($meta['eof'] ? 'closed' : 'unknown');
			$msg = 'Failed to send the data to server completely ';
			$msg .= '(SIZE:' . ($size - $len) . '/' . $size . ', REASON:' . $reason . ')';
			throw new XSException($msg);
		}
	}
	protected function read($len)
	{
		if ($len == 0) {
			return '';
		}
		$this->check();
		for ($buf = '', $size = $len;;) {
			$bytes = fread($this->_sock, $len);
			if ($bytes === false || strlen($bytes) == 0) {
				break;
			}
			$len -= strlen($bytes);
			$buf .= $bytes;
			if ($len === 0) {
				return $buf;
			}
		}
		$meta = stream_get_meta_data($this->_sock);
		$this->close(true);
		$reason = $meta['timed_out'] ? 'timeout' : ($meta['eof'] ? 'closed' : 'unknown');
		$msg = 'Failed to recv the data from server completely ';
		$msg .= '(SIZE:' . ($size - $len) . '/' . $size . ', REASON:' . $reason . ')';
		throw new XSException($msg);
	}
	protected function check()
	{
		if ($this->_sock === null) {
			throw new XSException('No server connection');
		}
		if ($this->_flag & self::BROKEN) {
			throw new XSException('Broken server connection');
		}
	}
	protected function connect()
	{
		$conn = $this->_conn;
		if (is_int($conn) || is_numeric($conn)) {
			$host = 'localhost';
			$port = intval($conn);
		} elseif (!strncmp($conn, 'file://', 7)) {
			$conn = substr($conn, 7);
			if (($sock = @fopen($conn, 'wb')) === false) {
				throw new XSException('Failed to open local file for writing: `' . $conn . '\'');
			}
			$this->_flag |= self::FILE;
			$this->_sock = $sock;
			return;
		} elseif (($pos = strpos($conn, ':')) !== false) {
			$host = substr($conn, 0, $pos);
			$port = intval(substr($conn, $pos + 1));
		} else {
			$host = 'unix://' . $conn;
			$port = -1;
		}
		if (($sock = @fsockopen($host, $port, $errno, $error, 5)) === false) {
			throw new XSException($error . '(C#' . $errno . ', ' . $host . ':' . $port . ')');
		}
		$timeout = ini_get('max_execution_time');
		$timeout = $timeout > 0 ? ($timeout - 1) : 30;
		stream_set_blocking($sock, true);
		stream_set_timeout($sock, $timeout);
		$this->_sock = $sock;
	}
}
interface XSTokenizer
{
	const DFL = 0;
	public function getTokens($value, XSDocument $doc = null);
}
class XSTokenizerNone implements XSTokenizer
{
	public function getTokens($value, XSDocument $doc = null)
	{
		return array();
	}
}
class XSTokenizerFull implements XSTokenizer
{
	public function getTokens($value, XSDocument $doc = null)
	{
		return array($value);
	}
}
class XSTokenizerSplit implements XSTokenizer
{
	private $arg = ' ';
	public function __construct($arg = null)
	{
		if ($arg !== null && $arg !== '') {
			$this->arg = $arg;
		}
	}
	public function getTokens($value, XSDocument $doc = null)
	{
		if (strlen($this->arg) > 2 && substr($this->arg, 0, 1) == '/' && substr($this->arg, -1, 1) == '/') {
			return preg_split($this->arg, $value);
		}
		return explode($this->arg, $value);
	}
}
class XSTokenizerXlen implements XSTokenizer
{
	private $arg = 2;
	public function __construct($arg = null)
	{
		if ($arg !== null && $arg !== '') {
			$this->arg = intval($arg);
			if ($this->arg < 1 || $this->arg > 255) {
				throw new XSException('Invalid argument for ' . __CLASS__ . ': ' . $arg);
			}
		}
	}
	public function getTokens($value, XSDocument $doc = null)
	{
		$terms = array();
		for ($i = 0; $i < strlen($value); $i += $this->arg) {
			$terms[] = substr($value, $i, $this->arg);
		}
		return $terms;
	}
}
class XSTokenizerXstep implements XSTokenizer
{
	private $arg = 2;
	public function __construct($arg = null)
	{
		if ($arg !== null && $arg !== '') {
			$this->arg = intval($arg);
			if ($this->arg < 1 || $this->arg > 255) {
				throw new XSException('Invalid argument for ' . __CLASS__ . ': ' . $arg);
			}
		}
	}
	public function getTokens($value, XSDocument $doc = null)
	{
		$terms = array();
		$i = $this->arg;
		while (true) {
			$terms[] = substr($value, 0, $i);
			if ($i >= strlen($value)) {
				break;
			}
			$i += $this->arg;
		}
		return $terms;
	}
}
class XSTokenizerScws implements XSTokenizer
{
	const MULTI_MASK = 15;
	private static $_charset;
	private $_setting = array();
	private static $_server;
	public function __construct($arg = null)
	{
		if (self::$_server === null) {
			$xs = XS::getLastXS();
			if ($xs === null) {
				throw new XSException('An XS instance should be created before using ' . __CLASS__);
			}
			self::$_server = $xs->getScwsServer();
			self::$_server->setTimeout(0);
			self::$_charset = $xs->getDefaultCharset();
			if (!defined('SCWS_MULTI_NONE')) {
				define('SCWS_MULTI_NONE', 0);
				define('SCWS_MULTI_SHORT', 1);
				define('SCWS_MULTI_DUALITY', 2);
				define('SCWS_MULTI_ZMAIN', 4);
				define('SCWS_MULTI_ZALL', 8);
			}
			if (!defined('SCWS_XDICT_XDB')) {
				define('SCWS_XDICT_XDB', 1);
				define('SCWS_XDICT_MEM', 2);
				define('SCWS_XDICT_TXT', 4);
			}
		}
		if ($arg !== null && $arg !== '') {
			$this->setMulti($arg);
		}
	}
	public function getTokens($value, XSDocument $doc = null)
	{
		$tokens = array();
		$this->setIgnore(true);
		$_charset = self::$_charset;
		self::$_charset = 'UTF-8';
		$words = $this->getResult($value);
		foreach ($words as $word) {
			$tokens[] = $word['word'];
		}
		self::$_charset = $_charset;
		return $tokens;
	}
	public function setCharset($charset)
	{
		self::$_charset = strtoupper($charset);
		if (self::$_charset == 'UTF8') {
			self::$_charset = 'UTF-8';
		}
		return $this;
	}
	public function setIgnore($yes = true)
	{
		$this->_setting['ignore'] = new XSCommand(XS_CMD_SEARCH_SCWS_SET, XS_CMD_SCWS_SET_IGNORE, $yes === false
							? 0 : 1);
		return $this;
	}
	public function setMulti($mode = 3)
	{
		$mode = intval($mode) & self::MULTI_MASK;
		$this->_setting['multi'] = new XSCommand(XS_CMD_SEARCH_SCWS_SET, XS_CMD_SCWS_SET_MULTI, $mode);
		return $this;
	}
	public function setDict($fpath, $mode = null)
	{
		if (!is_int($mode)) {
			$mode = stripos($fpath, '.txt') !== false ? SCWS_XDICT_TXT : SCWS_XDICT_XDB;
		}
		$this->_setting['set_dict'] = new XSCommand(XS_CMD_SEARCH_SCWS_SET, XS_CMD_SCWS_SET_DICT, $mode, $fpath);
		unset($this->_setting['add_dict']);
		return $this;
	}
	public function addDict($fpath, $mode = null)
	{
		if (!is_int($mode)) {
			$mode = stripos($fpath, '.txt') !== false ? SCWS_XDICT_TXT : SCWS_XDICT_XDB;
		}
		if (!isset($this->_setting['add_dict'])) {
			$this->_setting['add_dict'] = array();
		}
		$this->_setting['add_dict'][] = new XSCommand(XS_CMD_SEARCH_SCWS_SET, XS_CMD_SCWS_ADD_DICT, $mode, $fpath);
		return $this;
	}
	public function setDuality($yes = true)
	{
		$this->_setting['duality'] = new XSCommand(XS_CMD_SEARCH_SCWS_SET, XS_CMD_SCWS_SET_DUALITY, $yes === false
							? 0 : 1);
		return $this;
	}
	public function getVersion()
	{
		$cmd = new XSCommand(XS_CMD_SEARCH_SCWS_GET, XS_CMD_SCWS_GET_VERSION);
		$res = self::$_server->execCommand($cmd, XS_CMD_OK_INFO);
		return $res->buf;
	}
	public function getResult($text)
	{
		$words = array();
		$text = $this->applySetting($text);
		$cmd = new XSCommand(XS_CMD_SEARCH_SCWS_GET, XS_CMD_SCWS_GET_RESULT, 0, $text);
		$res = self::$_server->execCommand($cmd, XS_CMD_OK_SCWS_RESULT);
		while ($res->buf !== '') {
			$tmp = unpack('Ioff/a4attr/a*word', $res->buf);
			$tmp['word'] = XS::convert($tmp['word'], self::$_charset, 'UTF-8');
			$words[] = $tmp;
			$res = self::$_server->getRespond();
		}
		return $words;
	}
	public function getTops($text, $limit = 10, $xattr = '')
	{
		$words = array();
		$text = $this->applySetting($text);
		$cmd = new XSCommand(XS_CMD_SEARCH_SCWS_GET, XS_CMD_SCWS_GET_TOPS, $limit, $text, $xattr);
		$res = self::$_server->execCommand($cmd, XS_CMD_OK_SCWS_TOPS);
		while ($res->buf !== '') {
			$tmp = unpack('Itimes/a4attr/a*word', $res->buf);
			$tmp['word'] = XS::convert($tmp['word'], self::$_charset, 'UTF-8');
			$words[] = $tmp;
			$res = self::$_server->getRespond();
		}
		return $words;
	}
	public function hasWord($text, $xattr)
	{
		$text = $this->applySetting($text);
		$cmd = new XSCommand(XS_CMD_SEARCH_SCWS_GET, XS_CMD_SCWS_HAS_WORD, 0, $text, $xattr);
		$res = self::$_server->execCommand($cmd, XS_CMD_OK_INFO);
		return $res->buf === 'OK';
	}
	private function applySetting($text)
	{
		self::$_server->reopen();
		foreach ($this->_setting as $key => $cmd) {
			if (is_array($cmd)) {
				foreach ($cmd as $_cmd) {
					self::$_server->execCommand($_cmd);
				}
			} else {
				self::$_server->execCommand($cmd);
			}
		}
		return XS::convert($text, 'UTF-8', self::$_charset);
	}
}
