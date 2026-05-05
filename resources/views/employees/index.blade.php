@extends('layouts.app')
@section('title','Employes')
@section('page_title', 'Personnel')
@section('page_subtitle', 'Effectifs, departements et masse salariale')

@section('content')
<style>
    .employees-page{display:grid;gap:18px}
    .employee-filters{
        display:grid;
        grid-template-columns:1.4fr 1fr 1fr auto auto;
        gap:12px;
        align-items:end;
    }
    .employee-filters label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .employees-table th{background:#faf1ea}
    .employees-table td{font-size:15px}
    .avatar-chip{
        width:34px;
        height:34px;
        border-radius:999px;
        background:#edf2ff;
        color:#334155;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:12px;
        font-weight:800;
    }
    @media (max-width:1180px){
        .employee-filters{grid-template-columns:1fr}
    }
</style>

<div class="employees-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion du Personnel</h2>
            <p>Masse salariale: <strong>{{ number_format($stats['mass_salariale'],0,',',' ') }} FCFA/mois</strong></p>
        </div>
        <div class="screen-actions">
            @can('employees.create')
                <a href="{{ route('employees.create') }}" class="btn-primary">Nouvel employe</a>
            @endcan
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Actifs</div>
            <div class="metric-figure" style="color:#16a34a">{{ $stats['total'] }}</div>
            <div class="metric-caption">Personnel en poste</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">En conge</div>
            <div class="metric-figure" style="color:#a55a00">{{ $stats['on_leave'] }}</div>
            <div class="metric-caption">Absences planifiees</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Inactifs</div>
            <div class="metric-figure" style="color:#dc2626">{{ $stats['inactive'] }}</div>
            <div class="metric-caption danger">A regulariser</div>
        </article>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <form method="GET" class="employee-filters">
                <div>
                    <label>Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..." class="form-input">
                </div>
                <div>
                    <label>Departement</label>
                    <select name="department" class="form-input">
                        <option value="">Tous</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" @selected(request('department')===$dept)>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous</option>
                        <option value="active" @selected(request('status')==='active')>Actif</option>
                        <option value="on_leave" @selected(request('status')==='on_leave')>En conge</option>
                        <option value="inactive" @selected(request('status')==='inactive')>Inactif</option>
                    </select>
                </div>
                <div><button type="submit" class="btn-primary">Filtrer</button></div>
                <div><a href="{{ route('employees.index') }}" class="btn-secondary">Reinitialiser</a></div>
            </form>
        </div>
    </section>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Liste des Employes</h3>
            <span class="link-accent">{{ $employees->total() }} employe(s)</span>
        </div>
        <div style="overflow:auto">
            <table class="table employees-table">
                <thead>
                    <tr>
                        <th>Employe</th>
                        <th>Poste</th>
                        <th>Departement</th>
                        <th>Salaire</th>
                        <th>Contrat</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px">
                                    <div class="avatar-chip">{{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}</div>
                                    <div>
                                        <div style="font-weight:700">{{ $employee->full_name }}</div>
                                        <div style="font-size:12px;color:#8a8179">{{ $employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td><strong>{{ number_format($employee->salary,0,',',' ') }} F</strong></td>
                            <td>{{ $employee->contract_type }}</td>
                            <td><span class="badge {{ $employee->status_badge }}">{{ ucfirst($employee->status) }}</span></td>
                            <td>
                                <div style="display:flex;gap:10px;flex-wrap:wrap">
                                    <a href="{{ route('employees.show', $employee) }}" class="link-accent">Voir</a>
                                    @can('employees.edit')
                                        <a href="{{ route('employees.edit', $employee) }}" class="link-accent" style="color:#5d5f63">Modifier</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center">Aucun employe trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $employees->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
