<?php namespace Sanatorium\Reviews\Providers;

use Cartalyst\Support\ServiceProvider;

class ReviewServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Reviews\Models\Review']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.reviews.review.handler.event');

		// Register all the default hooks
        $this->registerHooks();

		// Prepare resources
		$this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.reviews.review', 'Sanatorium\Reviews\Repositories\Review\ReviewRepository');

		// Register the data handler
		$this->bindIf('sanatorium.reviews.review.handler.data', 'Sanatorium\Reviews\Handlers\Review\ReviewDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.reviews.review.handler.event', 'Sanatorium\Reviews\Handlers\Review\ReviewEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.reviews.review.validator', 'Sanatorium\Reviews\Validator\Review\ReviewValidator');
	}

	/**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-review');

        $this->publishes([
            $config => config_path('sanatorium-review.php'),
        ], 'config');
    }

    /**
     * Register all hooks.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $hooks = [
            [
            	'position' => 'profile.order.bottom',
            	'hook' => 'sanatorium/reviews::hooks.order',
            ],
            [
                'position' => 'product.bought',
                'hook' => 'sanatorium/reviews::hooks.product',
            ],
            [
                'position' => 'profile.tabs.nav',
                'hook' => 'sanatorium/reviews::hooks.tab_nav',
            ],
            [
                'position' => 'profile.tabs.content',
                'hook' => 'sanatorium/reviews::hooks.tab_content',
            ]
        ];

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $item) {
        	extract($item);
            $manager->registerHook($position, $hook);
        }
    }

}
