<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Reflection
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** requires */
// require_once 'Zend/Reflection/Extension.php';

/**
 * @category   Zend
 * @package    Zend_Reflection
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Reflection
 * @group      Zend_Reflection_Extension
 */
class Zend_Reflection_ExtensionTest extends PHPUnit_Framework_TestCase
{

    public function testClassReturn()
    {
        $extension = new Zend_Reflection_Extension('Reflection');
        $extensionClasses = $extension->getClasses();
        $this->assertEquals(array_shift($extensionClasses)::class, 'Zend_Reflection_Class');
    }

    public function testFunctionReturn()
    {
        $extension = new Zend_Reflection_Extension('Spl');
        $extensionFunctions = $extension->getFunctions();
        $this->assertEquals(array_shift($extensionFunctions)::class, 'Zend_Reflection_Function');
    }
}

