<?php
namespace Lubos\Table\Test\TestCase\View\Helper;

use Cake\Controller;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Lubos\Table\View\Helper\TableHelper;

class TableHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        $Controller = new Controller();
        $View = new View(null);
        $this->Table = new TableHelper($View);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Table);
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplay()
    {
        $result = $this->Table->display();
        $expected = '<table><tr></tr></table>';
        $this->assertEquals($result, $expected);
    }
}
