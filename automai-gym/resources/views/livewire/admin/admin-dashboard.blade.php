<?php

use Livewire\Component;

new class extends Component {
    public $tab = 'usuarios';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    protected $listeners = ['tabChanged' => 'setTab'];
};
?>

<div class="surface">
    <nav class="tabs" style="justify-content: flex-start; gap: 20px;">
        <div class="tab-left">
            <button wire:click="setTab('usuarios')"
                class="tab {{ $tab === 'usuarios' ? 'active' : '' }}">Usuarios</button>
            <button wire:click="setTab('clases')" class="tab {{ $tab === 'clases' ? 'active' : '' }}">Clases</button>
            <button wire:click="setTab('rutinas')" class="tab {{ $tab === 'rutinas' ? 'active' : '' }}">Rutinas
                Master</button>
        </div>
    </nav>

    <div class="content">
        @if($tab === 'usuarios')
            <livewire:admin.user-management />
        @elseif($tab === 'clases')
            <livewire:admin.class-management />
        @elseif($tab === 'rutinas')
            <livewire:admin.routine-management />
        @endif
    </div>
</div>