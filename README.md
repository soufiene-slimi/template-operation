# TemplateOperation for Backpack for Laravel

[![Latest Version on Packagist][ico-version]][link-version]
[![Build Status][ico-build]][link-build]
[![Quality Score][ico-quality]][link-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Adds an interface for [```soufiene-slimi/laravel-form-template```](https://github.com/VentureCraft/revisionable) to your Backpack CRUDs, so that the admin can:
- save entries forms as a template;
- apply those template while creating new ones;

[```soufiene-slimi/laravel-form-template```](https://github.com/soufiene-slimi/laravel-form-template) allows you to save some form templates to apply them whenever you want. the utility is to avoid filling some inputs again and again, that most of the time have the same values, or maybe to apply some template based on the user choice.

When used, this operation will show another button beside the create button in the listing view. On click, that button opens another page, which will allow an admin to create a template for that entry:

![screencapture-localhost-Make-My-Business-public-admin-invoice-2020-06-14-12_28_06](https://user-images.githubusercontent.com/10948245/84592054-9b983080-ae3a-11ea-9475-84b523164a69.png)

## Installation

**Step 1.** Require the package:

``` bash
composer require soufiene-slimi/template-operation
```

This will automatically install ```soufiene-slimi/laravel-form-template``` too, if it's not already installed.

**Step 2.** Migrate the ```soufiene-slimi/laravel-form-template``` table:

``` bash
php artisan migrate
```

**Step 3.** In your CrudController, use the operation trait:
```php
<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class InvoiceCrudController extends CrudController
{
    use \SoufieneSlimi\TemplateOperation\TemplateOperation;
```

For complex usage, head on over to [soufiene-slimi/laravel-form-template](https://github.com/VentureCraft/revisionable) to see the full documentation and extra configuration options.
## Usage

After installing the operation, you need to define the template fields and validation (if necessary) like in the create operation.

```php
protected function setupTemplateOperation()
    {
        $this->crud->setValidation(InvoiceTemplateRequest::class);

        $this->crud->setOperationSetting('excludedInputs', ['paid']);

        CRUD::field('title')->type('custom_html')->value('<h1>FXXXX-XXXXXX</h1>')->size(8);
        CRUD::field('type')->default(3)->size(4);
        CRUD::field('hr')->type('custom_html')->value('<hr>');
        CRUD::field('client')->size(6);
        CRUD::field('items')->type('repeatable')->fields([
            [
                'name' => 'product',
                'label' => 'Product',
                'type' => 'select2_from_array',
                'options' => Product::get(['id', 'name'])->pluck('name', 'id')->toArray(),
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'quantity',
                'label' => 'Quantity',
                'type' => 'number',
                'wrapperAttributes' => ['class' => 'form-group col-md-3'],
                'attributes' => ['step' => 'any'],
            ],
            [
                'name' => 'price',
                'label' => 'Price',
                'type' => 'number',
                'wrapperAttributes' => ['class' => 'form-group col-md-3'],
                'attributes' => ['step' => 'any'],
            ],
        ]);
        CRUD::field('total')
            ->label('Total ('.config('settings.currency').')')
            ->type('number')
            ->size(5)
            ->attributes(['readonly' => 'true']);
        CRUD::field('paid')->type('number')->attributes(['step' => 'any'])->size(5);
        CRUD::field('wallet')->default(config('settings.default_wallet'))->size(2);
    }
```

If you need to customize some settings, you need to add the operation settings in the ```config\backpack\crud.php``` file.

```php
'operations' => [
    /*
    * Template Operation
    */
    'template' => [
        // The cards color
        // Available colors: bg-blue, bg-green, bg-purple, bg-orange, bg-red
        'cardsClass' => 'bg-orange',
        // How many cards per row
        'cardsPerRow' => 3,
        // The card icon
        'cardIcon' => '<i class="las la-pen-alt"></i>',
        // Excluded columns
        'excludedInputs' => ['status_id']
    ],
]
```

> Note that those are global operation settings, means that those settings will be applied to all template operations, but you steel can define or override those in your **EntityCrudController** by using ```$this->crud->setOperationSetting('key', value);``` inside the **setupTemplateOperation** function.

## Screenshots
### Template listing
![screencapture-localhost-Make-My-Business-public-admin-invoice-template-2020-06-14-12_36_22](https://user-images.githubusercontent.com/10948245/84592183-ac957180-ae3b-11ea-977e-cceb085ae157.png)

### Add new template
![screencapture-localhost-Make-My-Business-public-admin-invoice-template-create-2020-06-14-12_37_40](https://user-images.githubusercontent.com/10948245/84592220-dc447980-ae3b-11ea-9115-6f7ba185366b.png)

## Change log

Please see the [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details and a todolist.

## Security

If you discover any security related issues, please email soufiene.slimi@mail.com instead of using the issue tracker.

## Credits

- [Soufiene Slimi][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/soufiene-slimi/template-operation.svg
[ico-build]: https://scrutinizer-ci.com/g/soufiene-slimi/template-operation/badges/build.png?b=master
[ico-quality]: https://img.shields.io/scrutinizer/g/soufiene-slimi/template-operation.svg
[ico-downloads]: ☻☻https://img.shields.io/packagist/dt/soufiene-slimi/template-operation.svg

[link-version]: https://packagist.org/packages/soufiene-slimi/template-operation
[link-build]: https://scrutinizer-ci.com/g/soufiene-slimi/template-operation/build-status/master
[link-quality]: https://scrutinizer-ci.com/g/soufiene-slimi/template-operation
[link-downloads]: https://packagist.org/packages/soufiene-slimi/template-operation

[link-author]: https://github.com/soufiene-slimi
[link-contributors]: ../../contributors
