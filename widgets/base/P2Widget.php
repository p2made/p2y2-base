<?php
/**
 * P2Widget.php
 *
 * @copyright Copyright &copy; Pedro Plowman, 2017
 * @author Pedro Plowman
 * @link https://github.com/p2made
 * @package p2made/yii2-p2y2-things
 * @license MIT
 */

namespace p2m\widgets\base;

use yii\bootstrap\Html;

/**
 * Use this helper with...
 *
 * use p2m\widgets\base\P2Widget;
 * ...
 * echo P2Widget::method([$params]);
 *
 * or
 *
 * echo \p2m\widgets\base\P2Widget::method([$params]);
 */

/**
 * Base widget class for yii2-widgets
 *
 * @var public $options = [];
 * @var public $pluginOptions = [];
 * @var public $pluginEvents = [];
 * You must define events in
 * event-name => event-function format
 * for example:
 * ~~~
 * pluginEvents = [
 *     "change" => "function() { log("change"); }",
 *     "open" => "function() { log("open"); }",
 * ];
 * ~~~
 *
 * @var public $i18n = [];
 * @var protected $_msgCat = '';
 * @var protected $_pluginName;
 * @var protected $_hashVar;
 * @var protected $_dataVar;
 * @var protected $_encOptions = '';
 */

/**
 * Class P2Widget
 * @package p2m\yii2-p2y2-things
 */
class P2Widget extends \yii\base\Widget
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

	}

}
