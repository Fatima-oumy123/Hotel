@extends('layouts.app')
@section('title', 'Nouvelle depense')
@section('page_title', 'Nouvelle depense')
@section('page_subtitle', 'Saisie comptable plus claire pour les charges de l hotel')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Declarer une depense</h2>
        <p>La creation de depense suit maintenant une mise en scene plus douce, avec un meilleur confort de lecture pour la comptabilite et la direction.</p>
        <div class="hero-pills">
            <span class="hero-pill">Montant</span>
            <span class="hero-pill">Categorie</span>
            <span class="hero-pill">Fournisseur</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:900px">
        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="section-stack">
            @csrf
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Titre</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="form-input">
                @error('title')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Montant</label><input type="number" name="amount" value="{{ old('amount') }}" required min="0" step="0.01" class="form-input"></div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Categorie</label>
                    <select name="category" required class="form-input">
                        @foreach(['maintenance','food','utilities','salary','supplies','marketing','insurance','taxes','equipment','other'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category')===$cat)>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Fournisseur</label><input type="text" name="supplier" value="{{ old('supplier') }}" class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Date</label><input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="form-input"></div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Description</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Justificatif</label>
                <input type="file" name="receipt" class="form-input">
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Enregistrer la depense</button>
                <a href="{{ route('expenses.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
