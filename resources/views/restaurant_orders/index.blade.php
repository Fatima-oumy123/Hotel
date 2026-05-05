@extends('layouts.app')
@section('title','Commandes Restaurant')
@section('page_title','Commandes <span>restaurant</span>')
@section('page_subtitle','Prise de commande, suivi et facturation chambre')

@section('content')
<div style="display:grid;gap:20px">
    <section class="panel">
        <div class="panel-head"><h3>Nouvelle commande</h3></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('restaurant-orders.store') }}" style="display:grid;gap:10px">
                @csrf
                <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px">
                    <select class="top-search" style="max-width:none;width:100%" name="reservation_id">
                        <option value="">Sans reservation</option>
                        @foreach($activeReservations as $reservation)
                        <option value="{{ $reservation->id }}">{{ $reservation->booking_number }} - {{ $reservation->guest_full_name }}</option>
                        @endforeach
                    </select>
                    <select class="top-search" style="max-width:none;width:100%" name="room_id">
                        <option value="">Aucune chambre</option>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}">Chambre {{ $room->number }}</option>
                        @endforeach
                    </select>
                    <input class="top-search" style="max-width:none;width:100%" name="customer_name" placeholder="Client externe (optionnel)">
                    <input class="top-search" style="max-width:none;width:100%" name="notes" placeholder="Note commande">
                </div>

                <div style="display:grid;grid-template-columns:2fr 1fr 2fr 1fr 2fr 1fr auto;gap:10px;align-items:center">
                    <select class="top-search" style="max-width:none;width:100%" name="items[0][menu_item_id]" required>
                        @foreach($menuItems as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input class="top-search" style="max-width:none;width:100%" type="number" name="items[0][quantity]" min="1" value="1" required>
                    <select class="top-search" style="max-width:none;width:100%" name="items[1][menu_item_id]">
                        <option value="">(optionnel)</option>
                        @foreach($menuItems as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input class="top-search" style="max-width:none;width:100%" type="number" name="items[1][quantity]" min="1" value="1">
                    <select class="top-search" style="max-width:none;width:100%" name="items[2][menu_item_id]">
                        <option value="">(optionnel)</option>
                        @foreach($menuItems as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input class="top-search" style="max-width:none;width:100%" type="number" name="items[2][quantity]" min="1" value="1">
                    <button class="btn-gold" type="submit">Commander</button>
                </div>
            </form>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head"><h3>Suivi des commandes</h3></div>
        <div class="panel-body" style="padding:0">
            <table class="table">
                <thead><tr><th>Commande</th><th>Client/Chambre</th><th>Montant</th><th>Statut</th><th>Paiement</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}<br><small>{{ $order->ordered_at?->format('d/m H:i') }}</small></td>
                        <td>{{ $order->customer_name ?: 'Client chambre' }} {{ $order->room ? ' / Ch.'.$order->room->number : '' }}</td>
                        <td>{{ number_format($order->total_amount, 0, ',', ' ') }} {{ config('hotel.currency') }}</td>
                        <td><span class="badge badge-info">{{ strtoupper($order->status) }}</span></td>
                        <td><span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ strtoupper($order->payment_status) }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('restaurant-orders.update-status', $order) }}" style="display:flex;gap:6px">
                                @csrf @method('PATCH')
                                <select class="top-search" style="max-width:none;width:140px" name="status">
                                    <option value="pending">En cours</option>
                                    <option value="preparing">Preparation</option>
                                    <option value="served">Servi</option>
                                    <option value="paid">Paye</option>
                                    <option value="cancelled">Annule</option>
                                </select>
                                <button class="btn-outline" type="submit">Mettre a jour</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center">Aucune commande</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px">{{ $orders->links() }}</div>
        </div>
    </section>
</div>
@endsection
