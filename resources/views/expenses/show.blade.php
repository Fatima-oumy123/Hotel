@extends('layouts.app')
@section('title', 'Depense - ' . $expense->title)
@section('page_title', 'Detail depense')
@section('page_subtitle', 'Lecture plus visuelle des charges et justificatifs')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $expense->title }}</h2>
        <p>La fiche depense a ete transformee en ecran de lecture plus confortable, avec une meilleure presence du montant, de la categorie et des pieces liees.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ ucfirst($expense->category) }}</span>
            <span class="hero-pill">{{ $expense->expense_date->format('d/m/Y') }}</span>
            <span class="hero-pill">{{ $expense->formatted_amount }}</span>
        </div>
    </section>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('expenses.edit', $expense) }}" class="btn-primary">Modifier</a>
        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Supprimer cette depense ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">Supprimer</button>
        </form>
        <a href="{{ route('expenses.index') }}" class="btn-secondary">Retour liste</a>
    </div>

    <div class="detail-grid" style="grid-template-columns:1.2fr .8fr">
        <section class="detail-card">
            <h3>Informations comptables</h3>
            <div style="font-family:'Outfit',sans-serif;font-size:42px;color:#dc2626">{{ $expense->formatted_amount }}</div>
            <div style="margin-top:16px;display:grid;gap:10px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Categorie</span><strong>{{ ucfirst($expense->category) }}</strong></div>
                @if($expense->supplier)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Fournisseur</span><span>{{ $expense->supplier }}</span></div>@endif
                @if($expense->approver)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Approuve par</span><span>{{ $expense->approver->name }}</span></div>@endif
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Date</span><span>{{ $expense->expense_date->format('d/m/Y') }}</span></div>
            </div>
            @if($expense->description)
                <div style="margin-top:18px;padding:16px;border-radius:16px;background:#f8fafc;border:1px solid #eceef2">
                    <strong>Description</strong>
                    <div style="margin-top:8px;color:#475569;font-size:14px;line-height:1.7">{{ $expense->description }}</div>
                </div>
            @endif
        </section>

        <section class="detail-card">
            <h3>Pieces associees</h3>
            @if($expense->receipt_path)
                <div style="padding:16px;border-radius:16px;background:#f8f3ed;border:1px solid #eddcc7">
                    <strong>Justificatif disponible</strong>
                    <div style="margin-top:8px;color:#64748b;font-size:13px">Le document de preuve peut etre consulte ou telecharge.</div>
                    <div style="margin-top:14px">
                        <a href="{{ asset('storage/'.$expense->receipt_path) }}" target="_blank" class="btn-secondary">Voir le justificatif</a>
                    </div>
                </div>
            @else
                <div style="color:#64748b">Aucun justificatif n a ete ajoute a cette depense.</div>
            @endif
        </section>
    </div>
</div>
@endsection
