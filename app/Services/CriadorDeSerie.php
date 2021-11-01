<?php

namespace App\Services;

use App\Serie;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class CriadorDeSerie
{
    public function criarSerie(
        string $nomeSerie,
        int    $qtdTemporadas,
        int    $epPorTemporada
    ): Serie
    {
        DB::beginTransaction();
        $serie = Serie::create(['nome' => $nomeSerie]);
        $this->criaTemporada($qtdTemporadas, $serie, $epPorTemporada);
        DB::commit();

        return $serie;
    }

    /**
     * @param int $qtdTemporadas
     * @param $serie
     * @param int $epPorTemporada
     */
    public function criaTemporada(int $qtdTemporadas, $serie, int $epPorTemporada): void
    {
        $qtdTemporadas = $qtdTemporadas;

        for ($i = 1; $i <= $qtdTemporadas; $i++):
            $temporada = $serie->temporadas()->create(['numero' => $i]);

            $this->criaEpisodios($epPorTemporada, $temporada);
        endfor;
    }

    /**
     * @param int $epPorTemporada
     * @param $temporada
     */
    public function criaEpisodios(int $epPorTemporada, $temporada): void
    {
        for ($j = 1; $j <= $epPorTemporada; $j++):
            $temporada->episodios()->create(['numero' => $j]);
        endfor;
    }
}
