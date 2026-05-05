@extends('layouts.app')
@section('title', 'Modifier ' . $user->name)
@section('page_title', 'Modifier utilisateur')
@section('page_subtitle', 'Edition plus visuelle du compte et de ses droits')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $user->name }}</h2>
        <p>La page d edition utilisateur a ete alignee sur la meme experience premium que les autres ecrans critiques du back-office.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ $user->getRoleNames()->first() ?? 'Utilisateur' }}</span>
            <span class="hero-pill">{{ $user->is_active ? 'Actif' : 'Inactif' }}</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:940px">
        <form action="{{ route('users.update', $user) }}" method="POST" class="section-stack">
            @csrf @method('PUT')
            <div class="detail-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div style="grid-column:1/-1"><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nom complet</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nouveau mot de passe</label><input type="password" name="password" class="form-input" placeholder="Laisser vide pour ne pas changer"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Confirmation</label><input type="password" name="password_confirmation" class="form-input"></div>
            </div>
            <div>
                <label style="display:block;margin-bottom:10px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Role</label>
                <div class="detail-grid">
                    @foreach($roles as $role)
                        @php $currentRole = old('role', $user->getRoleNames()->first()); @endphp
                        <label style="display:flex;gap:12px;align-items:flex-start;padding:18px;border:2px solid {{ $currentRole === $role->name ? '#f1b57f' : '#e5e7eb' }};border-radius:18px;background:{{ $currentRole === $role->name ? '#f8f3ed' : '#fff' }};cursor:pointer">
                            <input type="radio" name="role" value="{{ $role->name }}" style="margin-top:4px" @checked($currentRole === $role->name)>
                            <div>
                                <div style="font-weight:800">{{ ucfirst($role->name) }}</div>
                                <div style="margin-top:6px;color:#64748b;font-size:13px">
                                    {{ match($role->name) {
                                        'manager'      => 'Acces total',
                                        'receptionist' => 'Operations courantes',
                                        'hr'           => 'Ressources humaines',
                                        default        => 'Acces limite',
                                    } }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            <label style="display:flex;gap:10px;align-items:center;padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#fafafa;width:fit-content">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                <span>Compte actif</span>
            </label>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
