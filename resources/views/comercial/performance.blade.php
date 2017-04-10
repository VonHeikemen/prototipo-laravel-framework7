@extends('layouts.public')

@section('content')
  <div data-page="comercial-performance" class="page">
    @include('elements.navbar')

    <!-- Scrollable page content -->
    <div class="page-content">
      {{ csrf_field() }}

      <div class="content-block-title">Periodo</div>
      <div class="list-block">
        <ul>
          <li>
            <div class="item-content">
              <div class="item-inner">
                <div class="item-input">
                  <input type="text" placeholder="Seleccione un rango" readonly id="periodo">
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <div class="content-block-title">Consultores</div>
      <div class="list-block">
        <ul>
          <li>
            @include('elements.smart-select', $data)
          </li>
        </ul>
      </div>

      <div class="content-block inset">
        <p class="buttons-row">
          <a id="relatorio" data-route="{{action('ConsultoresController@relatorio')}}" href="#" class="button button-fill button-raised color-cyan">Relatorio</a>
          <a id="grafico" data-route="{{action('ConsultoresController@grafico')}}" href="#" class="button button-fill button-raised color-teal">Grafico</a>
          <a id="pizza" data-route="{{action('ConsultoresController@pizza')}}" href="#" class="button button-fill button-raised color-blue">Pizza</a>
        </p>
      </div>

      <div id="content" class="content-block inset">

      </div>

    </div>
     
  </div>
@endsection

@push('css')
  <link rel="stylesheet" type="text/css" href="{{asset('/css/material-table.css')}}">
@endpush

@push('js')
  <script type="text/javascript" src="{{asset('/js/chart.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/performance_comercial.js')}}"></script>
@endpush
