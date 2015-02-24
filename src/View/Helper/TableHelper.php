<?php
namespace Lubos\Table\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

class TableHelper extends Helper
{

    use StringTemplateTrait;

    /**
     * Other helpers used by FormHelper
     *
     * @var array
     */
    public $helpers = ['Url', 'Html', 'Form'];

    /**
     * Table body content
     *
     * @var array
     */
    protected $_body = array();

    /**
     * Table head content
     *
     * @var array
     */
    public $_head = array();

    /**
     * Table foot content
     *
     * @var array
     */
    protected $_foot = array();

    /**
     * Last group used
     *
     * @var string
     */
    protected $_lastGroup;

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'tableheader' => '<th{{attrs}}>{{content}}</th>',
            'tablecell' => '<td{{attrs}}>{{content}}</td>',
            'tablerow' => '<tr{{attrs}}>{{content}}</tr>',
            'tablerowstart' => '<tr{{attrs}}>',
            'tablerowend' => '</tr>',
            'tablehead' => '<thead{{attrs}}>{{content}}</thead>',
            'tableheadstart' => '<thead{{attrs}}>',
            'tableheadend' => '</thead>',
            'tablebody' => '<tbody{{attrs}}>{{content}}</tbody>',
            'tablebodystart' => '<tbody{{attrs}}>',
            'tablebodyend' => '</tbody>',
            'tablefoot' => '<tfoot{{attrs}}>{{content}}</tfoot>',
            'tablefootstart' => '<tfoot{{attrs}}>',
            'tablefootend' => '</tfoot>',
            'table' => '<table{{attrs}}>{{content}}</table>',
        ]
    ];

    /**
     * Constructor
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
    }

    /**
     * Create table
     *
     * @param array $options Array of options.
     * @return $this
     */
    public function create(array $options = array())
    {
        // empty body, head and foot
        $this->_body = $this->_head = $this->_foot = '';
        return $this;
    }

    /**
     * Start table row
     *
     * ### Options
     *
     * - `group` Table group options head, body, foot. Default body.
     *
     * @param array $options Array of options and html attributes.
     * @return $this
     */
    public function startRow(array $options = array())
    {
        $group = 'body';
        if (isset($options['group'])) {
            $group = $options['group'];
            unset($options['group']);
        }
        if (isset($this->{'_' . $group})) {
            $this->{'_' . $group}[] = $this->formatTemplate('tablerowstart', [
                'attrs' => $this->templater()->formatAttributes($options),
            ]);
        }
        $this->_lastGroup = $group;
        return $this;
    }

    /**
     * Table header
     *
     * @param string $content Header cell content.
     * @param array $htmlAttributes Html attributes passed to th.
     * @return $this
     */
    public function header($content = null, array $htmlAttributes = array())
    {
        $this->_head[] = $this->formatTemplate('tableheader', [
            'content' => $content,
            'attrs' => $this->templater()->formatAttributes($htmlAttributes),
        ]);
        return $this;
    }

    /**
     * Table cell
     *
     * @param string $content Cell content.
     * @param array $htmlAttributes Html attributes passed to td.
     * @return $this
     */
    public function cell($content = null, array $htmlAttributes = array())
    {
        $this->{'_' . $this->_lastGroup}[] = $this->formatTemplate('tablecell', [
            'content' => $content,
            'attrs' => $this->templater()->formatAttributes($htmlAttributes),
        ]);
        return $this;
    }

    /**
     * End table row
     *
     * @return $this
     */
    public function endRow()
    {
        $this->{'_' . $this->_lastGroup}[] = $this->formatTemplate('tablerowend', []);
        return $this;
    }

    /**
     * @param array $htmlAttributes Html attributes passed to table tag.
     * @return string Table HTML code
     */
    public function display(array $htmlAttributes = [])
    {
        $out = [];
        if ($this->_head) {
            $out[] = $this->formatTemplate('tablehead', [
                'content' => implode($this->_head)
            ]);
        }
        if ($this->_body && (empty($this->_head) || empty($this->_foot))) {
            $out[] = implode($this->_body);
        } elseif ($this->_body) {
            $out[] = $this->formatTemplate('tablebody', [
                'content' => implode($this->_body)
            ]);
        }
        if ($this->_foot) {
            $out[] = $this->formatTemplate('tablefoot', [
                'content' => implode($this->_foot)
            ]);
        }
        $out = $this->formatTemplate('table', [
            'attrs' => $this->templater()->formatAttributes($htmlAttributes),
            'content' => implode($out)
        ]);
        return $out;
    }
}
