<?php

namespace App\Http\Controllers\usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTime;
use DB;

// MODELOS
use App\User;
use App\modelos\usuario\rolModel;
use App\modelos\usuario\empleadoModel;
use App\modelos\usuario\asignarrolesModel;
use App\modelos\catalogos\ProveedorModel;


//Configuracion Zona horaria
date_default_timezone_set('America/Mexico_City');

class usuarioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('Superusuario,Administrador,Proveedor,Reconocimiento,Proyecto,Compras,Staff,Psicólogo,Ergónomo,CoordinadorPsicosocial,CoordinadorErgonómico,CoordinadorRN,CoordinadorRS,CoordinadorRM,CoordinadorHI,Reportes,ApoyoTecnico,Cadista,Financiero,Externo,Coordinador');
        // $this->middleware('roles:Superusuario,Administrador,Usuario');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRoles(['Superusuario', 'Financiero']))
        {
            $proveedores = ProveedorModel::where('proveedor_Eliminado', 0)->orderBy('proveedor_NombreComercial', 'ASC')->get();
            $usuarios = User::all();
            // $roles = rolModel::orderBy('rol_Orden', 'ASC')->get();
            $roles = rolModel::where('ACTIVO', 1)->orderBy('rol_Orden', 'ASC')->get();


            $permiso = 0;
            if (auth()->user()->hasRoles(['Superusuario']))
            {
                $permiso = 1;
            }


            return view('catalogos.usuario.usuario', compact('usuarios', 'roles', 'proveedores', 'permiso'));
        }
        else
        {
            return view('catalogos.usuario.usuarioperfil');
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usuariotabla()
    {
        try
        {
            $usuarios = User::with(['empleado', 'roles'])
                            // ->whereIn('id', [1])
                            ->orderBy('id', 'ASC')
                            ->get();


            // dd($usuarios);


            // formatear Filas
            $numero_registro = 0; $checkbox_disabled = ''; $checkbox_color = '';
            foreach ($usuarios as $key => $value) 
            {
                $numero_registro += 1;
                $value->numero_registro = $numero_registro;

                // Activas botones de impresion
                switch ($value->usuario_tipo+0)
                {
                    case 1:
                        $value->tipo = "Empleado";
                        // $value->empleado->empleado_fechanacimiento = Carbon::createFromFormat('Y-m-d', $value->empleado->empleado_fechanacimiento)->format('d-m-Y');
                        break;
                    default:
                        $value->tipo = "Proveedor";
                        break;
                }


                // $value->foto_usuario = '<img src="/usuariofoto/'.$value->id.'" alt="" class="img-circle d-block img-fluid" height="40" width="32">';
                $value->foto_usuario = '<img src="/usuariofoto/'.$value->id.'" alt="" class="img-fluid" width="50" height="60">';


                // Nombre del usuario
                if (($value->usuario_tipo + 0) == 1)
                {
                    $value->nombre_completo = $value->name.' '.$value->empleado->empleado_apellidopaterno.' '.$value->empleado->empleado_apellidomaterno.'<br>'.$value->empleado->empleado_cargo;
                    $value->correo_telefono = $value->email.'<br>'.$value->empleado->empleado_telefono;
                }
                else //Proveedores
                {
                    $value->nombre_completo = $value->name;
                    $value->correo_telefono = $value->email;
                }


                // Verificar si es Superusuario
                $value->superusuario = 0;
                $value->Financiero = 0;
                $value->roles_acceso = '';


                foreach ($value->roles as $key => $rol) 
                {
                    if ($rol->id == 1) //Superusuario
                    {
                        $value->superusuario = 1;
                    }


                    if ($rol->id == 19) //Financiero
                    {
                        $value->Financiero = 1;
                    }

                    $value->roles_acceso .= '<li>'.$rol->rol_Nombre.'</li>';
                }


                // Botones
                if (auth()->user()->hasRoles(['Superusuario']))
                {
                    if (auth()->user()->id == $value->id)
                    {
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                        $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';

                        $checkbox_color = 'lever switch-col-red';
                        $checkbox_disabled = 'disabled';
                    }
                    else
                    {
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                        $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar"><i class="fa fa-trash"></i></button>';

                        $checkbox_color = 'lever switch-col-light-blue';
                        $checkbox_disabled = '';
                    }
                }
                else
                {
                    if ($value->superusuario == 1)
                    {
                        $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                        $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';

                        $checkbox_disabled = 'disabled';
                        $checkbox_color = 'lever switch-col-red';
                    }
                    else if ($value->Financiero == 1)
                    {
                        if (auth()->user()->id == $value->id)
                        {
                            $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                            $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                        }
                        else
                        {
                            $value->boton_editar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                            $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                        }

                        $checkbox_color = 'lever switch-col-red';
                        $checkbox_disabled = 'disabled';
                    }
                    else
                    {
                        $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                        $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';

                        $checkbox_color = 'lever switch-col-light-blue';
                        $checkbox_disabled = '';
                    }
                }


                // ESTADO DEL USUARIO [ACTIVO / INACTIVO]
                if ($value->usuario_activo == 1)
                {
                    $value->checkbox_estado = '<div class="switch">
                                                    <label>
                                                        <input type="checkbox" value="'.$value->id.'" onclick="usuario_estado(this, \''.$value->name.'\');" checked '.$checkbox_disabled.'>
                                                        <span class="'.$checkbox_color.'" style="margin: 0px;"></span>
                                                    </label>
                                                </div>';
                }
                else
                {
                    $value->checkbox_estado = '<div class="switch">
                                                    <label>
                                                        <input type="checkbox" value="'.$value->id.'" onclick="usuario_estado(this, \''.$value->name.'\');" '.$checkbox_disabled.'>
                                                        <span class="'.$checkbox_color.'"></span>
                                                    </label>
                                                </div>';
                }







                // // Boton eliminar
                // if ($value->superusuario == 0)
                // {
                //     if ($value->administrador == 1)
                //     {
                //         if (auth()->user()->id == $value->id)
                //         {
                //             $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                //             $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                //         }
                //         else
                //         {
                //             $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-pencil"></i></button>';
                //             $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                //         }
                //     }
                //     else
                //     {
                //         $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                //         $value->boton_eliminar = '<button type="button" class="btn btn-danger btn-circle eliminar"><i class="fa fa-trash"></i></button>';
                //     }
                // }
                // else
                // {
                //     $value->tipo = "Superusuario";
                //     $value->boton_editar = '<button type="button" class="btn btn-warning btn-circle editar"><i class="fa fa-pencil"></i></button>';
                //     $value->boton_eliminar = '<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-ban"></i></button>';
                // }

            }

            // respuesta
            $dato['data'] = $usuarios;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['data'] = 0;
            return response()->json($dato);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if ($request['usuario_admin'] == 1) // EDICION DESDE MODULO ADMINISTRADOR
            {
                if ($request['usuario_id'] == 0) //nuevo
                {
                    // TIPO DE USUARIO
                    if ($request['usuario_tipo'] == 1) //USUARIO
                    {
                        // guardar empleado
                        DB::statement('ALTER TABLE empleado AUTO_INCREMENT=1');
                        $empleado = empleadoModel::create($request->all());

                        // guardar usuario
                        DB::statement('ALTER TABLE usuario AUTO_INCREMENT=1');
                        $usuario = User::create([
                              'usuario_tipo' => $request['usuario_tipo']
                            , 'empleado_id' => $empleado->id
                            , 'name' => $request['empleado_nombre']
                            , 'email' => $request['empleado_correo']
                            , 'password' => bcrypt($request['password'])
                            , 'clave' => $request['password']
                            , 'usuario_activo' => 1
                        ]);

                        // guardar roles
                        $usuario->asignarroles()->sync($request->rol);
                    }
                    else
                    {
                        // guardar usuario
                        DB::statement('ALTER TABLE usuario AUTO_INCREMENT=1');
                        $usuario = User::create([
                              'usuario_tipo' => $request['usuario_tipo']
                            , 'empleado_id' => $request['proveedor_id']
                            , 'name' => $request['proveedor_nombre']
                            , 'email' => $request['proveedor_correo']
                            , 'password' => bcrypt($request['password'])
                            , 'clave' => $request['password']
                            , 'usuario_activo' => 1
                        ]);

                        // guardar roles
                        $usuario->asignarroles()->sync($request->rol);
                    }

                    // mensaje
                    $dato["msj"] = 'Información guardada correctamente';
                }
                else //editar
                {
                    // TIPO DE USUARIO
                    if ($request['empleado_nombre'] != "") //USUARIO
                    {
                        // guardar usuario
                        $usuario = User::findOrFail($request['usuario_id']);
                        $usuario->update([
                              'name' => $request['empleado_nombre']
                            , 'email' => $request['empleado_correo']
                            , 'password' => bcrypt($request['password'])
                            , 'clave' => $request['password']
                        ]);

                        // modificar empleado
                        $empleado = empleadoModel::findOrFail($usuario->empleado_id);
                        $empleado->update($request->all());

                        // guardar roles
                        $usuario->asignarroles()->sync($request->rol);
                    }
                    else
                    {
                        // guardar usuario
                        $usuario = User::findOrFail($request['usuario_id']);
                        $usuario->update([
                              'name' => $request['proveedor_nombre']
                            , 'email' => $request['proveedor_correo']
                            , 'password' => bcrypt($request['password'])
                            , 'clave' => $request['password']
                        ]);
                    }

                    // mensaje
                    $dato["msj"] = 'Información modificada correctamente';
                }

                // VALIDA ESTADO DEL USUARIO
                if (($usuario->usuario_activo+0) == 0)
                {
                    // Modificar el password
                    $usuario->update([
                        'password' => bcrypt('_'.date('Ymdhmi').'*')
                    ]);
                }

                // si envia FOTO
                if ($request->file('fotousuario'))
                {
                    $extension = $request->file('fotousuario')->getClientOriginalExtension();

                    // TIPO DE USUARIO
                    if ($request['empleado_nombre'] != "") //USUARIO
                    {
                        // tabla usuario
                        $request['usuario_foto'] = $request->file('fotousuario')->storeAs('usuario/'.$usuario->id.'/foto', $usuario->id.'.'.$extension);
                        $usuario->update(['usuario_foto' => $request['usuario_foto']]);

                        // tabla empleado
                        $empleado->update(['empleado_foto' => $request['usuario_foto']]);
                    }
                    else
                    {
                        // tabla usuario
                        $request['usuario_foto'] = $request->file('fotousuario')->storeAs('usuario/'.$usuario->id.'/foto', $usuario->id.'.'.$extension);
                        $usuario->update(['usuario_foto' => $request['usuario_foto']]);
                    }
                }
            }
            else // EDICION DESDE MODULO USUARIO PERFIL
            {
                if ((auth()->user()->usuario_tipo+0) == 1) //Usuario
                {
                    // Modificar datos usuario
                    $usuario = User::findOrFail(auth()->user()->id);
                    $usuario->update([
                          'email' => $request['usuarioperfil_correo']
                        , 'password' => bcrypt($request['usuarioperfil_password'])
                        , 'clave' => $request['usuarioperfil_password']
                    ]);

                    // Modificar datos empleado
                    $empleado = empleadoModel::findOrFail(auth()->user()->empleado_id);
                    $empleado->update([
                        'empleado_correo' => $request['usuarioperfil_correo']
                    ]);
                }
                else //Proveedor
                {
                    // Modificar datos usuario
                    $usuario = User::findOrFail(auth()->user()->id);
                    $usuario->update([
                          'email' => $request['usuarioperfil_correo']
                        , 'password' => bcrypt($request['usuarioperfil_password'])
                        , 'clave' => $request['usuarioperfil_password']
                    ]);
                }

                // si envia FOTO
                if ($request->file('fotoperfil'))
                {
                    $extension = $request->file('fotoperfil')->getClientOriginalExtension();

                    // TIPO DE USUARIO
                    if ((auth()->user()->usuario_tipo+0) == 1) //Usuario
                    {
                        // tabla usuario
                        $request['usuario_foto'] = $request->file('fotoperfil')->storeAs('usuario/'.auth()->user()->id.'/foto', auth()->user()->id.'.'.$extension);
                        $usuario->update(['usuario_foto' => $request['usuario_foto']]);

                        // tabla empleado
                        $empleado->update(['empleado_foto' => $request['usuario_foto']]);
                    }
                    else
                    {
                        // tabla usuario
                        $request['usuario_foto'] = $request->file('fotoperfil')->storeAs('usuario/'.auth()->user()->id.'/foto', auth()->user()->id.'.'.$extension);
                        $usuario->update(['usuario_foto' => $request['usuario_foto']]);
                    }
                }

                // VALIDA ESTADO DEL USUARIO
                if (($usuario->usuario_activo+0) == 0)
                {
                    // Modificar el password
                    $usuario->update([
                        'password' => bcrypt('_'.date('Ymdhmi').'*')
                    ]);
                }

                // mensaje
                $dato["msj"] = 'Información modificada correctamente, los cambios se veran reflejados en el proximo inicio de sesión';
            }

            // respuesta
            $dato['usuario'] = $usuario;
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            $dato['usuario'] = null;
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $usuario_id
     * @param  int  $usuario_estado
     * @return \Illuminate\Http\Response
     */
    public function usuarioestado($usuario_id, $usuario_estado)
    {
        try
        {
            // Consultar usuario
            $usuario = User::findOrFail($usuario_id);

            // Validar nuevo estado
            if (($usuario_estado+0) == 1) //Activar
            {
                // Modificar el password
                $usuario->update([
                      'password' => bcrypt($usuario->clave)
                    , 'usuario_activo' => 1
                ]);
            }
            else
            {
                // Modificar el password
                $usuario->update([
                      'password' => bcrypt('_'.date('Ymdhmi').'*')
                    , 'usuario_activo' => 0
                ]);
            }

            // respuesta
            $dato["msj"] = 'Perfil del usuario modificado correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $usuario_id
     * @param  int  $usuario_tipo
     * @param  int  $empleado_id
     * @return \Illuminate\Http\Response
     */
    public function usuarioeliminar($usuario_id, $usuario_tipo, $empleado_id)
    {
        try
        {
            if (($usuario_tipo+0) == 1) //Usuario
            {
                $eliminar_usuario = User::where('id', $usuario_id)->delete();
                $eliminar_empleado = empleadoModel::where('id', $empleado_id)->delete();
                $eliminar_roles = asignarrolesModel::where('usuario_id', $usuario_id)->delete();
            }
            else //Proveedor
            {
                $eliminar_usuario = User::where('id', $usuario_id)->delete();
                $eliminar_roles = asignarrolesModel::where('usuario_id', $usuario_id)->delete();
            }

            // respuesta
            $dato["msj"] = 'Usuario eliminado correctamente';
            return response()->json($dato);
        }
        catch(Exception $e)
        {
            $dato["msj"] = 'Error '.$e->getMessage();
            return response()->json($dato);
        }
    }
}
