@extends('layouts.app')
@section('title', $employee->full_name)
@section('page_title', 'Fiche employe')
@section('page_subtitle', 'Lecture claire des informations personnelles et professionnelles')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $employee->full_name }}</h2>
        <p>La fiche employe est maintenant presentee comme un vrai dossier lisible, avec une meilleure hierarchie visuelle et une sensation plus premium.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ $employee->position }}</span>
            <span class="hero-pill">{{ $employee->department }}</span>
            <span class="hero-pill">{{ ucfirst($employee->status) }}</span>
        </div>
    </section>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        @can('employees.edit')
            <a href="{{ route('employees.edit', $employee) }}" class="btn-primary">Modifier</a>
        @endcan
        <a href="{{ route('employees.index') }}" class="btn-secondary">Retour liste</a>
    </div>

    <div class="detail-grid" style="grid-template-columns:1fr 1fr">
        <section class="detail-card">
            <h3>Informations personnelles</h3>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                <div style="width:56px;height:56px;border-radius:999px;background:#e7f0ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-weight:800">
                    {{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:800">{{ $employee->full_name }}</div>
                    <div style="color:#64748b;font-size:13px">{{ $employee->phone }}</div>
                </div>
            </div>
            <div style="display:grid;gap:10px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Email</span><span>{{ $employee->email }}</span></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Telephone</span><span>{{ $employee->phone }}</span></div>
                @if($employee->id_number)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Piece</span><span>{{ $employee->id_number }}</span></div>@endif
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Statut</span><span class="badge {{ $employee->status_badge }}">{{ ucfirst($employee->status) }}</span></div>
            </div>
        </section>

        <section class="detail-card">
            <h3>Informations professionnelles</h3>
            <div style="display:grid;gap:10px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Departement</span><strong>{{ $employee->department }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Contrat</span><span>{{ $employee->contract_type }}</span></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Date d embauche</span><span>{{ $employee->hire_date->format('d/m/Y') }}</span></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Anciennete</span><span>{{ $employee->years_of_service }} an(s)</span></div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid #eceef2;padding-top:10px"><span style="color:#64748b">Salaire mensuel</span><strong style="color:#16a34a">{{ number_format($employee->salary,0,',',' ') }} FCFA</strong></div>
            </div>
        </section>
    </div>
</div>
@endsection
