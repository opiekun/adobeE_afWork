<?php
namespace Nextopia\Search\Test;

use Nextopia\Search\Helper\Data;
 
class DataTest extends \PHPUnit\Framework\TestCase
{
    protected $_objectManager;
    protected $_model;

    protected function setUp()
    {
        $this->_objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_model = $this->_objectManager->getObject("Nextopia\Search\Helper\Data");
    }

    public function tearDown()
    {
    }

    public function testIsEnabled()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('isEnabled')
             ->willReturn(true);
        $this->assertEquals(true, $stub->isEnabled());
    }

    public function testIsDemo()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('isDemo')
             ->willReturn(true);
        $this->assertEquals(true, $stub->isDemo());
    }

    public function testGetNxtId()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getNxtId')
             ->willReturn('NXT_ID');
        $this->assertEquals('NXT_ID', $stub->getNxtId());
    }

    public function testGetLoadingContent()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getLoadingContent')
             ->willReturn('CONTENT');
        $this->assertEquals('CONTENT', $stub->getLoadingContent());
    }

    public function testShowInNxtSearchPage()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('showInNxtSearchPage')
             ->willReturn(true);
        $this->assertEquals(true, $stub->showInNxtSearchPage());
    }

    public function testShowEverywhere()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('showEverywhere')
             ->willReturn(true);
        $this->assertEquals(true, $stub->showEverywhere());
    }

    public function testGetResultUrl()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getResultUrl')
             ->willReturn('NSEARCH_PATH');
        $this->assertEquals('NSEARCH_PATH', $stub->getResultUrl());
    }

    public function testGetGroupCode()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getGroupCode')
             ->willReturn('GROUP_CODE');
        $this->assertEquals('GROUP_CODE', $stub->getGroupCode());
    }

    public function testGetFormKey()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getFormKey')
             ->willReturn('FORM_KEY');
        $this->assertEquals('FORM_KEY', $stub->getFormKey());
    }

    public function testGetLabelSearchResultPage()
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getLabelSearchResultPage')
             ->willReturn('LABEL_SEARCH_RESULT_PAGE');
        $this->assertEquals('LABEL_SEARCH_RESULT_PAGE', $stub->getLabelSearchResultPage());
    }

    public function testGetCurrentStoreId() 
    {
        $stub = $this->createMock(\Nextopia\Search\Helper\Data::class);
        $stub->method('getCurrentStoreId')
             ->willReturn('STORE_ID');
        $this->assertEquals('STORE_ID', $stub->getCurrentStoreId());
    }

    public function testIsClientOnlineCodeAvailable()
    {
        $result = $this->_model->isClientOneLineCodeAvailable('TEST_CLIENT_ID');
        $this->assertEquals(false, $result);
    }

}
