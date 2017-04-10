<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'cao_usuario';
    protected $primaryKey = 'co_usuario';
    public $incrementing = false;

    public static function consultores()
    {
        return static::join('permissao_sistema AS b', 'cao_usuario.co_usuario', 'b.co_usuario')
            ->where('b.co_sistema', 1)
            ->where('b.in_ativo', 'S')
            ->whereIn('b.co_tipo_usuario', [0,1,2])
        ;
    }

    public static function relatorioConsultores($users=[], $dates=[], $type='relatorio')
    {
        $query = static::select(
                'user.no_usuario AS nombre',
                'user.co_usuario AS id',
                'salario.brut_salario AS custo_fixo',
                DB::raw('CONCAT(MONTHNAME(fatura.data_emissao), \' \',YEAR(fatura.data_emissao)) as periodo'),
                DB::raw('SUM( fatura.valor - (fatura.valor * (fatura.total_imp_inc / 100)) ) AS receita_liquida')
            )
            ->from('cao_fatura AS fatura')
            ->join('cao_os AS os', 'fatura.co_os', 'os.co_os')
            ->join('cao_usuario AS user', 'os.co_usuario', 'user.co_usuario')
            ->leftJoin('cao_salario AS salario', 'os.co_usuario', 'salario.co_usuario')
        ;

        if($type == 'relatorio')
        {
            $query->addSelect(
                    DB::raw('SUM( (fatura.valor - (fatura.valor * (fatura.total_imp_inc / 100))) * (fatura.comissao_cn / 100) ) AS comissao')
                )
            ;
        }

        if( is_array($users) && !empty($users) )
            $query->whereIn('os.co_usuario', $users);

        else if( is_string($users) && !empty($users) )
            $query->where('os.co_usuario', $users);

        if( is_array($dates) && !empty($dates) )
            $query->whereBetween('fatura.data_emissao', $dates);

        $query->groupBy(
            'user.no_usuario',
            DB::raw('MONTH(fatura.data_emissao)')
        );

        return $query;
    }

    public function getLucro()
    {
        return $this->receita_liquida - ($this->custo_fixo + $this->comissao);
    }
}
