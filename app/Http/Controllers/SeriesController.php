<?php

namespace App\Http\Controllers;

use App\Episodio;
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

        $serie = $criadorDeSerie->criarSerie(
            $request->nome,
            $request->qtd_temporadas,
            $request->ep_por_temporada
        );

        $users = User::all();
        foreach ($users as $indice => $user){
            $multiplicador = $indice + 1;
            $email = new \App\Mail\NovaSerie(
                $request->nome,
                $request->qtd_temporadas,
                $request->ep_por_temporada
            );
            $email->subject = 'Nova Série Adicionada';
            $quando = now()->addSecond($multiplicador * 10);
            \Illuminate\Support\Facades\Mail::to($user)->later(
                $quando,
                $email
            );
            //sleep(5);
        }

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
