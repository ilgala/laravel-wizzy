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

    public function welcome(Request $request)
    {
        if ($request->ajax()) {
            $version = $this->wizzy->checkPHPVersion();
            $extensions = $this->wizzy->checkPHPExstensions();

            return response()->json(compact('version', 'extensions'));
        }


        return view('wizzy::index');
    }

    public function environment(Request $request)
    {
        if (!$request->isJson()) {
            throw new WizzyException("");
        }
    }

    public function database(Request $request)
    {
        if (!$request->isJson()) {
            throw new WizzyException("");
        }
    }

    public function conclusion(Request $request)
    {
        if (!$request->isJson()) {
            throw new WizzyException("");
        }
    }

}
