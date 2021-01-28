# cfonb-parser
[![Build Status](https://travis-ci.org/andrew-svirin/cfonb-php.svg?branch=master)](https://travis-ci.org/andrew-svirin/cfonb-php)
[![Latest Stable Version](https://poser.pugx.org/andrew-svirin/cfonb-php/v/stable)](https://packagist.org/packages/andrew-svirin/cfonb-php)
[![License](https://poser.pugx.org/andrew-svirin/cfonb-php/license)](https://packagist.org/packages/andrew-svirin/cfonb-php)

A PHP Parser for CFONB statements (120c), transfers (240c)

Supports CFONB 120 signature and CFONB 240 transactions formats.
Helper for [EBICS Client PHP](https://github.com/andrew-svirin/ebics-client-php)

## Installation
```bash
$ composer require andrew-svirin/cfonb-php
```

## How to use
```php
<?php

$parser = new \Silarhi\Cfonb\CfonbParser();

//Gets all statements day by day
foreach($parser->read120C('My Content') as $statement) {
  if ($statement->hasOldBalance()) {
    echo sprintf("Old balance : %f\n", $statement->getOldBalance()->getAmount());
  }
  foreach($statement->getOperations() as $operation) {
    //Gets all statement operations
  }
  
  if ($statement->hasNewBalance()) {
    echo sprintf("New balance : %f\n", $statement->getNewBalance()->getAmount());
  }
}
```

```php
<?php

$parser = new \Silarhi\Cfonb\CfonbParser();

//Gets all statements day by day
foreach($parser->read240C('My Content') as $transfer) {
  if ($transfer->getHeader()) {
    echo sprintf("Header op code : %f\n", $transaction->getHeader()->getOperationCode());
  }
  foreach($transfer->getTransactions() as $transactions) {
    //Gets all statement operations
  }
  
  if ($transaction->getTotal()()) {
    echo sprintf("Total transfer amount : %f\n", $transaction->getTotal()->getTotalAmount());
  }
}
```
