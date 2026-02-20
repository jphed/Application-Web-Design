<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function create(): View
    {
        return view('create');
    }

    public function edit($id): View
    {
        return view('edit');
    }

    public function store(Request $request) {}
    public function show($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
