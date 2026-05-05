@extends('layouts.app')
@section('title', 'Nouvel employe')
@section('page_title', 'Nouvel employe')
@section('page_subtitle', 'Creation d un profil personnel plus agreable a renseigner')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Ajouter un membre de l equipe</h2>
        <p>Cette page adopte maintenant une presentation plus vivante et plus guidee pour la saisie RH, sans donner l impression d un simple formulaire brut.</p>
        <div class="hero-pills">
            <span class="hero-pill">Identite</span>
            <span class="hero-pill">Poste</span>
            <span class="hero-pill">Contrat</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:980px">
        <form action="{{ route('employees.store') }}" method="POST" class="section-stack">
            @csrf
            <div class="detail-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Prenom</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="form-input">
                    @error('first_name')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nom</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="form-input">
                    @error('last_name')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                    @error('email')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Poste</label>
                    <input type="text" name="position" value="{{ old('position') }}" required class="form-input" placeholder="Receptionniste, chef, agent...">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Departement</label>
                    <select name="department" required class="form-input">
                        <option value="">Selectionnez...</option>
                        <option value="Réception" @selected(old('department')==='Réception')>Reception</option>
                        <option value="Housekeeping" @selected(old('department')==='Housekeeping')>Housekeeping</option>
                        <option value="Restauration" @selected(old('department')==='Restauration')>Restauration</option>
                        <option value="Maintenance" @selected(old('department')==='Maintenance')>Maintenance</option>
                        <option value="Sécurité" @selected(old('department')==='Sécurité')>Securite</option>
                        <option value="Administration" @selected(old('department')==='Administration')>Administration</option>
                        <option value="Direction" @selected(old('department')==='Direction')>Direction</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Salaire mensuel</label>
                    <input type="number" name="salary" value="{{ old('salary') }}" required min="0" class="form-input">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Contrat</label>
                    <select name="contract_type" required class="form-input">
                        <option value="CDI" @selected(old('contract_type','CDI')==='CDI')>CDI</option>
                        <option value="CDD" @selected(old('contract_type')==='CDD')>CDD</option>
                        <option value="Stage" @selected(old('contract_type')==='Stage')>Stage</option>
                        <option value="Freelance" @selected(old('contract_type')==='Freelance')>Freelance</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Date d embauche</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required class="form-input">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Numero de piece</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Ajouter l employe</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
