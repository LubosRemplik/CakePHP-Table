# CakePHP Table

[![Build Status](https://travis-ci.org/LubosRemplik/CakePHP-Table.svg)](https://travis-ci.org/LubosRemplik/CakePHP-Table)
[![Latest Stable Version](https://poser.pugx.org/lubos/table/v/stable.svg)](https://packagist.org/packages/lubos/table) 
[![Total Downloads](https://poser.pugx.org/lubos/table/downloads.svg)](https://packagist.org/packages/lubos/table) 
[![Latest Unstable Version](https://poser.pugx.org/lubos/table/v/unstable.svg)](https://packagist.org/packages/lubos/table) 
[![License](https://poser.pugx.org/lubos/table/license.svg)](https://packagist.org/packages/lubos/table)

A CakePHP 3.x plugin for creating Html tables

## Installation

```
composer require lubos/table
```

Load plugin in bootstrap.php file

```php
Plugin::load('Lubos/Table');
```

## Usage

In your controller
````php
public $helpers = [
    'Lubos/Table.Table'
];
```

In your view
```php
$cells = [
    ['cell 00', 'cell 01', 'cell02'],
    ['cell 10', 'cell 11', 'cell12'],
    ['cell 20', 'cell 21', 'cell22']
];
$this->Table->create();
$this->Table
    ->startRow(['group' => 'head', 'class' => 'header'])
    ->header('header 1')
    ->header('header 2')
    ->header('header 3')
    ->endRow();
foreach ($cells as $row) {
    $this->Table->startRow();
    foreach ($row as $cell) {
        $this->Table->cell($cell);
    }
    $this->Table->endRow();
}
echo $this->Table->display();
```

## Bugs & Features

For bugs and feature requests, please use the issues section of this repository.

If you want to help, pull requests are welcome.  
Please follow few rules:  

- Fork & clone
- Make bugfix or feature request
- Follow [CakePHP coding standards](https://github.com/cakephp/cakephp-codesniffer)
- Make tests and use phpunit to pass them
