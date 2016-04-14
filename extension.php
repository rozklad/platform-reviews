<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Reviews',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/reviews',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Review extension',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.0',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Reviews\Providers\ReviewServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group([
				'prefix'    => admin_uri().'/reviews/reviews',
				'namespace' => 'Sanatorium\Reviews\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.reviews.reviews.all', 'uses' => 'ReviewsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.reviews.reviews.all', 'uses' => 'ReviewsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.reviews.reviews.grid', 'uses' => 'ReviewsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.reviews.reviews.create', 'uses' => 'ReviewsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.reviews.reviews.create', 'uses' => 'ReviewsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.reviews.reviews.edit'  , 'uses' => 'ReviewsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.reviews.reviews.edit'  , 'uses' => 'ReviewsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.reviews.reviews.delete', 'uses' => 'ReviewsController@delete']);
			});

		Route::group([
			'prefix'    => 'reviews/reviews',
			'namespace' => 'Sanatorium\Reviews\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.reviews.reviews.index', 'uses' => 'ReviewsController@index']);
			Route::get('{id}', ['as' => 'sanatorium.reviews.product', 'uses' => 'ReviewsController@product']);
			Route::post('submit', ['as' => 'sanatorium.reviews.submit', 'uses' => 'ReviewsController@submit']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('review', function($g)
		{
			$g->name = 'Reviews';

			$g->permission('review.index', function($p)
			{
				$p->label = trans('sanatorium/reviews::reviews/permissions.index');

				$p->controller('Sanatorium\Reviews\Controllers\Admin\ReviewsController', 'index, grid');
			});

			$g->permission('review.create', function($p)
			{
				$p->label = trans('sanatorium/reviews::reviews/permissions.create');

				$p->controller('Sanatorium\Reviews\Controllers\Admin\ReviewsController', 'create, store');
			});

			$g->permission('review.edit', function($p)
			{
				$p->label = trans('sanatorium/reviews::reviews/permissions.edit');

				$p->controller('Sanatorium\Reviews\Controllers\Admin\ReviewsController', 'edit, update');
			});

			$g->permission('review.delete', function($p)
			{
				$p->label = trans('sanatorium/reviews::reviews/permissions.delete');

				$p->controller('Sanatorium\Reviews\Controllers\Admin\ReviewsController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{
		$settings->find('platform')->section('reviews', function ($s) {
			$s->name = trans('sanatorium/reviews::settings.title');

            $s->fieldset('reviews', function ($f) {
                $f->name = trans('sanatorium/reviews::settings.title');

                $f->field('bought_only', function ($f) {
                    $f->name   = trans('sanatorium/reviews::settings.bought_only.label');
                    $f->info   = trans('sanatorium/reviews::settings.bought_only.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-reviews.bought_only';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/reviews::settings.bought_only.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/reviews::settings.bought_only.values.false');
                    });
                });

                // @todo get reviews not dependendant on orders
                $orders = app('sanatorium.orders.order');
                $statuses = Status::where('status_entity', $orders->getModel())->get();

                $f->field('finished_status', function ($f) use ($statuses) {
                    $f->name   = trans('sanatorium/reviews::settings.finished_status.label');
                    $f->info   = trans('sanatorium/reviews::settings.finished_status.info');
                    $f->type   = 'select';
                    $f->config = 'sanatorium-reviews.finished_status';

                    foreach( $statuses as $status ) {
	                    $f->option($status->id, function ($o) use ($status) {
	                        $o->value = $status->id;
	                        $o->label = $status->name;
	                    });
                	}
                });

            });

		});
	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-reviews',
				'name' => 'Reviews',
				'class' => 'fa fa-comments',
				'uri' => 'reviews',
				'regex' => '/:admin\/reviews/i',
				'children' => [
					[
						'class' => 'fa fa-comments',
						'name' => 'Reviews',
						'uri' => 'reviews/reviews',
						'regex' => '/:admin\/reviews\/review/i',
						'slug' => 'admin-sanatorium-reviews-review',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
