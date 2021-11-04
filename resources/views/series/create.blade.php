@extends('layout')
@section('cabecalho')
    Adicionar Série
@endsection

@section('conteudo')
    @include('erros', ['errors' => $errors])
    <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col col-6">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome">
            </div>

            <div class="col col-3">
                <label for="nome">N° temporadas</label>
                <input type="number" class="form-control" name="qtd_temporadas" id="qtd_temporadas">
            </div>

            <div class="col col-3">
                <label for="nome">Ep. por temporadas</label>
                <input type="number" class="form-control" name="ep_por_temporada" id="ep_por_temporada">
            </div>

            <div class="row">
                <div class="col col-12">
                    <label for="capa">Capa</label>
                    <input type="file" class="form-control" name="capa" id="capa">
                </div>
            </div>
        </div>

        <button class="btn btn-primary mt-2">Adicionar</button>
    </form>
@endsection
