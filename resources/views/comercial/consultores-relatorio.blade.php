@inject('format', 'App\Helpers\FormatHelper')

@foreach($consultores as $key => $user)
  <div class="content-block-title">{{ $key }}</div>
  <div class="table-responsive-vertical shadow-z-1">
    <table class="table table-hover table-mc-indigo">
      <thead>
        <tr>
          <th>Periodo</th>
          <th>Receita Líquida</th>
          <th>Custo Fixo</th>
          <th>Comissão</th>
          <th>Lucro</th>
        </tr>
      </thead>
      <tbody>
      @php
        $total_receita = 0;
        $total_custo = 0;
        $total_comissao = 0;
        $total_lucro = 0;
      @endphp

        @foreach($user as $mes)
          @php
            $lucro = $mes->getLucro();
            $total_receita += $mes->receita_liquida;
            $total_custo += $mes->custo_fixo;
            $total_comissao += $mes->comissao;
            $total_lucro += $lucro;
          @endphp
          <tr>
            <td data-title="Periodo">{{ $mes->periodo }}</td>
            <td data-title="Receita">{{ $format->currency($mes->receita_liquida) }}</td>
            <td data-title="Custo">{{ $format->currency($mes->custo_fixo) }}</td>
            <td data-title="Comissão">{{ $format->currency($mes->comissao) }}</td>
            <td data-title="Lucro">{{ $format->currency( $lucro ) }}</td>
          </tr>
        @endforeach
        <tr>
          <td>Total</td>
          <td data-title="Receita">{{ $format->currency($total_receita) }}</td>
          <td data-title="Custo">{{ $format->currency($total_custo) }}</td>
          <td data-title="Comissão">{{ $format->currency($total_comissao) }}</td>
          <td data-title="Lucro">{{ $format->currency( $total_lucro ) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
@endforeach