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
 * @package    Zend_Pdf
 * @subpackage Destination
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/** Internally used classes */
// require_once 'Zend/Pdf/Element.php';


/** Zend_Pdf_Target */
// require_once 'Zend/Pdf/Target.php';


/**
 * Abstract PDF destination representation class
 *
 * @package    Zend_Pdf
 * @subpackage Destination
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Pdf_Destination extends Zend_Pdf_Target
{
    /**
     * Load Destination object from a specified resource
     *
     * @internal
     * @param Zend_Pdf_Element $resource
     * @return Zend_Pdf_Destination
     */
    #[\Override]
    public static function load(Zend_Pdf_Element $resource)
    {
        // require_once 'Zend/Pdf/Element.php';
        if ($resource->getType() == Zend_Pdf_Element::TYPE_NAME  ||  $resource->getType() == Zend_Pdf_Element::TYPE_STRING) {
            // require_once 'Zend/Pdf/Destination/Named.php';
            return new Zend_Pdf_Destination_Named($resource);
        }

        if ($resource->getType() != Zend_Pdf_Element::TYPE_ARRAY) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('An explicit destination must be a direct or an indirect array object.');
        }
        if (count($resource->items) < 2) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('An explicit destination array must contain at least two elements.');
        }

        return match ($resource->items[1]->value) {
            // require_once 'Zend/Pdf/Destination/Zoom.php';
            'XYZ' => new Zend_Pdf_Destination_Zoom($resource),
            // require_once 'Zend/Pdf/Destination/Fit.php';
            'Fit' => new Zend_Pdf_Destination_Fit($resource),
            // require_once 'Zend/Pdf/Destination/FitHorizontally.php';
            'FitH' => new Zend_Pdf_Destination_FitHorizontally($resource),
            // require_once 'Zend/Pdf/Destination/FitVertically.php';
            'FitV' => new Zend_Pdf_Destination_FitVertically($resource),
            // require_once 'Zend/Pdf/Destination/FitRectangle.php';
            'FitR' => new Zend_Pdf_Destination_FitRectangle($resource),
            // require_once 'Zend/Pdf/Destination/FitBoundingBox.php';
            'FitB' => new Zend_Pdf_Destination_FitBoundingBox($resource),
            // require_once 'Zend/Pdf/Destination/FitBoundingBoxHorizontally.php';
            'FitBH' => new Zend_Pdf_Destination_FitBoundingBoxHorizontally($resource),
            // require_once 'Zend/Pdf/Destination/FitBoundingBoxVertically.php';
            'FitBV' => new Zend_Pdf_Destination_FitBoundingBoxVertically($resource),
            // require_once 'Zend/Pdf/Destination/Unknown.php';
            default => new Zend_Pdf_Destination_Unknown($resource),
        };
    }
}
