@extends('layouts/app')

@section('content')
    <h1>{{ $post->title }}</h1>

    {!! $post->safe_html_content !!}

    <p>{{ $post->user->name }}</p>

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <button type="submit">Publicar comentario</button>

    {!! Form::close() !!}

    @foreach ($post->latestComments() as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">

            {!! $comment->safe_html_comment !!} - {{ $comment->post->user->name }}

            @if(Gate::allows('accept', $comment) && !$comment->answer)
            {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                <button type="submit">Aceptar respuesta</button>
            {!! Form::close() !!}
            @endif
        </article>
    @endforeach

    {{ $post->latestComments()->render() }}

@endsection