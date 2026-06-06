<?php

namespace App\Imports;

use App\Models\InformacionInputPrecargado;
use App\Models\CampoPrecargado;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use App\Models\CampoForaneo;
use App\Models\CampoForaneoInformacion;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class InputPrecargadoImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            $fecha = Carbon::now();
            $mes = $fecha->month;
            $year = $fecha->year;



                foreach ($rows as $row) {
                    
                    if (empty($row['id'] ?? null) || empty($row['nombre'] ?? null)) {
                        continue;
                    }

                    $campoForaneo = CampoForaneo::firstOrCreate(
                        ['id_input' => $row['id']],
                        ['nombre'   => $row['nombre'],
                        'descripcion' => $row['descripcion']]
                    );


                    CampoForaneoInformacion::create([
                        'id_campo_foraneo' => $campoForaneo->id,
                        'informacion'      => $row['informacion'],
                        'mes'              => $mes,
                        'year'             => $year,
                    ]);


                    //Aqui puedo poner la logica 
                    //aqui estoy tomando el primer resultado de input_precargado y le estoy insertando los datos... Lo idealk seria insertarlo en todos los input_precargado
                    $input_precargados = CampoPrecargado::where('id_input_foraneo', $campoForaneo->id_input)->get();

                    if($input_precargados){

                        foreach($input_precargados as $input_precargado){
                            
                            InformacionInputPrecargado::create([
                                'informacion' => $row['informacion'],
                                'id_input_precargado' => $input_precargado->id,
                                'mes' => $mes,
                                'year' => $year
                                
                            ]);
                        }




                    }

                }
            });
    }
}
