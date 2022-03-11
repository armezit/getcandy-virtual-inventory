# GetCandy Virtual Inventory Addon

## Setup

### Composer

```shell
composer require armezit/getcandy-virtual-inventory
```

### Add service provider

Add service provider to your project config file `app.php`:

```php
// ...
'providers' => [
    // ...
    Armezit\GetCandy\VirtualInventory\VirtualInventoryServiceProvider::class,
],
```

**NOTE:**
_This package adds it`s menu items into sidebar menu of the GetCandy
Hub. But there is no way to add menu item in a specific position of the
sidebar menu, yet. So, I decided to disable service discovery and add it
manually to let control the positioning issue._

### Execute Database Seeder

```shell
php artisan db:seed --class="Armezit\GetCandy\VirtualInventory\Seeders\Install"
```

This would create a `Virtual Inventory` product attribute group, and two
attribute `Has Virtual Inventory` (Toggle field type) and
`Virtual Inventory Attributes` (List field type).

### (Optional) Publish resources

#### publish config file

```shell
php artisan vendor:publish --tag=getcandy:virtual-inventory:config
```

#### publish language files

```shell
php artisan vendor:publish --tag=getcandy:virtual-inventory:lang
```

## Usage

1. Assign all attributes of the `Virtual Inventory` attribute group to
   the product type of your choice.
2. on editing product, toggle on `Has Virtual Inventory` attribute.
3. Create attributes of virtual inventory items,
   by creating attributes in `Virtual Inventory Attributes` list.
4. Define virtual inventory data in GetCandy Hub.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
