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

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Http\Request;
use App\Http\Requests;
use IlGala\LaravelWizzy\Exception\WizzyException;

class WizzyController extends BaseController
{

    use AuthorizesRequests,
        AuthorizesResources,
        DispatchesJobs,
        ValidatesRequests;

    /**
     *
     * @var \IlGala\LaravelWizzy\Wizzy
     */
    protected $wizzy;

    public function __construct()
    {
        $this->wizzy = app('wizzy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $version = $this->wizzy->checkPHPVersion();
            $extensions = $this->wizzy->checkPHPExstensions();

            $nextEnabled = $version['required'] == false;

            foreach ($extensions as $key => $value) {
                if (!$value) {
                    $nextEnabled = false;
                }
            }

            return response()->json(compact('version', 'extensions', 'nextEnabled'));
        }


        return view('wizzy::index');
    }

    public function environment(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException("Method not allowed", 401);
        }

        // Recover environment file
        $envPath = base_path('.env.example');

        $env_variables = $this->wizzy->fromEnvToArray($envPath);

        return response()->json(compact('env_variables'));
    }

    public function database(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException("Method not allowed", 401);
        }
    }

    public function conclusion(Request $request)
    {
        if (!$request->ajax()) {
            throw new WizzyException("Method not allowed", 401);
        }
    }

    public function storeSettings(Request $request)
    {
        if (!$request->has('view')) {
            throw new WizzyException("No view defined", 500);
        }

        switch ($view) {
            case 'environment':
                return $this->storeEnvironmentSettings();
            case 'database':
                return $this->storeDatabaseSettings();
            default:
                throw new WizzyException("Undefined view", 500);
        }
    }

    private function storeEnvironmentSettings()
    {

    }

    private function storeDatabaseSettings()
    {

    }

}
