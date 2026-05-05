@extends('layouts.app')
@section('title', 'Nouvelle reservation')
@section('page_title', 'Nouvelle reservation')
@section('page_subtitle', 'Creation assistee d un sejour avec estimation immediate')

@section('content')
<div class="page-shell" x-data="reservationForm()">
    <section class="page-hero">
        <h2>Composer une reservation sans friction</h2>
        <p>Cette page a ete retravaillee pour mieux guider la reception, avec une lecture par etapes, des cartes plus vivantes et un calcul plus lisible du cout du sejour.</p>
        <div class="hero-pills">
            <span class="hero-pill">Chambre + dates</span>
            <span class="hero-pill">Fiche client</span>
            <span class="hero-pill">Calcul TTC instantane</span>
        </div>
    </section>

    <form action="{{ route('reservations.store') }}" method="POST" class="section-stack">
        @csrf

        <section class="card" style="padding:22px">
            <h3 style="margin:0 0 16px;font-family:'Outfit',sans-serif;font-size:28px">1. Chambre et calendrier</h3>
            <div class="detail-grid" style="grid-template-columns:1.4fr 1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Chambre</label>
                    <select name="room_id" required class="form-input" x-model="roomId" @change="calculatePrice()">
                        <option value="">Selectionnez une chambre...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->roomType->base_price }}" data-type="{{ $room->roomType->name }}" @selected(request('room_id') == $room->id)>
                                Ch.{{ $room->number }} · {{ $room->roomType->name }} · {{ number_format($room->roomType->base_price,0,',',' ') }} FCFA/nuit
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Arrivee</label>
                    <input type="date" name="check_in" required x-model="checkIn" min="{{ date('Y-m-d') }}" @change="calculatePrice()" class="form-input">
                    @error('check_in')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Depart</label>
                    <input type="date" name="check_out" required x-model="checkOut" @change="calculatePrice()" class="form-input">
                    @error('check_out')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
            </div>

            <div x-show="nights > 0" x-transition style="margin-top:18px;padding:18px;border-radius:18px;background:#f8f3ed;border:1px solid #eddcc7">
                <div class="detail-grid" style="grid-template-columns:repeat(4,minmax(0,1fr))">
                    <div><div style="font-size:11px;color:#9a5210;text-transform:uppercase;font-weight:800">Nuits</div><div style="margin-top:8px;font-family:'Outfit',sans-serif;font-size:34px" x-text="nights"></div></div>
                    <div><div style="font-size:11px;color:#9a5210;text-transform:uppercase;font-weight:800">Prix / nuit</div><div style="margin-top:8px;font-family:'Outfit',sans-serif;font-size:34px" x-text="formatPrice(pricePerNight)"></div></div>
                    <div><div style="font-size:11px;color:#9a5210;text-transform:uppercase;font-weight:800">Sous total</div><div style="margin-top:8px;font-family:'Outfit',sans-serif;font-size:34px" x-text="formatPrice(subtotal)"></div></div>
                    <div><div style="font-size:11px;color:#9a5210;text-transform:uppercase;font-weight:800">Total TTC</div><div style="margin-top:8px;font-family:'Outfit',sans-serif;font-size:34px" x-text="formatPrice(total)"></div></div>
                </div>
            </div>
        </section>

        <section class="card" style="padding:22px">
            <h3 style="margin:0 0 16px;font-family:'Outfit',sans-serif;font-size:28px">2. Informations du client</h3>
            <div class="detail-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Prenom</label>
                    <input type="text" name="guest_first_name" value="{{ old('guest_first_name') }}" required class="form-input" placeholder="Prenom">
                    @error('guest_first_name')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Nom</label>
                    <input type="text" name="guest_last_name" value="{{ old('guest_last_name') }}" required class="form-input" placeholder="Nom">
                    @error('guest_last_name')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required class="form-input" placeholder="+221 77 000 00 00">
                    @error('guest_phone')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Email</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="form-input" placeholder="email@exemple.com">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Date de naissance</label>
                    <input type="date" name="guest_dob" value="{{ old('guest_dob') }}" class="form-input">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Piece d identite</label>
                    <input type="text" name="guest_id_number" value="{{ old('guest_id_number') }}" class="form-input" placeholder="CNI, passeport...">
                </div>
            </div>
        </section>

        <section class="card" style="padding:22px">
            <h3 style="margin:0 0 16px;font-family:'Outfit',sans-serif;font-size:28px">3. Occupation et demandes</h3>
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Adultes</label>
                    <select name="adults" class="form-input">
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" @selected(old('adults',1)==$i)>{{ $i }} adulte{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Enfants</label>
                    <select name="children" class="form-input">
                        @for($i=0; $i<=4; $i++)
                            <option value="{{ $i }}" @selected(old('children',0)==$i)>{{ $i }} enfant{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <div style="grid-column:1/-1">
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Demandes speciales</label>
                    <textarea name="special_requests" rows="4" class="form-input" placeholder="Chambre haute, lit bebe, regime alimentaire...">{{ old('special_requests') }}</textarea>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button type="submit" class="btn-primary">Creer la reservation</button>
            <a href="{{ route('reservations.index') }}" class="btn-secondary">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function reservationForm() {
    return {
        roomId: '{{ request('room_id') }}',
        checkIn: '',
        checkOut: '',
        nights: 0,
        pricePerNight: 0,
        subtotal: 0,
        total: 0,
        calculatePrice() {
            if (!this.checkIn || !this.checkOut || !this.roomId) return;
            const d1 = new Date(this.checkIn);
            const d2 = new Date(this.checkOut);
            this.nights = Math.max(0, Math.round((d2 - d1) / 86400000));
            const sel = document.querySelector(`select[name="room_id"] option[value="${this.roomId}"]`);
            this.pricePerNight = sel ? parseFloat(sel.dataset.price) : 0;
            this.subtotal = this.nights * this.pricePerNight;
            const tax = this.subtotal * 0.18;
            this.total = this.subtotal + tax;
        },
        formatPrice(v) {
            return Math.round(v).toLocaleString('fr') + ' F';
        }
    }
}
</script>
@endpush
@endsection
