# TicketparkExcelBundle

This Symfony2 bundle implements [HtmlPhpExcel](https://github.com/Ticketpark/HtmlPhpExcel) to create excel files from html tables.

## Functionalities
* ExcelCreator (Service)
    * Create excel files from html templates

## Installation

Add TicketparkExcelBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/excel-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/excel-bundle
```

Enable the bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\ExcelBundle\TicketparkExcelBundle(),
        new Ticketpark\FileBundle\TicketparkFileBundle(),
    );
}
```

## Usage of ExcelCreator
Use the excel creator service in a controller to create a excel:

``` php
$file = $this->get('ticketpark.excel.creator')
    ->setIdentifier('someIdentifier') // the identifier is used for caching purposes
    ->setContent('<table><tr><td>foo</td></tr></table>')
    ->create();
    
// Output the file (example, this is not connected to the bundle)
$headers = array(
 'Content-Type' => 'application/vnd.ms-excel',
 'Content-Disposition' => 'attachment; filename="filename.xlsx"'
);

return new Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, $headers);
```

## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
