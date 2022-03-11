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
_This package adds it`s menu items into sidebar menu of the GetCandy Hub.
But there is no way to add menu item in a specific position of the sidebar menu, yet.
So, I decided to disable service discovery and add it manually to let control the positioning issue._

### Publish resources

#### publish config file

```shell
php artisan vendor:publish --tag=getcandy:virtual-inventory:config
```

#### publish language files

```shell
php artisan vendor:publish --tag=getcandy:virtual-inventory:lang
```
