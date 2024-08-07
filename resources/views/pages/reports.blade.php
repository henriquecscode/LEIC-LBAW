@extends('layouts.app')

@section('title', 'Trivia(l)')

@section('path', 'home/admin/reports')

@section('content')
<h2>Publicações reportadas</h2>

<div class="admin_reported">
  <div class="user_name p-2 pl-3">Reports</div>
  <div class="d-flex flex-column">
      @foreach ($reports as $report)

        <div class="report d-flex flex-row" id="report-{{ $report->id }}">
          <div class="id_square">
            {{ $report->id }} 
          </div>
          <div class="p-3">
            <a href="{{ url('question/' . $report->question->id) }}"><b>Pergunta {{ $report->question->id }}</b></a>
            <div class="line w-100 mt-2 mb-3"></div>
            <p>
              <b>Id da publicação:</b> {{ $report->post->id }}
              <br>
              <b>Conteúdo da publicação:</b> {{ $report->post->content }}
              <br>
              <b>Motivo:</b> {{ $report->motive }}
              <br>
              <b>Data do reporte:</b> {{ $report->report_date }}
              <br>
            </p>
             
            <div>
              @if(!$report->member->is_banned)
                <a id="user-{{$report->post->member}}" user-id-block="{{$report->post->member}}" class="button text-white block-add-button">Block User {{$report->post->member}}</a>
              @endif
              <a report-id="{{$report->id}}" class="button text-white report-remove-button">Dismiss</a>
            </div>
          </div>
        </div>
      @endforeach
        
  </div>
</div>


@endsection