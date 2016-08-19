<?php

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

    public function welcome(Request $request)
    {
        return view('wizzy::index');
    }

    public function requirements(Request $request)
    {
        if (!$request->isJson()) {
            throw new WizzyException("");
        }
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
