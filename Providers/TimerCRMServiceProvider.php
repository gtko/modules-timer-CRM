<?php

namespace Modules\TimerCRM\Providers;

use Modules\BaseCore\Contracts\Services\CompositeurThemeContract;
use Modules\BaseCore\Contracts\Views\AfterMenuContract;
use Modules\BaseCore\Contracts\Views\TopBarContract;
use Modules\BaseCore\Entities\TypeView;
use Modules\TimerCRM\Contracts\Repositories\TimerRepositoryContract;
use Modules\TimerCRM\Repositories\TimerRepository;
use Illuminate\Support\ServiceProvider;


class TimerCRMServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'TimerCRM';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'timercrm';


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        app()->bind(TimerRepositoryContract::class, TimerRepository::class);

        if(config('timercrm.display_widget', true)) {
            app(CompositeurThemeContract::class)
                ->setViews(AfterMenuContract::class, [
                    'timercrm::time-tracker-commercial' => new TypeView(TypeView::TYPE_LIVEWIRE, 'timercrm::time-tracker-commercial')
                ]);
        }

    }



    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }


    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
