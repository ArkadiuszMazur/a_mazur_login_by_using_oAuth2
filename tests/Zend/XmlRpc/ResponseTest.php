<?php
require_once dirname(__FILE__)."/../../TestHelper.php";
require_once 'Zend/XmlRpc/Response.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

/**
 * Test case for Zend_XmlRpc_Response
 *
 * @package Zend_XmlRpc
 * @subpackage UnitTests
 * @version $Id: ResponseTest.php 13599 2009-01-11 21:52:39Z beberlei $
 */
class Zend_XmlRpc_ResponseTest extends PHPUnit_Framework_TestCase 
{
    /**
     * Zend_XmlRpc_Response object
     * @var Zend_XmlRpc_Response
     */
    protected $_response;

    /**
     * Setup environment
     */
    public function setUp() 
    {
        $this->_response = new Zend_XmlRpc_Response();
    }

    /**
     * Teardown environment
     */
    public function tearDown() 
    {
        unset($this->_response);
    }

    /**
     * __construct() test
     */
    public function test__construct()
    {
        $this->assertTrue($this->_response instanceof Zend_XmlRpc_Response);
    }

    /**
     * get/setReturnValue() test
     */
    public function testReturnValue()
    {
        $this->_response->setReturnValue('string');
        $this->assertEquals('string', $this->_response->getReturnValue());

        $this->_response->setReturnValue(array('one', 'two'));
        $this->assertSame(array('one', 'two'), $this->_response->getReturnValue());
    }

    /**
     * isFault() test
     *
     * Call as method call 
     *
     * Returns: boolean 
     */
    public function testIsFault()
    {
        $this->assertFalse($this->_response->isFault());
        $this->_response->loadXml('foo');
        $this->assertTrue($this->_response->isFault());
    }

    /**
     * Tests getFault() returns NULL (no fault) or the fault object
     */
    public function testGetFault()
    {
        $this->assertNull($this->_response->getFault());
        $this->_response->loadXml('foo');
        $this->assertType('Zend_XmlRpc_Fault', $this->_response->getFault());
    }
    
    /**
     * loadXml() test
     *
     * Call as method call 
     *
     * Expects:
     * - response: 
     * 
     * Returns: boolean 
     */
    public function testLoadXml()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $response = $dom->appendChild($dom->createElement('methodResponse'));
        $params   = $response->appendChild($dom->createElement('params'));
        $param    = $params->appendChild($dom->createElement('param'));
        $value    = $param->appendChild($dom->createElement('value'));
        $value->appendChild($dom->createElement('string', 'Return value'));

        $xml = $dom->saveXML();

        $parsed = $this->_response->loadXml($xml);
        $this->assertTrue($parsed, $xml);
        $this->assertEquals('Return value', $this->_response->getReturnValue());
    }

    /**
     * @group ZF-5404
     */
    public function testNilResponseFromXmlRpcServer()
    {
        $rawResponse = <<<EOD
<methodResponse><params><param><value><array><data><value><struct><member><name>id</name><value><string>1</string></value></member><member><name>name</name><value><string>birdy num num!</string></value></member><member><name>description</name><value><nil/></value></member></struct></value></data></array></value></param></params></methodResponse>
EOD;
        try {
            $response = new Zend_XmlRpc_Response();
            $ret      = $response->loadXml($rawResponse);
        } catch(Exception $e) {
            $this->fail("Parsing the response should not throw an exception.");
        }

        $this->assertTrue($ret);
        $this->assertEquals(array(
            0 => array(
                'id'            => 1,
                'name'          => 'birdy num num!',
                'description'   => null,
            )
        ), $response->getReturnValue());
    }

    /**
     * helper for saveXML() and __toString() tests
     * 
     * @param string $xml 
     * @return void
     */
    protected function _testXmlResponse($xml)
    {
        try {
            $sx = new SimpleXMLElement($xml);
        } catch (Exception $e) {
            $this->fail('Invalid XML returned');
        }

        $this->assertTrue($sx->params ? true : false);
        $this->assertTrue($sx->params->param ? true : false);
        $this->assertTrue($sx->params->param->value ? true : false);
        $this->assertTrue($sx->params->param->value->string ? true : false);
        $this->assertEquals('return value', (string) $sx->params->param->value->string);
    }

    /**
     * saveXML() test
     */
    public function testSaveXML()
    {
        $this->_response->setReturnValue('return value');
        $xml = $this->_response->saveXML();
        $this->_testXmlResponse($xml);
    }

    /**
     * __toString() test
     */
    public function test__toString()
    {
        $this->_response->setReturnValue('return value');
        $xml = $this->_response->__toString();
        $this->_testXmlResponse($xml);
    }

    /**
     * Test encoding settings
     */
    public function testSetGetEncoding()
    {
        $this->assertEquals('UTF-8', $this->_response->getEncoding());
        $this->_response->setEncoding('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $this->_response->getEncoding());
    }

    public function testLoadXmlThrowsExceptionWithMissingNodes()
    {
        $sxl = new SimpleXMLElement('<?xml version="1.0"?><methodResponse><params><param>foo</param></params></methodResponse>');
        $this->_loadXml($sxl->asXML());
        $sxl = new SimpleXMLElement('<?xml version="1.0"?><methodResponse><params>foo</params></methodResponse>');
        $this->_loadXml($sxl->asXML());
        $sxl = new SimpleXMLElement('<?xml version="1.0"?><methodResponse><bar>foo</bar></methodResponse>');
        $this->_loadXml($sxl->asXML());
    }

    protected function _loadXml($xml)
    {
        try {
            $this->_response->loadXml($xml);
            $this->fail('Invalid XML-RPC response should raise an exception');
        } catch (Exception $e) {
        }
    }
}
