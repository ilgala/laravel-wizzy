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

use Illuminate\Http\Request;

/**
 *
 * @author ilgala
 */
interface WizzyInterface
{

    public function index(Request $request);

    public function environment(Request $request);

    public function database(Request $request);

    public function conclusion(Request $request);

    public function execute(Request $request);
}
