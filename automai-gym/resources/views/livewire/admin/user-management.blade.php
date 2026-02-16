<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Rol;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $editingUser = null;

    // Form fields
    public $nombre = '';
    public $correo = '';
    public $id_rol = '';
    public $estado = 'activo';

    public function mount($search = '')
    {
        $this->search = $search;
    }

    public function createUser()
    {
        $this->resetForm();
        $this->editingUser = 'new';
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->correo = '';
        $this->id_rol = '';
        $this->estado = 'activo';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editUser($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->editingUser = $user->id_usuario;
        $this->nombre = $user->nombre_mostrado_usuario;
        $this->correo = $user->correo_usuario;
        $this->id_rol = $user->roles->first()?->id_rol ?? '';
        $this->estado = $user->estado_usuario;
    }

    public function saveUser()
    {
        if ($this->editingUser === 'new') {
            $user = User::create([
                'nombre_mostrado_usuario' => $this->nombre,
                'correo_usuario' => $this->correo,
                'estado_usuario' => $this->estado,
                'hash_contrasena_usuario' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password
            ]);
        } else {
            $user = User::findOrFail($this->editingUser);
            $user->update([
                'nombre_mostrado_usuario' => $this->nombre,
                'correo_usuario' => $this->correo,
                'estado_usuario' => $this->estado,
            ]);
        }

        if ($this->id_rol) {
            $user->roles()->sync([$this->id_rol]);
        }

        $this->editingUser = null;
        $this->dispatch('notify', 'Usuario guardado correctamente.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->estado_usuario = $user->estado_usuario === 'activo' ? 'bloqueado' : 'activo';
        $user->save();
    }

    public function with(): array
    {
        return [
            'users' => User::with('roles')
                ->where(function ($query) {
                    $query->where('nombre_mostrado_usuario', 'like', '%' . $this->search . '%')
                        ->orWhere('correo_usuario', 'like', '%' . $this->search . '%');
                })
                ->paginate(10),
            'allRoles' => Rol::all(),
        ];
    }
};
?>

<div class="grid2">
    <!-- Lista de Usuarios -->
    <div class="panel">
        <div class="panel-h"
            style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <strong>Gestión de Usuarios</strong>
                <div class="pill">{{ \App\Models\User::where('estado_usuario', 'activo')->count() }} activos</div>
            </div>

            <div style="display: flex; gap: 10px; flex-grow: 1; justify-content: flex-end;">
                <input type="text" class="search" placeholder="Buscar..."
                    style="width: 200px; height: 32px; font-size: 13px; padding: 0 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--cream-4); color: var(--cream);"
                    wire:model.live="search">

                <button class="mini-btn primary" wire:click="createUser" style="height: 32px; padding: 0 15px;">
                    + Nuevo
                </button>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="font-weight:800;color:var(--cream);">{{ $user->nombre_mostrado_usuario }}</div>
                                <div class="muted">{{ $user->correo_usuario }}</div>
                            </td>
                            <td>
                                <span class="pill" style="height:24px;font-size:9px;">
                                    {{ $user->roles->first()?->nombre_rol ?? 'Sin rol' }}
                                </span>
                            </td>
                            <td>
                                <div class="state cursor-pointer" wire:click="toggleStatus({{ $user->id_usuario }})">
                                    <span class="dot {{ $user->estado_usuario === 'activo' ? 'green' : 'red' }}"></span>
                                    {{ ucfirst($user->estado_usuario) }}
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="mini-btn warn"
                                        wire:click="editUser({{ $user->id_usuario }})">Edit</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 15px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Formulario/Detalle rápido -->
    @if($editingUser)
        <aside class="panel">
            <div class="panel-h"><strong>{{ $editingUser === 'new' ? 'Nuevo Usuario' : 'Detalle de Usuario' }}</strong>
            </div>
            <form class="form" wire:submit.prevent="saveUser">
                <div class="field">
                    <label>Nombre Completo</label>
                    <input type="text" class="input" wire:model="nombre">
                </div>
                <div class="field">
                    <label>Correo Electrónico</label>
                    <input type="email" class="input" wire:model="correo">
                </div>
                <div class="row">
                    <div class="field">
                        <label>Rol</label>
                        <select wire:model="id_rol">
                            <option value="">Seleccionar rol</option>
                            @foreach($allRoles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ ucfirst($rol->nombre_rol) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Estado</label>
                        <select wire:model="estado" class="input">
                            <option value="activo">Activo</option>
                            <option value="bloqueado">Bloqueado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row" style="margin-top:10px;">
                    <button type="button" class="mini-btn primary" wire:click="$set('editingUser', null)">Cancelar</button>
                    <button type="submit" class="cta" style="height:40px;justify-content:center;">Guardar cambios</button>
                </div>
            </form>
        </aside>
    @else
        <div class="panel"
            style="display: flex; align-items: center; justify-content: center; padding: 40px; text-align: center; color: var(--cream-3);">
            <div>
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="margin-bottom: 10px; opacity: 0.5;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <p>Selecciona un usuario para ver detalles o editar.</p>
            </div>
        </div>
    @endif
</div>