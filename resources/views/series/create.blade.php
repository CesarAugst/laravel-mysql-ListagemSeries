@extends('layout')
@section('cabecalho')
    Adicionar Série
@endsection

@section('conteudo')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post">
        @csrf
        <div class="form-group">
            <labe for="nome">Nome</labe>
            <input type="text" class="form-control" name="nome" id="nome">
        </div>
        <button class="btn btn-primary">Adicionar</button>
    </form>
@endsection
