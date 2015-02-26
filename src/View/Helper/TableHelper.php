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
    public $helpers = ['Url', 'Html', 'Form', 'Paginator'];

    /**
     * Table body content
     *
     * @var array
     */
    protected $_body = [];

    /**
     * Table head content
     *
     * @var array
     */
    protected $_head = [];

    /**
     * Table foot content
     *
     * @var array
     */
    protected $_foot = [];

    /**
     * Last group used
     *
     * @var string
     */
    protected $_lastGroup;

    /**
     * Rows number
     *
     * @var integer
     */
    protected $_row = 1;

    /**
     * Batch actions
     *
     * @var string
     */
    protected $_batchActions;

    /**
     * Show checkbox header
     *
     * @var bool
     */
    protected $_showCheckboxHeader = false;

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'paging' => true,
        'batchActions' => [
            'publish' => 'Publish records',
            'unpublish' => 'Unpublish records',
            'remove' => 'Remove records'
        ],
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
    public function create(array $options = [])
    {
        // empty body, head and foot
        $this->_body = $this->_head = $this->_foot = '';
        return $this;
    }

    /**
     * Adds batch actions to the table
     *
     * @param array $options Array of batch options.
     * @param bool $useDefault Merge with default actions.
     * @return $this
     */
    public function batchActions($options = [], $useDefault = true)
    {
        if ($useDefault) {
            $options = array_merge($this->_config['batchActions'], $options);
        }
        $this->_batchActions = $this->Form->input('batch_action', [
            'type' => 'select',
            'label' => 'With Selected',
            'options' => $options,
            'empty' => 'Please select ...',
        ]);
        $this->_batchActions .= $this->Form->button('Submit', array('class' => 'batch-action-submit'));
        $this->_showCheckboxHeader = true;
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
    public function startRow(array $options = [])
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
        if ($group == 'body' && !empty($this->_batchActions)) {
            $this->{'_' . $group}[] = $this->formatTemplate('tablecell', [
                'content' => sprintf('<input type="checkbox" name="data[selected][]" value="%s" />', $this->_row),
            ]);
        }
        $this->_row++;
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
    public function header($content = null, array $htmlAttributes = [])
    {
        if ($this->_showCheckboxHeader) {
            $this->_head[] = $this->formatTemplate('tableheader', [
                'content' => '<input type="checkbox" class="trigger-check-all" />',
                'attrs' => $this->templater()->formatAttributes(array('class' => 'nowrap')),
            ]);
            $this->_showCheckboxHeader = false;
        }
        if ($this->_config['paging'] && !empty($this->request->params['paging'])) {
            if (!is_array($content)) {
                $content = array($content);
            }
            $content = $this->Paginator->sort($content[0], isset($content[1]) ? $content[1] : $content[1]);
        }
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
    public function cell($content = null, array $htmlAttributes = [])
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
        if ($this->_batchActions) {
            $out[] = $this->Html->div('batch-actions', $this->_batchActions);
        }
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
        if ($this->_config['paging'] && !empty($this->request->params['paging'])) {
            $out .= $this->Html->div('paginator', implode([
                $this->Html->tag('ul', implode([
                    $this->Paginator->prev('< ' . __('previous')),
                    $this->Paginator->numbers(),
                    $this->Paginator->next(__('next') . ' >')
                ]), ['class' => 'pagination']),
                $this->Html->tag('p', $this->Paginator->counter())
            ]));
        }
        return $out;
    }
}
