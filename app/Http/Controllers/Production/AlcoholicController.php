<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Production\Alcoholic;

class AlcoholicController extends Controller
{
    public function index()
    {
        $alcoholics = Alcoholic::all();
        return view('productions.alcoholics.index', compact('alcoholics'));
    }

    public function create()
    {
        return view('productions.alcoholics.create');
    }

    public function store(Request $request)
    {
        Alcoholic::create($request->all());
        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione alcolica aggiunta con successo!');
    }

    public function show($id)
    {
        $alcoholic = Alcoholic::findOrFail($id);
        return view('productions.alcoholics.show', compact('alcoholic'));
    }


    public function edit(Alcoholic $alcoholic)
    {
        return view('productions.alcoholics.edit', compact('alcoholic'));
    }

    public function update(Request $request, Alcoholic $alcoholic)
    {
        $alcoholic->update($request->all());
        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione aggiornata con successo!');
    }

    public function destroy(Alcoholic $alcoholic)
    {
        $alcoholic->delete();
        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione eliminata con successo!');
    }
}
