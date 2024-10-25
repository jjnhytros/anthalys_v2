<?php

namespace App\Http\Controllers\City;

use App\Models\City\Robot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RobotController extends Controller
{
    public function index()
    {
        $robots = Robot::all();
        return view('robots.index', compact('robots'));
    }

    public function create()
    {
        return view('robots.create');
    }

    public function store(Request $request)
    {
        Robot::create($request->all());
        return redirect()->route('robots.index');
    }

    public function show(Robot $robot)
    {
        return view('robots.show', compact('robot'));
    }

    public function edit(Robot $robot)
    {
        return view('robots.edit', compact('robot'));
    }

    public function update(Request $request, Robot $robot)
    {
        $robot->update($request->all());
        return redirect()->route('robots.index');
    }

    public function destroy(Robot $robot)
    {
        $robot->delete();
        return redirect()->route('robots.index');
    }
}
