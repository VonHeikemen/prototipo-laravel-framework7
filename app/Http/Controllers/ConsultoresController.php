<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class ConsultoresController extends Controller
{
    public $_rule = [
        'consultores' => 'required',
        'periodo' => 'required'
    ];

    public function randomColor()
    {
        $r = mt_rand(0, 255);
        $g = mt_rand(0, 255);
        $b = mt_rand(0, 255);
        
        return "rgb($r,$g,$b)";
    }

    public function getPeriodo($value)
    {
        return [
            Carbon::parse($value[0])->startOfDay()->startOfMonth()->toDateString(),
            Carbon::parse($value[1])->endOfDay()->endOfMonth()->toDateString()
        ];
    }

    public function getRelatorio($peticion, $type)
    {
        $this->validate($peticion, $this->_rule);

        $consultores = $peticion->input('consultores');
        $periodo = $this->getPeriodo($peticion->periodo);

        $relatorio = User::relatorioConsultores($consultores, $periodo, $type)
                        ->get();

        return $relatorio;
    }

    public function index()
    {
        $select_options = User::consultores()->pluck('no_usuario', 'b.co_usuario');

        $select_params = [
            'id' => 'select_consultores',
            'label' => 'Buscar...',
            'picker_title' => 'Consultores',
            'select_name' => 'consultores[]',
            'is_multiple' => true,
            'options' => $select_options,
            'placeholder' => 'Buscar consultores'
        ];

        return view('comercial.performance', ['data' => $select_params]);
    }

    public function relatorio(Request $peticion)
    {
        $relatorio = $this->getRelatorio($peticion, 'relatorio');

        $data = [
            'consultores' => $relatorio->groupBy('nombre')
        ];

        return view('comercial.consultores-relatorio', $data);
    }

    public function grafico(Request $peticion)
    {
        $relatorio = $this->getRelatorio($peticion, 'grafico');

        $months = $relatorio->groupBy('periodo')->keys()->all();
        $users = $relatorio->groupBy('nombre');
        $custo_fixo_medio = $users->reduce(function ($carry, $item) {
            return $carry + $item[0]->custo_fixo;;
        }, 0);
        $custo_fixo_medio /= count($users);

        $datasets = [
            [
                'type' => 'line',
                'label' => 'Custo Fixo Medio',
                'backgroundColor' => "rgba(75,192,192,0.4)",
                'data' => [],
            ]
        ];

        foreach ($users as $i => $user) 
        {
            $color = $this->randomColor();
            $set = [
                'type' => 'bar',
                'label' => $i,
                'data' => [],
                'backgroundColor' => []
            ];

            $month_receita = $user->groupBy('periodo');

            foreach ($months as $key => $value) 
            {
                $datasets[0]['data'][] = $custo_fixo_medio;

                $set['data'][] = ( isset($month_receita[$value]) ) 
                    ? $month_receita[$value][0]->receita_liquida
                    : 0;

                $set['backgroundColor'][] = $color;
            }

            $datasets[] = $set;
        }

        $params = [
            'type' => 'bar',
            'data' => [
                'labels' => $months,
                'datasets' => $datasets
            ]
        ];

        return response()->json($params);
    }

    public function pizza(Request $peticion)
    {
        $relatorio = $this->getRelatorio($peticion, 'pizza');
        $total = $relatorio->sum('receita_liquida');
        $percent = $relatorio->groupBy('nombre')
            ->mapWithKeys(function($item) use($total){
                $couta = round( ($item->sum('receita_liquida') * 100) / $total, 0 );
                return [$item[0]->nombre => $couta];
            });

        $datasets = [
            [
                'type' => 'pie',
                'label' => $percent->keys()->all(),
                'backgroundColor' => [],
                'data' => [],
            ]
        ];

        foreach ($percent as $key => $value) {
            $color = $this->randomColor();
            
            $datasets[0]['data'][] = $value;
            $datasets[0]['backgroundColor'][] = $color;
            $datasets[0]['hoverBackgroundColor'][] = $color;
        }

        $params = [
            'type' => 'pie',
            'data' => [
                'labels' => $percent->keys()->all(),
                'datasets' => $datasets
            ]
        ];

        return response()->json($params);
    }
}
