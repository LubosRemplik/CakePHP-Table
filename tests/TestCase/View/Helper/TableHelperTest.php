<?php
namespace Lubos\Table\Test\TestCase\View\Helper;

use Cake\Network\Request;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Lubos\Table\View\Helper\TableHelper;

class TableHelperTest extends TestCase
{

    /**
     * testStartRow method
     */
    public function testDisplay()
    {
        // simple table
        $View = new View(null);
        $Table = new TableHelper($View);
        $expected = '<table>';
        $expected .= '<tr><td>cell</td></tr>';
        $expected .= '</table>';
        $Table
            ->create()
            ->startRow()
            ->cell('cell')
            ->endRow();
        $result = $Table->display();
        $this->assertEquals($expected, $result);
        unset($View);
        unset($Table);

        // table with class for row, thead group, tbody, tfoot
        $View = new View(null);
        $Table = new TableHelper($View);
        $expected = '<table>';
        $expected .= '<thead><tr class="header"><th>header</th></tr></thead>';
        $expected .= '<tbody><tr><td>cell</td></tr></tbody>';
        $expected .= '<tfoot><tr><td>footer</td></tr></tfoot>';
        $expected .= '</table>';
        $Table
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
        $result = $Table->display();
        $this->assertEquals($expected, $result);
        unset($View);
        unset($Table);

        // table with paging
        $Request = new Request([
            'params' => [
                'plugin' => null,
                'controller' => 'Articles',
                'action' => 'index',
                '_ext' => null,
                'pass' => [],
                'paging' => [
                    'Articles' => [
                        'finder' => 'all',
                        'page' => 1,
                        'current' => 1,
                        'count' => 1,
                        'perPage' => 20,
                        'prevPage' => false,
                        'nextPage' => false,
                        'pageCount' => 1,
                        'sort' => null,
                        'direction' => false,
                        'limit' => null,
                        'sortDefault' => false,
                        'directionDefault' => false
                    ]
                ]
            ]
        ]);
        $View = new View($Request);
        $Table = new TableHelper($View);
        $expected = '<table>';
        $expected .= '<thead><tr class="header"><th>';
        $expected .= '<a href="/?sort=id&amp;direction=asc">header</a>';
        $expected .= '</th></tr></thead>';
        $expected .= '<tr><td>cell</td></tr>';
        $expected .= '</table>';
        $expected .= '<div class="paginator">';
        $expected .= '<ul class="pagination">';
        $expected .= '<li class="prev disabled"><a href="">&lt; previous</a></li>';
        $expected .= '<li class="next disabled"><a href="">next &gt;</a></li>';
        $expected .= '</ul>';
        $expected .= '<p>1 of 1</p>';
        $expected .= '</div>';
        $Table
            ->create()
            ->startRow(['group' => 'head', 'class' => 'header'])
            ->header(['id', 'header'])
            ->endRow()
            ->startRow()
            ->cell('cell')
            ->endRow();
        $result = $Table->display();
        $this->assertEquals($expected, $result);
        unset($Request);
        unset($View);
        unset($Table);
    }
}
