<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelWizzy;

use App;
use IlGala\LaravelWizzy\Exception\WizzyException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * This is Wizzy controller class.
 *
 * @author ilgala
 */
class WizzyController extends BaseController implements WizzyInterface
{
    use AuthorizesRequests,
        AuthorizesResources,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * @var \IlGala\LaravelWizzy\Wizzy
     */
    protected $wizzy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->wizzy = app('wizzy');
    }

    /**
     * Show the index view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->flush();

        if ($request->ajax()) {
            $version = $this->wizzy->checkPHPVersion();
            $extensions = $this->wizzy->checkPHPExstensions();

            $nextEnabled = $version['required'] == false;

            foreach ($extensions as $key => $value) {
                if (!$value) {
                    $nextEnabled = false;
                }
            }

            // refresh token
            $token = csrf_token();

            return response()->json(compact('token', 'version', 'extensions', 'nextEnabled'));
        }


        return view('wizzy::index');
    }

    /**
     * Wizzy environment step action.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    public function environment(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException('Method not allowed', 401);
        }

        // Recover environment file
        if (session()->get('wizzy.envfile', null) !== null) {
            $envPath = base_path(session()->get('wizzy.envfile'));
        } else {
            $configRepository = app()->app['config'];

            $envPath = base_path($configRepository->get('wizzy.environment'));
        }

        $filename = explode('.', $envPath)[1] == 'env' ? '' : explode('.', $envPath)[1];

        $env_variables = $this->wizzy->fromEnvToArray($envPath);

        // refresh token
        $token = csrf_token();

        return response()->json(compact('token', 'filename', 'env_variables'));
    }

    /**
     * Wizzy database step action.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    public function database(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException('Method not allowed', 401);
        }

        $configRepository = app()->app['config'];
        $migrations = Wizzy::getMigrationsList($configRepository->get('wizzy.migrations_path'));

        // refresh token
        $token = csrf_token();

        return response()->json(compact('token', 'migrations'));
    }

    /**
     * Wizzy conclusion step action.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    public function conclusion(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException('Method not allowed', 401);
        }

        // Recover environment file
        if (session()->get('wizzy.envfile', null) !== null) {
            $envPath = base_path(session()->get('wizzy.envfile'));
            $filename = session()->get('wizzy.envfile');
        } else {
            $configRepository = app()->app['config'];

            $envPath = base_path($configRepository->get('wizzy.environment'));
            $filename = $configRepository->get('wizzy.environment');
        }

        $configRepository = app()->app['config'];
        $env_variables = $this->wizzy->fromEnvToString($envPath);

        $filename = Wizzy::environmentStore($filename, $env_variables, true);

        foreach ($configRepository->get('wizzy.conclusion_scripts') as $script) {
            Wizzy::artisanCall($script);
        }

        // refresh token
        $token = csrf_token();

        return response()->json(compact('token'));
    }

    /**
     * Wizzy custom actions.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    public function execute(Request $request)
    {
        if (!$request->has('view')) {
            throw new WizzyException('No view defined', 500);
        }

        switch ($request->get('view')) {
            case 'environment':
                return $this->storeEnvironmentSettings($request);
            case 'database':
                return $this->runMigrations($request);
            default:
                throw new WizzyException('Undefined view', 500);
        }
    }

    /**
     * Wizzy store environment variables action.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    private function storeEnvironmentSettings(Request $request)
    {
        $this->validate($request, [
            'variables' => 'required',
        ]);

        // Setup filename
        $filename = Wizzy::environmentStore($request->get('filename'), $request->get('variables'));

        session()->put('wizzy.envfile', $filename);

        // refresh token
        $token = csrf_token();

        return response()->json(compact('token', 'success', 'message'));
    }

    /**
     * Wizzy run migration action.
     *
     * @param Request $request
     *
     * @throws WizzyException
     *
     * @return \Illuminate\Http\Response
     */
    private function runMigrations(Request $request)
    {
        $configRepository = app()->app['config'];
        $migrations_path = $configRepository->get('wizzy.migrations_path');

        $refresh_database = $request->get('refresh', false);
        $seed_database = $request->get('seed', false);

        Wizzy::runMigration($migrations_path, $refresh_database, $seed_database);

        // refresh token
        $token = csrf_token();

        return response()->json(compact('token', 'success', 'message'));
    }
}
