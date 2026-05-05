@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('page_title', 'Utilisateurs')
@section('page_subtitle', 'Comptes, roles et activations')

@section('content')
<style>
    .users-page{display:grid;gap:18px}
    .user-filters{
        display:grid;
        grid-template-columns:1.2fr .8fr auto auto;
        gap:12px;
        align-items:end;
    }
    .user-filters label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .users-table th{background:#faf1ea}
    .users-table td{font-size:15px}
    @media (max-width:980px){
        .user-filters{grid-template-columns:1fr}
    }
</style>

<div class="users-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Utilisateurs</h2>
            <p>Administration des comptes internes, roles dynamiques et activations rapides.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('users.create') }}" class="btn-primary">Nouvel utilisateur</a>
        </div>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <form method="GET" class="user-filters">
                <div>
                    <label>Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="form-input">
                </div>
                <div>
                    <label>Role</label>
                    <select name="role" class="form-input">
                        <option value="">Tous</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role')===$role->name)>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><button type="submit" class="btn-primary">Filtrer</button></div>
                <div><a href="{{ route('users.index') }}" class="btn-secondary">Reinitialiser</a></div>
            </form>
        </div>
    </section>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Comptes internes</h3>
            <span class="link-accent">{{ $users->total() }} utilisateur(s)</span>
        </div>
        <div style="overflow:auto">
            <table class="table users-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Role</th>
                        <th>Telephone</th>
                        <th>Statut</th>
                        <th>Cree le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px">
                                    <div style="width:38px;height:38px;border-radius:999px;background:#f8e9d8;color:#9a5210;display:flex;align-items:center;justify-content:center;font-weight:800">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                    <div>
                                        <div style="font-weight:800">{{ $user->name }}</div>
                                        <div style="color:#64748b;font-size:12px">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>@foreach($user->roles as $role)<span class="badge badge-info">{{ ucfirst($role->name) }}</span>@endforeach</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td><span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">{{ $user->is_active ? 'Actif' : 'Inactif' }}</span></td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    <a href="{{ route('users.edit', $user) }}" class="btn-secondary">Modifier</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="btn-primary">{{ $user->is_active ? 'Desactiver' : 'Activer' }}</button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center">Aucun utilisateur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $users->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
