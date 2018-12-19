<?php 

    /**
     * DatabaseService
     */

    namespace App\Services;

    use Embryo\PDO\Database;
    use Embryo\Container\ServiceProvider;

    class DatabaseService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set(Database::class, function($container){
                $settings = $container['settings'];
                $database = $settings['database'];
                return new Database($database);
            });

            $this->container->alias('database', Database::class);
        }
    }