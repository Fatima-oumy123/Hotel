@extends('layouts.app')
@section('title', 'Modifier depense')
@section('page_title', 'Modifier depense')
@section('page_subtitle', 'Mise a jour plus confortable du dossier comptable')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $expense->title }}</h2>
        <p>Cette page d edition reprend maintenant la meme structure premium que les autres formulaires de l application pour une experience moins rigide.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ ucfirst($expense->category) }}</span>
            <span class="hero-pill">{{ $expense->expense_date->format('d/m/Y') }}</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:900px">
        <form action="{{ route('expenses.update', $expense) }}" method="POST" class="section-stack">
            @csrf @method('PUT')
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Titre</label>
                <input type="text" name="title" value="{{ old('title', $expense->title) }}" required class="form-input">
                @error('title')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Montant</label><input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" required min="0" step="0.01" class="form-input"></div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Categorie</label>
                    <select name="category" required class="form-input">
                        @foreach(['maintenance','food','utilities','salary','supplies','marketing','insurance','taxes','equipment','other'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category',$expense->category)===$cat)>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Fournisseur</label><input type="text" name="supplier" value="{{ old('supplier', $expense->supplier) }}" class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Date</label><input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="form-input"></div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Description</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $expense->description) }}</textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <a href="{{ route('expenses.show', $expense) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
