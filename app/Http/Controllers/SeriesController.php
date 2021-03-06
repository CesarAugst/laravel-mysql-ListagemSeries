<?php

namespace App\Http\Controllers;

use App\Episodio;
use App\Events\NovaSerie;
use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use App\Services\CriadorDeSerie;
use App\Services\RemovedorDeSerie;
use App\Temporada;
use App\User;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::query()
            ->orderBy('nome')
            ->get();

        $mensagem = $request->session()->get('mensagem');
        return view('series.index', compact('series', 'mensagem'));
    }

    public function create()
    {
        return view('series.create');
    }

    //public function store(SeriesFormRequest $request)
    public function store(Request $request, CriadorDeSerie $criadorDeSerie)
    {
        $request->validate(
            ['nome' => 'required|min:3']
        );

        $capa = null;
        if($request->hasFile('capa')){
            $capa = $request->file('capa')->store('serie');
        }


        $serie = $criadorDeSerie->criarSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada,
            $capa
        );

        $eventoNovaSerie = new NovaSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada
        );

        event($eventoNovaSerie);

        $request->session()
            ->flash(
                'mensagem',
                "Série {$serie->id} criada com sucesso {$serie->nome}"
            );

        return redirect()->route('listar_series');

    }

    public function destroy(Request $request, RemovedorDeSerie $removedorDeSerie)
    {
        $nomeSerie = $removedorDeSerie->removerSerie($request->id);
        $request->session()
            ->flash(
                'mensagem',
                "Série {$nomeSerie} foi removida com sucesso"
            );
        return redirect()->route('listar_series');
    }

    public function editaNome( int $id, Request $request)
    {
        $novoNome = $request->nome;
        $serie = Serie::find(id);
        $serie->nome = $novoNome;
        $serie->save();
    }

}
