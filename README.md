# Introduction
Bagisto Size Chart Module allows the admin to easily create a size chart for their products. The size chart can be referenced by the customers to identify their accurate size. This add-on will definitely be a help to the customers as they can easily check their fit so that they can accordingly select their product size.

It packs in lots of demanding features that allows your business to scale in no time:

- Admin can add size chart templates.
- Two types of templates can be created – Normal & Configurable Product Template.
- Admin can add a size chart image.
- Admin can enable/disable size chart from configurations.
- Size Chart Module supports jpg, .png, .jpeg as image formats.
- The customer will see the size chart on the product page.
- This module increases the customer satisfaction.
- Size Chart Module is working with Simple, Virtual, and Configurable product types.

## Requirements:

- **Bagisto**: v1.3.2.

## Installation :
- Run the following command
```
composer require bagisto/bagisto-size-chart
```

- Goto config/concord.php file and add following line under 'modules'
```php
\Webkul\SizeChart\Providers\ModuleServiceProvider::class
```

- Run these commands below to complete the setup
```
composer dump-autoload
```

```
php artisan migrate
php artisan route:cache
php artisan config:cache
```

```
php artisan db:seed --class=Webkul\\SizeChart\\Database\\Seeders\\DatabaseSeeder
```
- If your are windows user then run the below command-

```
php artisan db:seed --class="Webkul\SizeChart\Database\Seeders\DatabaseSeeder"
```

```
php artisan vendor:publish
```
-> Press 0 and then press enter to publish all assets and configurations.

- Goto config/app.php file and set your 'default_country'

> now execute the project on your specified domain.
