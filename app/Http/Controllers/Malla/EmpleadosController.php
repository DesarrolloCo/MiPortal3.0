<?php

namespace App\Http\Controllers\Malla;

use \Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

use App\Models\campana;
use App\Models\cliente;
use App\Models\unidad_negocio;
use App\Models\uni_cli;
use App\Models\malla;
use App\Models\jornada;
use App\Models\empleado;
use App\Models\User;
use App\Models\cargo;
use App\Models\departamento;
use App\Models\municipio;
use App\Models\emp_contrato;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;


class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Cargar empleados con sus relaciones usando Eloquent (eager loading)
        // Esto previene N+1 queries y mejora el rendimiento
        $empleados = empleado::with(['users', 'cargo', 'cliente', 'municipio'])
            ->where('EMP_ESTADO', 1)
            ->orderBy('EMP_NOMBRES')
            ->get();

        // Cargar datos para los formularios
        $departamentos = departamento::where('DEP_ESTADO', 1)->get();
        $cargos = cargo::where('CAR_ESTADO', 1)->get();
        $clientes = cliente::where('CLI_ESTADO', 1)->get();

        return view('Malla.Empleado.index', compact('empleados', 'cargos', 'departamentos', 'clientes'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        // Validar input
        $validated = $request->validate([
            'estado' => 'required|in:0,1'
        ], [
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser 0 (Inactivo) o 1 (Activo)'
        ]);

        $empleado = empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        // Verificar que el empleado no esté eliminado
        if ($empleado->EMP_ESTADO != 1) {
            return response()->json(['error' => 'El empleado está eliminado'], 400);
        }

        $empleado->EMP_ACTIVO = $validated['estado'];
        $empleado->save();

        return response()->json([
            'mensaje' => 'Estado actualizado correctamente',
            'estado' => $empleado->EMP_ACTIVO
        ]);
    }

    public function create(Request $request)
    {
        // Validación completa con mensajes personalizados
        $validated = $request->validate([
            'EMP_CODE' => 'required|string|max:50|unique:empleados,EMP_CODE',
            'EMP_CEDULA' => 'required|string|max:20|unique:empleados,EMP_CEDULA',
            'EMP_NOMBRES' => 'required|string|max:255',
            'EMP_DIRECCION' => 'nullable|string|max:255',
            'EMP_TELEFONO' => 'nullable|string|max:20',
            'MUN_ID' => 'required|exists:municipios,MUN_ID',
            'EMP_SEXO' => 'required|in:F,M',
            'EMP_FECHA_NACIMIENTO' => 'required|date|before:today',
            'EMP_FECHA_INGRESO' => 'required|date',
            'CLI_ID' => 'required|exists:clientes,CLI_ID',
            'EMP_EMAIL' => 'required|email|max:255|unique:users,email',
            'EMP_SUELDO' => 'nullable|numeric|min:0',
        ], [
            'EMP_CODE.required' => 'El código del empleado es obligatorio',
            'EMP_CODE.unique' => 'El código del empleado ya existe',
            'EMP_CEDULA.required' => 'La cédula es obligatoria',
            'EMP_CEDULA.unique' => 'La cédula ya está registrada',
            'EMP_NOMBRES.required' => 'El nombre es obligatorio',
            'MUN_ID.required' => 'El municipio es obligatorio',
            'MUN_ID.exists' => 'El municipio seleccionado no existe',
            'EMP_SEXO.required' => 'El sexo es obligatorio',
            'EMP_SEXO.in' => 'El sexo debe ser F o M',
            'EMP_FECHA_NACIMIENTO.required' => 'La fecha de nacimiento es obligatoria',
            'EMP_FECHA_NACIMIENTO.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'EMP_FECHA_INGRESO.required' => 'La fecha de ingreso es obligatoria',
            'CLI_ID.required' => 'El cliente es obligatorio',
            'CLI_ID.exists' => 'El cliente seleccionado no existe',
            'EMP_EMAIL.required' => 'El correo electrónico es obligatorio',
            'EMP_EMAIL.email' => 'El correo electrónico debe ser válido',
            'EMP_EMAIL.unique' => 'El correo electrónico ya está registrado',
        ]);

        return DB::transaction(function () use ($validated){

            // Crear usuario usando mass assignment
            $user = User::create([
                'name' => $validated['EMP_NOMBRES'],
                'email' => $validated['EMP_EMAIL'],
                'password' => Hash::make($validated['EMP_CEDULA'])
            ]);

            $user->assignRole('Agente');

            // Crear empleado usando mass assignment
            $empleado = empleado::create([
                'USER_ID' => $user->id,
                'CAM_ID' => null,  // NULL por defecto (se usa CLI_ID en su lugar)
                'DEP_ID' => null,  // NULL por defecto
                'EMP_CODE' => $validated['EMP_CODE'],
                'EMP_CEDULA' => $validated['EMP_CEDULA'],
                'EMP_NOMBRES' => $validated['EMP_NOMBRES'],
                'EMP_DIRECCION' => $validated['EMP_DIRECCION'] ?? null,
                'EMP_TELEFONO' => $validated['EMP_TELEFONO'] ?? null,
                'EMP_SEXO' => $validated['EMP_SEXO'],
                'EMP_FECHA_NACIMIENTO' => $validated['EMP_FECHA_NACIMIENTO'],
                'EMP_FECHA_INGRESO' => $validated['EMP_FECHA_INGRESO'],
                'EMP_SUELDO' => $validated['EMP_SUELDO'] ?? null,
                'MUN_ID' => $validated['MUN_ID'],
                'CLI_ID' => $validated['CLI_ID'],
                'EMP_EMAIL' => $validated['EMP_EMAIL'],
                'EMP_ESTADO' => 1,
                'EMP_ACTIVO' => 1
            ]);

            return redirect()->back()->with('rgcmessage', 'Empleado y usuario creados con éxito');

        }, 5);
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id){

            // Buscar empleado usando find() en lugar de get()
            $empleado = empleado::find($id);

            // Validar que el empleado existe
            if (!$empleado) {
                return redirect()->back()->with('msjerror', 'Empleado no encontrado');
            }

            // Validar que el empleado no esté ya eliminado
            if ($empleado->EMP_ESTADO == 0) {
                return redirect()->back()->with('msjerror', 'El empleado ya está eliminado');
            }

            $user_id = $empleado->USER_ID;

            // Validar que tenga un usuario asociado
            if (!$user_id) {
                return redirect()->back()->with('msjerror', 'El empleado no tiene un usuario asociado');
            }

            // Buscar el usuario
            $user = User::find($user_id);

            if (!$user) {
                return redirect()->back()->with('msjerror', 'Usuario asociado no encontrado');
            }

            // Desactivar usuario (soft delete)
            $user->update(['estado' => '0']);

            // Remover roles anteriores
            DB::table('model_has_roles')->where('model_id', $user_id)->delete();

            // Asignar rol Inactivo
            $user->assignRole('Inactivo');

            // Desactivar empleado (soft delete)
            $empleado->update(['EMP_ESTADO' => '0']);

            return redirect()->back()->with('msjdelete', 'Empleado y usuario desactivados correctamente');

        }, 5);
    }

    public function update(Request $request, $id)
    {
        // Validar todos los campos
        $validated = $request->validate([
            'EMP_CODE' => 'required|string|max:50',
            'EMP_CEDULA' => 'required|string|max:20',
            'EMP_NOMBRES' => 'required|string|max:255',
            'EMP_DIRECCION' => 'nullable|string|max:255',
            'EMP_TELEFONO' => 'nullable|string|max:20',
            'MUN_ID' => 'required|exists:municipios,MUN_ID',
            'EMP_SEXO' => 'required|in:F,M',
            'EMP_FECHA_NACIMIENTO' => 'required|date|before:today',
            'EMP_FECHA_INGRESO' => 'required|date',
            'CLI_ID' => 'required|exists:clientes,CLI_ID',
            'EMP_EMAIL' => 'required|email|max:255',
            'EMP_SUELDO' => 'nullable|numeric|min:0',
        ], [
            'EMP_CODE.required' => 'El código del empleado es obligatorio',
            'EMP_CEDULA.required' => 'La cédula es obligatoria',
            'EMP_NOMBRES.required' => 'El nombre es obligatorio',
            'MUN_ID.required' => 'El municipio es obligatorio',
            'MUN_ID.exists' => 'El municipio seleccionado no existe',
            'EMP_SEXO.required' => 'El sexo es obligatorio',
            'EMP_SEXO.in' => 'El sexo debe ser F o M',
            'EMP_FECHA_NACIMIENTO.required' => 'La fecha de nacimiento es obligatoria',
            'EMP_FECHA_NACIMIENTO.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'EMP_FECHA_INGRESO.required' => 'La fecha de ingreso es obligatoria',
            'CLI_ID.required' => 'El cliente es obligatorio',
            'CLI_ID.exists' => 'El cliente seleccionado no existe',
            'EMP_EMAIL.required' => 'El correo electrónico es obligatorio',
            'EMP_EMAIL.email' => 'El correo electrónico debe ser válido',
        ]);

        // Verificar que el empleado existe
        $empleado = empleado::find($id);

        if (!$empleado) {
            Session::flash('msjerror', 'Empleado no encontrado');
            return redirect()->back();
        }

        // Verificar que el empleado no esté eliminado
        if ($empleado->EMP_ESTADO != 1) {
            Session::flash('msjerror', 'No se puede actualizar un empleado eliminado');
            return redirect()->back();
        }

        // Actualizar solo los campos validados
        $empleado->update($validated);

        // Actualizar también el email del usuario si cambió
        if ($empleado->users && $empleado->EMP_EMAIL !== $empleado->users->email) {
            $empleado->users->update(['email' => $validated['EMP_EMAIL']]);
        }

        Session::flash('msjupdate', '¡El empleado se ha actualizado correctamente!');
        return redirect()->back();
    }

    /**

    * @param Request $request

    * @return \Illuminate\Http\RedirectResponse

    * @throws \Illuminate\Validation\ValidationException

    * @throws \PhpOffice\PhpSpreadsheet\Exception

    */

   function importData(Request $request)
    {
        // Validar archivo
        $validated = $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:10240' // Max 10MB
        ], [
            'file.required' => 'Debe seleccionar un archivo Excel',
            'file.mimes' => 'El archivo debe ser de tipo Excel (.xls o .xlsx)',
            'file.max' => 'El archivo no debe superar los 10MB'
        ]);

        $the_file = $request->file('file');
        $errores = [];
        $exitosos = 0;
        $actualizados = 0;

        try {
            return DB::transaction(function () use ($the_file, &$errores, &$exitosos, &$actualizados) {
                $spreadsheet = IOFactory::load($the_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();

                // Validar que el archivo tenga datos
                if ($row_limit < 2) {
                    return back()->with('error', 'El archivo no contiene datos para importar');
                }

                // Leer todos los datos del archivo
                $data = [];
                for ($row = 2; $row <= $row_limit; $row++) {
                    $rowData = [
                        'fila' => $row,
                        'cargo' => $sheet->getCell('A' . $row)->getValue(),
                        'codigo' => $sheet->getCell('B' . $row)->getValue(),
                        'cedula' => $sheet->getCell('C' . $row)->getValue(),
                        'municipio_expedida' => $sheet->getCell('D' . $row)->getValue(),
                        'nombres' => $sheet->getCell('E' . $row)->getValue(),
                        'direccion' => $sheet->getCell('F' . $row)->getValue(),
                        'telefono' => $sheet->getCell('G' . $row)->getValue(),
                        'sexo' => $sheet->getCell('H' . $row)->getValue(),
                        'fecha_nacimiento' => $sheet->getCell('I' . $row)->getValue(),
                        'fecha_ingreso' => $sheet->getCell('J' . $row)->getValue(),
                        'fecha_retiro' => $sheet->getCell('K' . $row)->getValue(),
                        'sueldo' => $sheet->getCell('L' . $row)->getValue(),
                        'tipo_de_contrato' => $sheet->getCell('M' . $row)->getValue(),
                        'cliente' => $sheet->getCell('N' . $row)->getValue(),
                        'email' => $sheet->getCell('O' . $row)->getValue(),
                    ];

                    // Validar datos obligatorios
                    if (empty($rowData['nombres']) || empty($rowData['cedula']) || empty($rowData['email'])) {
                        $errores[] = "Fila {$row}: Faltan datos obligatorios (nombres, cédula o email)";
                        continue;
                    }

                    $data[] = $rowData;
                }

                if (empty($data)) {
                    return back()->with('error', 'No hay datos válidos para importar');
                }

                // OPTIMIZACIÓN: Cargar todos los cargos y clientes de una sola vez (elimina N+1 query)
                $codigosCargos = array_unique(array_column($data, 'cargo'));
                $codigosClientes = array_unique(array_column($data, 'cliente'));

                $cargosMap = cargo::whereIn('CAR_CODE', $codigosCargos)
                    ->where('CAR_ESTADO', 1)
                    ->get()
                    ->keyBy('CAR_CODE');

                $clientesMap = cliente::whereIn('CLI_CODE', $codigosClientes)
                    ->where('CLI_ESTADO', 1)
                    ->get()
                    ->keyBy('CLI_CODE');

                // Obtener cédulas existentes para evitar duplicados
                $cedulasExistentes = empleado::whereIn('EMP_CEDULA', array_column($data, 'cedula'))
                    ->pluck('EMP_ID', 'EMP_CEDULA');

                // Procesar cada fila
                foreach ($data as $rowData) {
                    try {
                        $fila = $rowData['fila'];

                        // Validar que el cargo exista
                        if (!isset($cargosMap[$rowData['cargo']])) {
                            $errores[] = "Fila {$fila}: Código de cargo '{$rowData['cargo']}' no encontrado";
                            continue;
                        }

                        // Validar que el cliente exista
                        if (!isset($clientesMap[$rowData['cliente']])) {
                            $errores[] = "Fila {$fila}: Código de cliente '{$rowData['cliente']}' no encontrado";
                            continue;
                        }

                        $cargo = $cargosMap[$rowData['cargo']];
                        $cliente = $clientesMap[$rowData['cliente']];

                        // Convertir fechas de Excel
                        try {
                            $fechaNacimiento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData['fecha_nacimiento'])->format('Y-m-d');
                            $fechaIngreso = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData['fecha_ingreso'])->format('Y-m-d');
                            $fechaRetiro = !empty($rowData['fecha_retiro'])
                                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowData['fecha_retiro'])->format('Y-m-d')
                                : null;
                        } catch (\Exception $e) {
                            $errores[] = "Fila {$fila}: Error en formato de fechas";
                            continue;
                        }

                        // Verificar si el empleado ya existe
                        $empleadoExiste = isset($cedulasExistentes[$rowData['cedula']]);

                        if (!$empleadoExiste) {
                            // CREAR NUEVO EMPLEADO

                            // Validar email único
                            if (User::where('email', $rowData['email'])->exists()) {
                                $errores[] = "Fila {$fila}: El email '{$rowData['email']}' ya está registrado";
                                continue;
                            }

                            try {
                                // Crear usuario
                                $user = User::create([
                                    'name' => $rowData['nombres'],
                                    'email' => $rowData['email'],
                                    'password' => Hash::make($rowData['cedula'])
                                ]);
                                $user->assignRole('Agente');

                                // Crear empleado - Con manejo de errores detallado
                                $empleado = empleado::create([
                                    'USER_ID' => $user->id,
                                    'CAR_ID' => $cargo->CAR_ID,
                                    'CAM_ID' => null,  // NULL por defecto (se usa CLI_ID en su lugar)
                                    'DEP_ID' => null,  // NULL por defecto
                                    'EMP_CODE' => $rowData['codigo'],
                                    'EMP_CEDULA' => $rowData['cedula'],
                                    'MUN_ID' => $rowData['municipio_expedida'],
                                    'EMP_NOMBRES' => $rowData['nombres'],
                                    'EMP_DIRECCION' => $rowData['direccion'],
                                    'EMP_TELEFONO' => $rowData['telefono'],
                                    'EMP_SEXO' => $rowData['sexo'],
                                    'EMP_FECHA_NACIMIENTO' => $fechaNacimiento,
                                    'EMP_FECHA_INGRESO' => $fechaIngreso,
                                    'EMP_FECHA_RETIRO' => $fechaRetiro,
                                    'EMP_SUELDO' => $rowData['sueldo'],
                                    'EMP_TIPO_CONTRATO' => $rowData['tipo_de_contrato'],
                                    'CLI_ID' => $cliente->CLI_ID,
                                    'EMP_EMAIL' => $rowData['email'],
                                    'EMP_ESTADO' => 1,
                                    'EMP_ACTIVO' => 1
                                ]);

                                $empId = $empleado->EMP_ID;
                                $exitosos++;
                            } catch (\Illuminate\Database\QueryException $e) {
                                $errores[] = "Fila {$fila}: Error SQL al crear empleado - " . $e->getMessage();
                                // Si falla el empleado, eliminar el usuario creado para mantener consistencia
                                if (isset($user)) {
                                    $user->delete();
                                }
                                continue;
                            } catch (\Exception $e) {
                                $errores[] = "Fila {$fila}: Error al crear empleado - " . $e->getMessage();
                                // Si falla el empleado, eliminar el usuario creado para mantener consistencia
                                if (isset($user)) {
                                    $user->delete();
                                }
                                continue;
                            }
                        } else {
                            // ACTUALIZAR EMPLEADO EXISTENTE
                            $empId = $cedulasExistentes[$rowData['cedula']];
                            $empleado = empleado::find($empId);

                            if (!$empleado) {
                                $errores[] = "Fila {$fila}: Error al cargar el empleado existente";
                                continue;
                            }

                            // Validar que el email no esté usado por otro usuario (excepto el actual)
                            $emailEnUso = User::where('email', $rowData['email'])
                                ->where('id', '!=', $empleado->USER_ID)
                                ->exists();

                            if ($emailEnUso) {
                                $errores[] = "Fila {$fila}: El email '{$rowData['email']}' ya está registrado por otro usuario";
                                continue;
                            }

                            // Actualizar datos del empleado
                            $empleado->update([
                                'CAR_ID' => $cargo->CAR_ID,
                                'EMP_CODE' => $rowData['codigo'],
                                'MUN_ID' => $rowData['municipio_expedida'],
                                'EMP_NOMBRES' => $rowData['nombres'],
                                'EMP_DIRECCION' => $rowData['direccion'],
                                'EMP_TELEFONO' => $rowData['telefono'],
                                'EMP_SEXO' => $rowData['sexo'],
                                'EMP_FECHA_NACIMIENTO' => $fechaNacimiento,
                                'EMP_FECHA_INGRESO' => $fechaIngreso,
                                'EMP_FECHA_RETIRO' => $fechaRetiro,
                                'EMP_SUELDO' => $rowData['sueldo'],
                                'EMP_TIPO_CONTRATO' => $rowData['tipo_de_contrato'],
                                'CLI_ID' => $cliente->CLI_ID,
                                'EMP_EMAIL' => $rowData['email']
                            ]);

                            // Actualizar email y nombre del usuario si cambió
                            if ($empleado->users) {
                                if ($empleado->users->email !== $rowData['email'] || $empleado->users->name !== $rowData['nombres']) {
                                    $empleado->users->update([
                                        'email' => $rowData['email'],
                                        'name' => $rowData['nombres']
                                    ]);
                                }
                            }

                            $actualizados++;
                        }

                        // Verificar si ya tiene contrato activo (OPTIMIZADO: sin SQL injection)
                        $tieneContratoActivo = emp_contrato::where('EMP_ID', $empId)
                            ->where('EMC_FINALIZADO', 'NO')
                            ->exists();

                        if (!$tieneContratoActivo) {
                            // Crear nuevo contrato
                            emp_contrato::create([
                                'EMP_ID' => $empId,
                                'CAR_ID' => $cargo->CAR_ID,
                                'TIC_ID' => $rowData['tipo_de_contrato'],
                                'EMC_SUELDO' => $rowData['sueldo'],
                                'USER_CREATED' => Auth::id(),
                                'EMC_FECHA_INI' => $fechaIngreso,
                                'EMC_FECHA_FIN' => $fechaRetiro,
                                'EMC_FINALIZADO' => 'NO'
                            ]);
                        }

                    } catch (\Exception $e) {
                        $errores[] = "Fila {$rowData['fila']}: {$e->getMessage()}";
                        continue;
                    }
                }

                // Preparar mensaje de resultado
                $mensaje = "Importación completada: ";
                if ($exitosos > 0) {
                    $mensaje .= "{$exitosos} empleados creados";
                }
                if ($actualizados > 0) {
                    $mensaje .= ($exitosos > 0 ? ", " : "") . "{$actualizados} empleados actualizados";
                }
                if ($exitosos == 0 && $actualizados == 0) {
                    $mensaje .= "ningún empleado procesado";
                }
                if (count($errores) > 0) {
                    $mensaje .= ", " . count($errores) . " errores encontrados";
                }

                // Retornar con mensaje y errores si los hay
                if (count($errores) > 0) {
                    return redirect()->back()
                        ->with('rgcmessage', $mensaje)
                        ->with('import_errors', $errores);
                }

                return redirect()->back()->with('rgcmessage', $mensaje);
            });

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return back()->with('error', 'Error al leer el archivo Excel: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Error en la importación: ' . $e->getMessage());
        }
    }

}
