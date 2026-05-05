@extends('layouts.app')
@section('title', 'Nouvel utilisateur')
@section('page_title', 'Creer un utilisateur')
@section('page_subtitle', 'Attribution claire des acces et profils de travail')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Creer un nouveau compte d acces</h2>
        <p>Le formulaire utilisateur a ete affine pour mieux presenter les roles et aider l administrateur a faire des choix rapides sans se retrouver face a une page trop seche.</p>
        <div class="hero-pills">
            <span class="hero-pill">Administrateur</span>
            <span class="hero-pill">Reception</span>
            <span class="hero-pill">RH</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:940px">
        <form action="{{ route('users.store') }}" method="POST" class="section-stack">
            @csrf
            <div class="detail-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div style="grid-column:1/-1">
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Prenom Nom">
                    @error('name')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                    @error('email')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+221 77 000 00 00">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Mot de passe</label>
                    <input type="password" name="password" required class="form-input" placeholder="Min. 8 caracteres">
                    @error('password')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Confirmation</label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
            </div>

            <div>
                <label style="display:block;margin-bottom:10px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Role</label>
                <div class="detail-grid">
                    @foreach($roles as $role)
                        <label style="display:flex;gap:12px;align-items:flex-start;padding:18px;border:2px solid {{ old('role') === $role->name ? '#f1b57f' : '#e5e7eb' }};border-radius:18px;background:{{ old('role') === $role->name ? '#f8f3ed' : '#fff' }};cursor:pointer">
                            <input type="radio" name="role" value="{{ $role->name }}" style="margin-top:4px" @checked(old('role') === $role->name)>
                            <div>
                                <div style="font-weight:800">{{ ucfirst($role->name) }}</div>
                                <div style="margin-top:6px;color:#64748b;font-size:13px">
                                    {{ match($role->name) {
                                        'manager'      => 'Acces total au systeme et a la configuration.',
                                        'receptionist' => 'Gestion du front office et des reservations.',
                                        'hr'           => 'Suivi RH et planning du personnel.',
                                        default        => 'Acces limite selon votre parametrage.',
                                    } }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('role')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Creer l utilisateur</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
