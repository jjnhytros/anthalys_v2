<?php

namespace App\Http\Controllers\Production;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Production\Alcoholic;

class AlcoholicController extends Controller
{
    public function index()
    {
        $alcoholics = Alcoholic::all();

        // Log dell'attività per la visualizzazione dell'elenco di tutte le produzioni alcoliche
        CLAIR::logActivity('C', 'index', 'Visualizzazione dell\'elenco delle produzioni alcoliche');

        return view('productions.alcoholics.index', compact('alcoholics'));
    }

    public function create()
    {
        // Log dell'attività per l'accesso alla pagina di creazione
        CLAIR::logActivity('L', 'create', 'Accesso alla pagina di creazione di una nuova produzione alcolica');

        return view('productions.alcoholics.create');
    }

    public function store(Request $request)
    {
        Alcoholic::create($request->all());

        // Log dell'attività per la creazione di una nuova produzione alcolica
        CLAIR::logActivity('A', 'store', 'Creazione di una nuova produzione alcolica', [
            'data' => $request->all()
        ]);

        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione alcolica aggiunta con successo!');
    }

    public function show($id)
    {
        $alcoholic = Alcoholic::findOrFail($id);

        // Log dell'attività per la visualizzazione di una specifica produzione alcolica
        CLAIR::logActivity('I', 'show', 'Visualizzazione dei dettagli della produzione alcolica', [
            'alcoholic_id' => $id
        ]);

        return view('productions.alcoholics.show', compact('alcoholic'));
    }

    public function edit(Alcoholic $alcoholic)
    {
        // Log dell'attività per l'accesso alla pagina di modifica di una produzione alcolica
        CLAIR::logActivity('L', 'edit', 'Accesso alla pagina di modifica della produzione alcolica', [
            'alcoholic_id' => $alcoholic->id
        ]);

        return view('productions.alcoholics.edit', compact('alcoholic'));
    }

    public function update(Request $request, Alcoholic $alcoholic)
    {
        $alcoholic->update($request->all());

        // Log dell'attività per l'aggiornamento di una produzione alcolica esistente
        CLAIR::logActivity('R', 'update', 'Aggiornamento della produzione alcolica', [
            'alcoholic_id' => $alcoholic->id,
            'updated_data' => $request->all()
        ]);

        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione aggiornata con successo!');
    }

    public function destroy(Alcoholic $alcoholic)
    {
        $alcoholic->delete();

        // Log dell'attività per l'eliminazione di una produzione alcolica
        CLAIR::logActivity('R', 'destroy', 'Eliminazione della produzione alcolica', [
            'alcoholic_id' => $alcoholic->id
        ]);

        return redirect()->route('productions.alcoholics.index')->with('success', 'Produzione eliminata con successo!');
    }
}
