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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * @see Zend_Db_Adapter_Pdo_TestCommon
 */
require_once 'Zend/Db/Adapter/Pdo/TestCommon.php';


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Db
 * @group      Zend_Db_Adapter
 */
class Zend_Db_Adapter_Pdo_SqliteTest extends Zend_Db_Adapter_Pdo_TestCommon
{

    protected $_numericDataTypes = [
        Zend_Db::INT_TYPE    => Zend_Db::INT_TYPE,
        Zend_Db::BIGINT_TYPE => Zend_Db::BIGINT_TYPE,
        Zend_Db::FLOAT_TYPE  => Zend_Db::FLOAT_TYPE,
        'INTEGER'            => Zend_Db::BIGINT_TYPE,
        'REAL'               => Zend_Db::FLOAT_TYPE
    ];

    /**
     * Test AUTO_QUOTE_IDENTIFIERS option
     * Case: Zend_Db::AUTO_QUOTE_IDENTIFIERS = true
     *
     * SQLite actually allows delimited identifiers to remain
     * case-insensitive, so this test overrides its parent.
     */
    #[\Override]
    public function testAdapterAutoQuoteIdentifiersTrue()
    {
        $params = $this->_util->getParams();

        $params['options'] = [
            Zend_Db::AUTO_QUOTE_IDENTIFIERS => true
        ];
        $db = Zend_Db::factory($this->getDriver(), $params);
        $db->getConnection();

        $select = $this->_db->select();
        $select->from('zfproducts');
        $stmt = $this->_db->query($select);
        $result1 = $stmt->fetchAll();

        $this->assertEquals(1, $result1[0]['product_id']);

        $select = $this->_db->select();
        $select->from('ZFPRODUCTS');
        try {
            $stmt = $this->_db->query($select);
            $result2 = $stmt->fetchAll();
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Db_Statement_Exception,
                'Expecting object of type Zend_Db_Statement_Exception, got '.$e::class);
            $this->fail('Unexpected exception '.$e::class.' received: '.$e->getMessage());
        }

        $this->assertEquals($result1, $result2);
    }


    #[\Override]
    public function testAdapterConstructInvalidParamDbnameException()
    {
        $this->markTestSkipped($this->getDriver() . ' does not throw exception on missing dbname');
    }

    #[\Override]
    public function testAdapterConstructInvalidParamUsernameException()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support login credentials');
    }

    #[\Override]
    public function testAdapterConstructInvalidParamPasswordException()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support login credentials');
    }

    #[\Override]
    public function testAdapterInsertSequence()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support sequences');
    }

    /**
     * Used by:
     * - testAdapterOptionCaseFoldingNatural()
     * - testAdapterOptionCaseFoldingUpper()
     * - testAdapterOptionCaseFoldingLower()
     */
    #[\Override]
    protected function _testAdapterOptionCaseFoldingSetup(Zend_Db_Adapter_Abstract $db)
    {
        $db->getConnection();
        $this->_util->setUp($db);
    }

    /**
     * Test that quote() takes an array and returns
     * an imploded string of comma-separated, quoted elements.
     */
    #[\Override]
    public function testAdapterQuoteArray()
    {
        $array = ["it's", 'all', 'right!'];
        $value = $this->_db->quote($array);
        $this->assertEquals("'it''s', 'all', 'right!'", $value);
    }

    /**
     * test that quote() escapes a double-quote
     * character in a string.
     */
    #[\Override]
    public function testAdapterQuoteDoubleQuote()
    {
        $value = $this->_db->quote('St John"s Wort');
        $this->assertEquals("'St John\"s Wort'", $value);
    }

    /**
     * test that quote() escapes a single-quote
     * character in a string.
     */
    #[\Override]
    public function testAdapterQuoteSingleQuote()
    {
        $string = "St John's Wort";
        $value = $this->_db->quote($string);
        $this->assertEquals("'St John''s Wort'", $value);
    }

    /**
     * test that quoteInto() escapes a double-quote
     * character in a string.
     */
    #[\Override]
    public function testAdapterQuoteIntoDoubleQuote()
    {
        $value = $this->_db->quoteInto('id=?', 'St John"s Wort');
        $this->assertEquals("id='St John\"s Wort'", $value);
    }

    /**
     * test that quoteInto() escapes a single-quote
     * character in a string.
     */
    #[\Override]
    public function testAdapterQuoteIntoSingleQuote()
    {
        $value = $this->_db->quoteInto('id = ?', 'St John\'s Wort');
        $this->assertEquals("id = 'St John''s Wort'", $value);
    }

    #[\Override]
    public function testAdapterTransactionAutoCommit()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support transactions or concurrency');
    }

    #[\Override]
    public function testAdapterTransactionCommit()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support transactions or concurrency');
    }

    #[\Override]
    public function testAdapterTransactionRollback()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support transactions or concurrency');
    }

    /**
     * @group ZF-2293
     */
    public function testAdapterSupportsLengthInTableMetadataForVarcharFields()
    {
        $metadata = $this->_db->describeTable('zfbugs');
        $this->assertEquals(100, $metadata['bug_description']['LENGTH']);
        $this->assertEquals(20, $metadata['bug_status']['LENGTH']);
    }

    public function getDriver()
    {
        return 'Pdo_Sqlite';
    }

    #[\Override]
    public function testAdapterOptionFetchMode()
    {
        $params = $this->_util->getParams();

        $params['options'] = [
            Zend_Db::FETCH_MODE => 'obj'
        ];
        $db = Zend_Db::factory($this->getDriver(), $params);

        //two extra lines to make SQLite work
        $db->query('CREATE TABLE zfproducts (id)');
        $db->insert('zfproducts', ['id' => 1]);

        $select = $db->select()->from('zfproducts');
        $row = $db->fetchRow($select);
        $this->assertTrue($row instanceof stdClass);
    }

    #[\Override]
    protected function _testAdapterAlternateStatement($stmtClass)
    {
        $ip = get_include_path();
        $dir = __DIR__ . DIRECTORY_SEPARATOR .  '..' . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        $params = $this->_util->getParams();

        $params['options'] = [
            Zend_Db::AUTO_QUOTE_IDENTIFIERS => false
        ];
        $db = Zend_Db::factory($this->getDriver(), $params);
        $db->getConnection();
        $db->setStatementClass($stmtClass);

        $currentStmtClass = $db->getStatementClass();
        $this->assertEquals($stmtClass, $currentStmtClass);

        //extra fix for SQLite
        $db->query('CREATE TABLE zfbugs (id)');

        $bugs = $this->_db->quoteIdentifier('zfbugs');

        $stmt = $db->prepare("SELECT COUNT(*) FROM $bugs");

        $this->assertTrue($stmt instanceof $stmtClass,
            'Expecting object of type ' . $stmtClass . ', got ' . $stmt::class);
    }

    /**
     * test that quote() escapes null byte character
     * in a string.
     */
    #[\Override]
    public function testAdapterQuoteNullByteCharacter()
    {
        $string = "1\0";
        $value  = $this->_db->quote($string);
        $this->assertEquals("'1\\000'", $value);
    }

    /**
     * https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.pdo.sqlite
     * @inheritDoc
     */
    #[\Override]
    public function testAdapterZendConfigEmptyDriverOptions()
    {
        $params = $this->_util->getParams();
        $params['driver_options'] = '';
        $params = new Zend_Config($params);

        $db = Zend_Db::factory($this->getDriver(), $params);
        $db->getConnection();

        $config = $db->getConfig();

        if (PHP_VERSION_ID >= 80100) {
            $this->assertEquals([PDO::ATTR_STRINGIFY_FETCHES => true], $config['driver_options']);
        } else {
            $this->assertEquals([], $config['driver_options']);
        }
    }
}
