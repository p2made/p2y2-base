<?php
/**
 * P2AssetBase.php
 *
 * @author Pedro Plowman
 * @copyright Copyright &copy; Pedro Plowman, 2019
 * @link https://github.com/p2made
 * @license MIT
 *
 * @package p2made/yii2-p2y2-base
 * @class \p2m\base\assets\P2AssetBase
 */

/**
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ #####                                           ##### ^ #####
 * ##### ^ #####      DO NOT USE THIS CLASS DIRECTLY!      ##### ^ #####
 * ##### ^ #####                                           ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 */

/**
 * Load this asset with...
 * p2m\assets\base\P2AssetBase::register($this);
 *
 * or specify as a dependency with...
 *     'p2m\assets\base\P2AssetBase',
 */

namespace p2m\base\assets;

use p2m\base\helpers\P2AssetsSettings as Settings;

class P2AssetBase extends \yii\web\AssetBundle
{
	// ##### ^ ##### ^ P2M Asset Properties ^ ##### ^ #####

	/*
	 * @var string
	 * private $_p2mProjectId;
	 */
	protected $_p2mProjectId = 'p2y2-base';

	/*
	 * @var string
	 * protected $packageName;
	 * The simple name of the package that the asset is built on
	 */
	protected $packageName;

	/*
	 * @var string
	 * protected $packageVersion;
	 */
	protected $packageVersion; // = '0.0.0'

	/*
	 * @var array
	 * protected $packageData;
	 */
	protected $packageData = [];

	// ##### ^ ##### ^ P2 asset data structure ^ ##### ^ #####

	/*
	 *

	protected $packageData = [
		'baseUrl' => 'baseUrl',
		'sourcePath' => 'sourcePath',
		'static' => [
			'css' => [
				'css/cssfile.css',
				[
					'css/cssfile.css'
					'integrity' => 'static-hash', // iff css has hash[s]
					'crossorigin' => 'anonymous', // iff css has hash[s]
				],
			],
			'cssOptions' => [
				'integrity' => 'static-hash', // iff css has hash[s]
				'crossorigin' => 'anonymous', // iff css has hash[s]
			],
			'js' => [
				'js/jsfile.js',
				[
					'js/jsfile.js'
					'integrity' => 'static-hash', // iff js has hash[s]
					'crossorigin' => 'anonymous', // iff js has hash[s]
				],
			],
			'jsOptions' => [
				'integrity' => 'static-hash', // iff js has hash[s]
				'crossorigin' => 'anonymous', // iff js has hash[s]
			],
			'publishOptions' => [
			],
		],
		'published' => [
			'css' => [
				'css/cssfile.css',
			],
			'cssOptions' => [
			],
			'js' => [
				'js/jsfile.js',
			],
			'jsOptions' => [
			],
			'publishOptions' => [
			],
		],
		'css' => [
			'css/cssfile.css',
		],
		'cssOptions' => [
		],
		'js' => [
			'js/jsfile.js',
		],
		'jsOptions' => [
		],
		'publishOptions' => [
		],
		'depends' => [
			'some\useful\ThingAsset',
		],
	];

	 *
	 */

	// ##### ^ ##### ^ Private Properties ^ ##### ^ #####

	/*
	 * @var boolean
	 * private $_useStatic = false;
	 */
	private static $_useStatic;

	/*
	 * @var array | false
	 * private $_assetsEnd = false;
	 */
	private static $_assetsEnd;

	/*
	 * @var bool | false
	 * private $_assetsEnd = false;
	 */
	private static $_aliasSet = false;

	/*
	 * @var string
	 * private $_version;
	 */
	private $_version; // = '0.0.0'

	// ##### ^ ##### ^ Yii Asset VariaPropertiesles ^ ##### ^ #####

	/**
	 * @var string
	 * public $baseUrl;
	 *
	 * @var string
	 * public $sourcePath;
	 *
	 * @var array
	 * public $css = [];
	 *
	 * @var array
	 * public $js = [];
	 *
	 * @var array
	 * public $cssOptions = [];
	 *
	 * @var array
	 * public $jsOptions = [];
	 *
	 * @var array
	 * public $publishOptions = [];
	 *
	 * @var array
	 * public $depends = [];
	 */

	protected function configureAsset($data)
	{
		$insertVersion = function($source) {
			if(isset($this->packageVersion))
				return str_replace('##-version-##', $this->packageVersion, $source);
			return $source;
		};

		/*
		 * For easier access to p2m stuff we give it an alias
		 * but only if it hasn't already been set.
		 * the 2nd asset & after need different names.
		 */
		//self::setP2mAlias();
		if(!self::$_aliasSet) {
			\Yii::setAlias('@p2m',      '@vendor/p2made');
			\Yii::setAlias('@jsdelivr', 'https://cdn.jsdelivr.net/npm');
			\Yii::setAlias('@cdnjs',    'https://cdnjs.cloudflare.com/ajax/libs');
			self::$_aliasSet = true;
		}

		if(self::useStatic()) {
			$branch = 'static';
			if(isset($data['baseUrl']))
				$this->baseUrl = $insertVersion($data['baseUrl']);
		}
		else {
			$branch = 'published';
			if(isset($data['sourcePath']))
				$this->sourcePath = $insertVersion($data['sourcePath']);
		}

		if(isset($data[$branch])) {
			$branchData = $data[$branch];
			$dataTemp = $data;
			$data = array_merge($dataTemp, $branchData);
		}

		$yiiAttributes = [
			'css', 'cssOptions', 'js', 'jsOptions', 'publishOptions', 'depends'
		];

		foreach($yiiAttributes as $attribute) {
			if(isset($data[$attribute]))
				$this->{$attribute} = $data[$attribute];
		}
	}

	// ===== utility functions ===== //

	/**
	 * Get useStatic setting - use static resources
	 * @return boolean
	 * @default false
	 */
	protected static function useStatic()
	{
		if(isset(self::$_useStatic))
			return self::$_useStatic;

		self::$_useStatic = Settings::assetsUseStatic();

		return self::$_useStatic;
	}

	/**
	 * Get assetsEnd setting - static application end
	 * @return array | false
	 * @default false
	 */
	protected static function assetsEnd()
	{
		if(isset($_assetsEnd))
			return $_assetsEnd;

		$_assetsEnd = Settings::assetsassetsEnd();

		return $_assetsEnd;
	}
}

	/*
	protected function __construct($bypass = false, $config = [])
	{

		if($bypass) return;

		$data = $this->packageData;


		parent::__construct();
	}
	*/
?>

<?php
/**
 * P2AssetBase.php
 *
 * @copyright Copyright &copy; Pedro Plowman, 2017
 * @author Pedro Plowman
 * @link https://github.com/p2made
 * @license MIT
 *
 * @package p2made/yii2-p2y2-base
 * @class \p2m\base\assets\P2AssetBase
 */

/**
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ #####                                           ##### ^ #####
 * ##### ^ #####      DO NOT USE THIS CLASS DIRECTLY!      ##### ^ #####
 * ##### ^ #####                                           ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 * ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ ##### ^ #####
 */

namespace p2m\base\assets;

use p2m\base\helpers\AssetsSettings;

/**
 * Load this asset with...
 * p2m\assets\base\P2AssetBase::register($this);
 *
 * or specify as a dependency with...
 *     'p2m\assets\base\P2AssetBase',
 */
class P2AssetBase extends \yii\web\AssetBundle
{

	/*
	 * @var string
	 * private $_p2mPath;
	 */
	private $_p2mPath;

	/*
	 * @var boolean
	 * private $_useStatic = false;
	 */
	private static $_useStatic;

	/*
	 * @var array | false
	 * private $_assetsEnd = false;
	 */
	private static $_assetsEnd;

	/**
	 * @var string
	 * public $sourcePath;
	 *
	 * @var string
	 * public $baseUrl;
	 *
	 * @var array
	 * public $css = [];
	 *
	 * @var array
	 * public $cssOptions = [];
	 *
	 * @var array
	 * public $js = [];
	 *
	 * @var array
	 * public $jsOptions = [];
	 *
	 * @var array
	 * public $depends = [];
	 *
	 * @var array
	 * public $publishOptions = [];
	 */

	/*
return array(
	'assetName' => array(
		'version' => 'version',
		'published' => [
			'fullPath' => 'fullPath',
			'sourcePath' => 'sourcePath',
			'css' => [
			],
			'js' => [
			],
		],
		'static' => [
			'fullUrl' => 'fullUrl',
			'baseUrl' => 'baseUrl',
			'css' => [
			],
			'cssIntegrity' => 'cssIntegrity',
			'js' => [
			],
			'jsIntegrity' => 'jsIntegrity',
			'crossorigin' => 'anonymous',
		],
		'cssOptions' => [
		],
		'jsOptions' => [
		],
		'depends' => [
		],
		'publishOptions' => [
		],
	),
);
	 */

	protected function configureDataFileAsset()
	{
		if(self::useStatic() && isset($this->staticData)) {
			$this->configureStaticAsset($this->staticData);
		} elseif(isset($this->publishedData)) {
			$this->configurePublishedAsset($this->publishedData);
		} else {
			return;
		}
	}

	protected function configureAsset($assetData)
	{
		if(isset($assetData['cssOptions'])) {
			$this->cssOptions = $assetData['cssOptions'];
		}
		if(isset($assetData['jsOptions'])) {
			$this->jsOptions = $assetData['jsOptions'];
		}
		if(isset($assetData['depends'])) {
			$this->depends = $assetData['depends'];
		}
		if(isset($assetData['publishOptions'])) {
			$this->publishOptions = $assetData['publishOptions'];
		}

		if(self::useStatic() && isset($assetData['static'])) {
			$this->configureStaticAsset($assetData['static']);
		} elseif(isset($assetData['published'])) {
			$this->configurePublishedAsset($assetData['published']);
		} else {
			return;
		}
	}

	protected function configureStaticAsset($assetData)
	{
		if(isset($assetData['baseUrl'])) {
			$this->baseUrl = $assetData['baseUrl'];
			$this->insertAssetVersion($this->baseUrl);
		}
		if(isset($assetData['css'])) {
			$this->css = $assetData['css'];
		}
		if(isset($assetData['js'])) {
			$this->js = $assetData['js'];
		}
	}

	protected function configurePublishedAsset($assetData)
	{
		if(isset($assetData['sourcePath'])) {
			$this->sourcePath = $assetData['sourcePath'];
			$this->insertAssetVersion($this->sourcePath);
			$this->insertP2mPath($this->sourcePath);
		}

		if(isset($assetData['css'])) {
			$this->css = $assetData['css'];
		}
		if(isset($assetData['js'])) {
			$this->js = $assetData['js'];
		}
	}

	// ===== utility functions ===== //

	protected function p2mPath()
	{
		if(isset($this->_p2mPath)) {
			return $this->_p2mPath;
		}

		$this->_p2mPath = '@vendor/p2made/' . $this->_p2mProjectId . '/vendor';

		return $this->_p2mPath;
	}

	protected function insertP2mPath(&$target)
	{
		$target = str_replace('@p2m@', $this->p2mPath(), $target);
	}

	protected function insertAssetVersion(&$target)
	{
		if(isset($this->version)) {
			$target = str_replace('##-version-##', $this->version, $target);
		}
	}

	/**
	 * Get useStatic setting - use static resources
	 * @return boolean
	 * @default false
	 */
	protected static function useStatic()
	{
		if(isset(self::$_useStatic)) {
			return self::$_useStatic;
		}

		self::$_useStatic = AssetsSettings::assetsUseStatic();

		return self::$_useStatic;
	}

	/**
	 * Get assetsEnd setting - static application end
	 * @return array | false
	 * @default false
	 */
	protected static function assetsEnd()
	{
		if(isset($_assetsEnd)) {
			return $_assetsEnd;
		}

		$_assetsEnd = AssetsSettings::assetsassetsEnd();

		return $_assetsEnd;
	}
}
