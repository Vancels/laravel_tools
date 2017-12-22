<?php
namespace Vancels\Tools;

use Illuminate\Support\ServiceProvider;
use Vancels\Tools\Console\CreateModelCommand;
use Vancels\Tools\Console\ToolsCommand;
use Vancels\Tools\Facade\ToolsFacade;
use Vancels\Tools\Service\ToolServiceInterface;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 非正式环境下
        if (env('APP_DEBUG')) {
            if (class_exists(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)) {
                $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            }
        }


        $this->app->singleton('tools', function ($app) {
            $cls = new ToolServiceInterface($app);

            return $cls;
        });

        $this->app->alias('vTools', ToolsFacade::class);


        // 绑定命令行
        $this->app->singleton(
            'command.vancels.create_model',
            function ($app) {
                return new CreateModelCommand($app['files'], $app['view']);
            }
        );
        $this->app->singleton(
            'command.vancels.tools',
            function ($app) {
                return new ToolsCommand($app['files'], $app['view']);
            }
        );

        $this->commands('command.vancels.create_model','command.vancels.tools');
    }

}
