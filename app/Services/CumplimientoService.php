<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CumplimientoService
{
    public static function calcularPerspectiva($perspectiva, $inicio, $fin)
    {
        $suma = 0;

        foreach ($perspectiva->objetivos as $objetivo) {

            // 🔹 INDICADORES
            foreach ($objetivo->indicadores_perspectiva as $indicador) {

                $valores = DB::table('indicadores_llenos')
                    ->where('id_indicador', $indicador->id)
                    ->where('final', 'on')
                    ->whereBetween('fecha_periodo', [$inicio, $fin])
                    ->pluck('informacion_campo');

                if ($valores->isEmpty()) continue;

                $avg = $valores->avg();

                if ($indicador->tipo_indicador == "normal") {
                    $cumplimiento = $indicador->unidad_medida == "porcentaje"
                        ? $avg
                        : ($avg / $indicador->meta_esperada) * 100;
                } else {
                    $cumplimiento = $indicador->unidad_medida == "porcentaje"
                        ? 100 - $avg
                        : ($indicador->meta_esperada / $avg) * 100;
                }

                $suma += ($cumplimiento * $indicador->ponderacion) / 100;
            }

            // 🔹 ENCUESTAS
            foreach ($objetivo->encuestas_perspectiva as $encuesta) {

                $rangos = [];

                if ($inicio->month <= 6) {
                    $rangos[] = [$inicio->copy()->startOfYear(), $inicio->copy()->month(6)->endOfMonth()];
                }

                if ($fin->month >= 7 || $inicio->year !== $fin->year) {
                    $rangos[] = [$fin->copy()->month(7)->startOfMonth(), $fin->copy()->endOfYear()];
                }

                $query = DB::table('respuestas as r')
                    ->join('preguntas as p', 'r.id_pregunta', '=', 'p.id')
                    ->where('p.cuantificable', 1);

                $query->where(function ($q) use ($rangos) {
                    foreach ($rangos as $r) {
                        $q->orWhereBetween('r.created_at', [$r[0], $r[1]]);
                    }
                });

                $avg = $query->avg(DB::raw('CAST(r.respuesta AS DECIMAL(10,2))'));

                $cumplimiento = round(($avg ?? 0) * 10, 2);

                $suma += ($cumplimiento * $encuesta->ponderacion_encuesta) / 100;
            }

            // 🔹 NORMAS
            foreach ($objetivo->normas_perspectiva as $norma) {

                $data = DB::table('apartado_norma as an')
                    ->join('norma as n', 'an.id_norma', '=', 'n.id')
                    ->leftJoin('cumplimiento_norma as cn', function ($join) use ($inicio, $fin) {
                        $join->on('cn.id_apartado_norma', '=', 'an.id')
                             ->whereBetween('cn.created_at', [$inicio, $fin]);
                    })
                    ->where('n.id', $norma->id)
                    ->selectRaw('
                        COUNT(DISTINCT an.id) as total,
                        (COUNT(DISTINCT cn.id_apartado_norma) / COUNT(DISTINCT an.id)) * 100 as porcentaje
                    ')
                    ->first();

                $cumplimiento = $data->porcentaje ?? 0;

                $suma += ($cumplimiento * $norma->ponderacion_norma) / 100;
            }
        }

        return round($suma, 2);
    }
}


