<?php

namespace CrudGenerator;


use Illuminate\Console\Command;
use Illuminate\Support\Str;
use DB;
use Artisan;

class CrudGeneratorService
{
    public $modelName = '';
    public $tableName = '';
    public $prefix = '';
    public $force = false;
    public $layout = '';
    public $existingModel = '';
    public $controllerName = '';
    public $viewFolderName = '';
    public $output = null;
    public $appNamespace = 'App';

    public function __construct()
    {
    }

    public function Generate()
    {
        $modelname = Str::ucfirst(Str::singular($this->modelName));
        $this->viewFolderName = Str::lower($this->controllerName);

        $this->output->info('');
        $this->output->info('Creating catalogue for table: ' . ($this->tableName ?: Str::lower(Str::plural($this->modelName))));
        $this->output->info('Model Name: ' . $modelname);

        $options = [
            'model_uc' => $modelname,
            'model_uc_plural' => Str::plural($modelname),
            'model_singular' => Str::lower($modelname),
            'model_plural' => Str::lower(Str::plural($modelname)),
            'tablename' => $this->tableName ?: Str::lower(Str::plural($this->modelName)),
            'prefix' => $this->prefix,
            'custom_master' => $this->layout ?: 'crudgenerator::layouts.master',
            'controller_name' => $this->controllerName,
            'view_folder' => $this->viewFolderName,
            'route_path' => $this->viewFolderName,
            'appns' => $this->appNamespace,
        ];

        if (!$this->force) {
            //if(file_exists(app_path().'/'.$modelname.'.php')) { $this->output->info('Model already exists, use --force to overwrite'); return; }
            if (file_exists(app_path() . '/Http/Controllers/' . $this->controllerName . 'Controller.php')) {
                $this->output->info('Controller already exists, use --force to overwrite');
                return;
            }
            if (file_exists(base_path() . '/resources/views/' . $this->viewFolderName . '/add.blade.php')) {
                $this->output->info('Add view already exists, use --force to overwrite');
                return;
            }
            if (file_exists(base_path() . '/resources/views/' . $this->viewFolderName . '/show.blade.php')) {
                $this->output->info('Show view already exists, use --force to overwrite');
                return;
            }
            if (file_exists(base_path() . '/resources/views/' . $this->viewFolderName . '/index.blade.php')) {
                $this->output->info('Index view already exists, use --force to overwrite');
                return;
            }
        }

        $columns = $this->createModel($modelname, $this->prefix, $this->tableName);

        $options['columns'] = $columns;
        $options['first_column_nonid'] = count($columns) > 1 ? $columns[1]['name'] : '';
        $options['num_columns'] = count($columns);

        //###############################################################################
        if (!is_dir(base_path() . '/resources/views/' . $this->viewFolderName)) {
            $this->output->info('Creating directory: ' . base_path() . '/resources/views/' . $this->viewFolderName);
            mkdir(base_path() . '/resources/views/' . $this->viewFolderName);
        }

        $filegenerator = new \CrudGenerator\CrudGeneratorFileCreator();
        $filegenerator->options = $options;
        $filegenerator->output = $this->output;

        $filegenerator->templateName = 'controller';
        $filegenerator->path = app_path() . '/Http/Controllers/' . $this->controllerName . 'Controller.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.add';
        $filegenerator->path = base_path() . '/resources/views/' . $this->viewFolderName . '/add.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.show';
        $filegenerator->path = base_path() . '/resources/views/' . $this->viewFolderName . '/show.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.index';
        $filegenerator->path = base_path() . '/resources/views/' . $this->viewFolderName . '/index.blade.php';
        $filegenerator->Generate();
        //###############################################################################

        $addroute = 'Route::resource(\'/' . $this->viewFolderName . '\', \'' . $this->controllerName . 'Controller\');';
        $this->appendToEndOfFile(base_path() . '/routes/web.php', "\n" . $addroute, 0, true);
        $this->output->info('Adding Route: ' . $addroute);
    }

    protected function getColumns($tablename)
    {
        $dbType = DB::getDriverName();
        switch ($dbType) {
            case "pgsql":
                $cols = DB::select("select column_name as Field, "
                    . "data_type as Type, "
                    . "is_nullable as Null "
                    . "from INFORMATION_SCHEMA.COLUMNS "
                    . "where table_name = '" . $tablename . "'");
                break;
            default:
                $cols = DB::select("show columns from " . $tablename);
                break;
        }

        $ret = [];
        foreach ($cols as $c) {
            $field = isset($c->Field) ? $c->Field : $c->field;
            $type = isset($c->Type) ? $c->Type : $c->type;
            $cadd = [];
            $cadd['name'] = $field;
            $cadd['type'] = $field == 'id' ? 'id' : $this->getTypeFromDBType($type);
            $cadd['display'] = ucwords(str_replace('_', ' ', $field));
            $ret[] = $cadd;
        }
        return $ret;
    }

    protected function getTypeFromDBType($dbtype)
    {
        $type = Helpers::types($dbtype);
        $this->output->info('[DB MAPPING]: ' . $dbtype . ' => ' . $type);
        return $type;
    }

    protected function createModel($modelname, $prefix, $table_name)
    {
        Artisan::call('make:model', ['name' => $modelname]);

        if ($table_name) {
            $this->output->info('Custom table name: ' . $prefix . $table_name);
            $this->appendToEndOfFile(app_path() . '/' . $modelname . '.php', "    protected \$table = '" . $table_name . "';\n\n}", 2);
        }

        $columns = $this->getColumns($prefix . ($table_name ?: Str::lower(Str::plural($modelname))));

        $cc = collect($columns);

        if (!$cc->contains('name', 'updated_at') || !$cc->contains('name', 'created_at')) {
            $this->appendToEndOfFile(app_path() . '/' . $modelname . '.php', "    public \$timestamps = false;\n\n}", 2, true);
        }

        $this->output->info('Model created, columns: ' . json_encode($columns));
        return $columns;
    }

    protected function deletePreviousFiles($tablename, $existing_model)
    {
        $todelete = [
            app_path() . '/Http/Controllers/' . Str::ucfirst($tablename) . 'Controller.php',
            base_path() . '/resources/views/' . Str::plural($tablename) . '/index.blade.php',
            base_path() . '/resources/views/' . Str::plural($tablename) . '/add.blade.php',
            base_path() . '/resources/views/' . Str::plural($tablename) . '/show.blade.php',
        ];
        if (!$existing_model) {
            $todelete[] = app_path() . '/' . Str::ucfirst(Str::singular($tablename)) . '.php';
        }
        foreach ($todelete as $path) {
            if (file_exists($path)) {
                unlink($path);
                $this->output->info('Deleted: ' . $path);
            }
        }
    }

    protected function appendToEndOfFile($path, $text, $remove_last_chars = 0, $dont_add_if_exist = false)
    {
        $content = file_get_contents($path);
        if (!Str::contains($content, $text) || !$dont_add_if_exist) {
            $newcontent = substr($content, 0, strlen($content) - $remove_last_chars) . $text;
            file_put_contents($path, $newcontent);
        }
    }
}
