<?php
namespace Lubos\Table\Test\TestCase\View\Helper;

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
     * testStartRow method
     */
    public function testDisplay()
    {
        $View = new View(null);

        // simple table
        $expected = '<table>';
        $expected .= '<tr><td>cell</td></tr>';
        $expected .= '</table>';
        $this->Table
            ->create()
            ->startRow()
            ->cell('cell')
            ->endRow();
        $result = $this->Table->display();
        $this->assertEquals($expected, $result);

        // table with class for row, thead group, tbody, tfoot
        $expected = '<table>';
        $expected .= '<thead><tr class="header"><th>header</th></tr></thead>';
        $expected .= '<tbody><tr><td>cell</td></tr></tbody>';
        $expected .= '<tfoot><tr><td>footer</td></tr></tfoot>';
        $expected .= '</table>';
        $this->Table
            ->create()
            ->startRow(['group' => 'head', 'class' => 'header'])
            ->header('header')
            ->endRow()
            ->startRow()
            ->cell('cell')
            ->endRow()
            ->startRow(['group' => 'foot'])
            ->cell('footer')
            ->endRow();
        $result = $this->Table->display();
        $this->assertEquals($expected, $result);
    }
}
