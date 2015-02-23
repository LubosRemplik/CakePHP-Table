<?php
namespace Lubos\Table\View\Helper;

use Cake\View\Helper;

class Table extends Helper
{

    /**
     * Other helpers used by FormHelper
     *
     * @var array
     */
    public $helpers = ['Url', 'Html', 'Form'];

    /**
     * Constructor
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);

        $config = $this->_config;
    }

    /**
     * @param array $htmlAttributes Html attributes passed to table tag.
     * @return string Table HTML code
     */
    public function display(array $htmlAttributes = [])
    {
        $output = implode([
            $this->Html->tag('tr', implode([
                $this->Html->tag('td', 'Whatever'),
                $this->Html->tag('td', 'Test')
            ]))
        ]);
        $output = $this->Html->tag('table', $output, $htmlAttributes);
        return $output;
    }
}
