@extends('layouts.app')
@section('title', 'Modifier ' . $employee->full_name)
@section('page_title', 'Modifier employe')
@section('page_subtitle', 'Mise a jour plus lisible du dossier personnel')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $employee->full_name }}</h2>
        <p>La page d edition a ete harmonisee avec le reste du projet pour offrir une experience plus fluide lors des modifications RH.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ $employee->department }}</span>
            <span class="hero-pill">{{ $employee->position }}</span>
            <span class="hero-pill">{{ ucfirst($employee->status) }}</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:980px">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="section-stack">
            @csrf @method('PUT')
            <div class="detail-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Prenom</label><input type="text" name="first_name" value="{{ old('first_name',$employee->first_name) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nom</label><input type="text" name="last_name" value="{{ old('last_name',$employee->last_name) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Email</label><input type="email" name="email" value="{{ old('email',$employee->email) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone</label><input type="text" name="phone" value="{{ old('phone',$employee->phone) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Poste</label><input type="text" name="position" value="{{ old('position',$employee->position) }}" required class="form-input"></div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Departement</label>
                    <select name="department" required class="form-input">
                        @foreach(['Réception','Housekeeping','Restauration','Maintenance','Sécurité','Administration','Direction'] as $dept)
                            <option value="{{ $dept }}" @selected(old('department',$employee->department)===$dept)>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Salaire</label><input type="number" name="salary" value="{{ old('salary',$employee->salary) }}" required min="0" class="form-input"></div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Statut</label>
                    <select name="status" required class="form-input">
                        <option value="active" @selected(old('status',$employee->status)==='active')>Actif</option>
                        <option value="on_leave" @selected(old('status',$employee->status)==='on_leave')>En conge</option>
                        <option value="inactive" @selected(old('status',$employee->status)==='inactive')>Inactif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <a href="{{ route('employees.show', $employee) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
