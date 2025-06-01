<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendshipController
{
    public function index()
    {
        $table_name = 'friendships';
        $table_data = DB::table($table_name)->paginate(10);
        return view($table_name . '.index', compact('table_name', 'table_data'));
    }

    public function create()
    {
        $usuarios = DB::table('users')->get();
        return view('friendships.create', compact('usuarios'));
    }

    public function store()
    {
        try {
            $table_name = 'friendships';
            $data = request()->validate([
                'usuario_id' => 'required|integer|exists:users,id',
                'amigo_id' => 'required|integer|exists:users,id',
                'accepted' => 'boolean|nullable',
            ]);

            $data['accepted'] = isset($data['accepted']) ? 1 : 0;
            $data['updated_at'] = now();
            $data['created_at'] = now();

            //dd($data);

            DB::table($table_name)->insert($data);
            return redirect()->route('friendships.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la amistad: ' . $e->getMessage());
        }
    }

    public function edit($usuario_id, $amigo_id)
    {

        $friendship = Friendship::where('usuario_id', $usuario_id)
            ->where('amigo_id', $amigo_id)
            ->firstOrFail();

        $usuarios = User::all();


        return view('friendships.edit', compact('friendship', 'usuarios'));
    }

    public function update($usuario_id, $amigo_id)
    {
        try {
            // Validar y actualizar los datos
            $data = request()->validate([
                'accepted' => 'nullable|boolean',
            ]);

            // Encuentra la amistad por sus claves primarias
            $updated = DB::table('friendships')
                ->where('usuario_id', '=', $usuario_id)
                ->where('amigo_id', '=', $amigo_id)
                ->update([
                    'accepted' => isset($data['accepted']) ? 1 : 0,
                    'updated_at' => now(),
                ]);

            // Verificar si se actualizó
            if ($updated === 0) {
                return redirect()->back()->with('error', 'La amistad no fue actualizada.');
            }

            return redirect()->route('friendships.index')->with('success', 'Amistad actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la amistad: ' . $e->getMessage());
        }
    }


    public function destroy($usuario_id, $amigo_id)
    {
        try {
            // Eliminar la amistad de la base de datos
            $deleted = DB::table('friendships')
                ->where('usuario_id', '=', $usuario_id)
                ->where('amigo_id', '=', $amigo_id)
                ->delete();

            // Verificar si la eliminación fue exitosa
            if ($deleted === 0) {
                return redirect()->back()->with('error', 'La amistad no fue eliminada.');
            }

            return redirect()->route('friendships.index')->with('success', 'Amistad eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la amistad: ' . $e->getMessage());
        }
    }


    //-----------Funciones para la API ------------------------//
    public function allFriendships()
    {
        $friendships = DB::table('friendships')->get();
        return response()->json($friendships, 200);
    }

    public function allFriendshipsOfUser()
    {
        $usuario_id = auth()->id();

        if (!$usuario_id) {
            return response()->json(['error' => 'El ID de usuario es requerido.'], 400);
        }

        try {
            $friendships = DB::table('friendships')
                ->where('usuario_id', $usuario_id)
                ->orWhere('amigo_id', $usuario_id)
                ->get();

            return response()->json($friendships, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las amistades: ' . $e->getMessage()], 500);
        }
    }

    public function allFriendshipsByUser($usuario_id)
    {
        try {
            $friendships = DB::table('friendships')
                ->where('usuario_id', $usuario_id)
                ->get();

            return response()->json($friendships, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las amistades: ' . $e->getMessage()], 500);
        }
    }

    public function viewFriendship($usuario_id, $amigo_id)
    {
        $friendship = DB::table('friendships')
            ->where('usuario_id', $usuario_id)
            ->where('amigo_id', $amigo_id)
            ->first();

        if (!$friendship) {
            return response()->json(['error' => 'Amistad no encontrada.'], 404);
        }

        return response()->json($friendship, 200);;
    }

    public function createFriendship()
    { {
            try {
                $user_id = auth()->id();
                if (!$user_id) {
                    return response()->json(['error' => 'No autenticado'], 401);
                }

                $data = request()->validate([
                    'amigo_id' => 'required|integer|exists:users,id',
                    
                ]);
                
                $data['usuario_id'] = $user_id; 
                $data['accepted'] = 0;
                $data['updated_at'] = now();
                $data['created_at'] = now();

                //dd($data);

                DB::table('friendships')->insert($data);
                return response()->json(['message' => 'Amistad creada exitosamente.'], 201);;
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al crear la amistad: ' . $e->getMessage()], 500);
            }
        }
    }

    public function editFriendship($usuario_id, $amigo_id)
    {
        try {
            $data = request()->validate([
                'accepted' => 'nullable|boolean',
            ]);

            $friendship = DB::table('friendships')
                ->where('usuario_id', $usuario_id)
                ->where('amigo_id', $amigo_id)
                ->first();

            if (!$friendship) {
                return response()->json(['error' => 'Amistad no encontrada.'], 404);
            }

            $data['accepted'] = isset($data['accepted']) ? 1 : 0;

            DB::table('friendships')
                ->where('usuario_id', $usuario_id)
                ->where('amigo_id', $amigo_id)
                ->update($data);

            return response()->json(['success' => 'Amistad actualizada exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la amistad: ' . $e->getMessage()], 500);
        }
    }

    public function deleteFriendship()
    {
        $user_id = auth()->id();
        if (!$user_id) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $amigo_id = request()->validate([
            'amigo_id' => 'required|integer|exists:users,id',
        ])['amigo_id'];

        $deleted = DB::table('friendships')
            ->where(function ($query) use ($user_id, $amigo_id) {
                $query->where('usuario_id', $user_id)->where('amigo_id', $amigo_id);
            })
            ->orWhere(function ($query) use ($user_id, $amigo_id) {
                $query->where('usuario_id', $amigo_id)->where('amigo_id', $user_id);
            })
            ->delete();

        if ($deleted) {
            return response()->json(['success' => 'Amistad eliminada exitosamente.'], 200);
        } else {
            return response()->json(['error' => 'Error al eliminar la amistad.'], 500);
        }
    }

    public function acceptFriendship()
    {
        try {
            $usuario_id = auth()->id();
            if (!$usuario_id) {
                return response()->json(['error' => 'No autenticado'], 401);
            }
            $amigo_id = request()->validate([
                'amigo_id' => 'required|integer|exists:users,id',
            ])['amigo_id'];

            $updated = DB::table('friendships')
                ->where('usuario_id', $amigo_id)
                ->where('amigo_id', $usuario_id)
                ->where('accepted', 0)
                ->update([
                    'accepted' => 1,
                ]);

            if ($updated) {
                return response()->json(['success' => 'Amistad aceptada exitosamente.'], 200);
            } else {
                return response()->json(['error' => 'No se encontró la amistad pendiente para aceptar.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al aceptar la amistad: ' . $e->getMessage()], 500);
        }
    }
    public function declineFriendship()
    {
        try {
            $usuario_id = auth()->id();
            if (!$usuario_id) {
                return response()->json(['error' => 'No autenticado'], 401);
            }
            $amigo_id = request()->validate([
                'amigo_id' => 'required|integer|exists:users,id',
            ])['amigo_id'];

            $deleted = DB::table('friendships')
                ->where('usuario_id', $amigo_id)
                ->where('amigo_id', $usuario_id)
                ->where('accepted', 0)
                ->delete();

            if ($deleted) {
                return response()->json(['success' => 'Amistad rechazada exitosamente.'], 200);
            } else {
                return response()->json(['error' => 'No se encontró la amistad pendiente para rechazar.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al rechazar la amistad: ' . $e->getMessage()], 500);
        }
    }
}
