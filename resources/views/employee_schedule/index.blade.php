@extends('layouts.app')
@section('title', 'Personnel')
@section('page_title', 'Gestion du personnel')
@section('page_subtitle', 'Planning, presence, taches et urgences operationnelles')

@section('content')
<style>
    .staff-page{display:grid;gap:16px}
    .metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
    .metric,.card{background:#fff;border:1px solid #d8dde6;border-radius:12px}
    .metric{padding:20px}
    .metric .label{font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#6b7280;font-weight:800}
    .metric .value{margin-top:10px;font-family:'Outfit',sans-serif;font-size:42px;line-height:1}
    .metric .meta{margin-top:8px;font-size:13px;color:#64748b}
    .layout{display:grid;grid-template-columns:2fr 1fr;gap:16px}
    .head{padding:18px 20px;border-bottom:1px solid #eceef2;display:flex;justify-content:space-between;gap:12px;align-items:center}
    .head h3{margin:0;font-family:'Outfit',sans-serif;font-size:28px}
    .inline-form{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px;padding:16px;background:#fafbfc;border-bottom:1px solid #eceef2}
    .inline-form input,.inline-form select{
        width:100%;padding:12px 14px;border:1px solid #d5dbe5;border-radius:10px;background:#fff;
    }
    .alert-panel{
        background:linear-gradient(180deg,#23160f,#2d180e);color:#fff;border:1px solid #553622;border-radius:14px;padding:18px 20px;
    }
    .load-list{display:grid;gap:12px}
    .load-item .bar{height:8px;background:#e5e7eb;border-radius:999px;margin-top:6px;overflow:hidden}
    .load-item .bar span{display:block;height:100%;background:#f55f0a}
    @media (max-width:1180px){
        .metrics{grid-template-columns:repeat(2,minmax(0,1fr))}
        .layout{grid-template-columns:1fr}
        .inline-form{grid-template-columns:repeat(3,minmax(0,1fr))}
    }
    @media (max-width:820px){
        .metrics,.inline-form{grid-template-columns:1fr}
    }
</style>

@php
    $present = $shifts->where('attendance_status', 'present')->count();
    $tasksOpen = $tasks->whereIn('status', ['pending', 'in_progress'])->count();
@endphp

<div class="staff-page">
    <div class="metrics">
        <article class="metric">
            <div class="label">Effectif total</div>
            <div class="value">{{ $employees->count() }}</div>
            <div class="meta">Personnel enregistre dans le systeme</div>
        </article>
        <article class="metric">
            <div class="label">En service</div>
            <div class="value">{{ $present }}</div>
            <div class="meta">Agents marques presents aujourd hui</div>
        </article>
        <article class="metric">
            <div class="label">Taches actives</div>
            <div class="value">{{ $tasksOpen }}</div>
            <div class="meta">Actions en cours ou a lancer</div>
        </article>
        <article class="metric">
            <div class="label">Alertes</div>
            <div class="value">2</div>
            <div class="meta">Priorites operationnelles du jour</div>
        </article>
    </div>

    <div class="layout">
        <section class="card">
            <div class="head">
                <h3>Planning des presences</h3>
                <span class="badge badge-info">Suivi journalier</span>
            </div>

            <form method="POST" action="{{ route('employee-schedule.shifts.store') }}" class="inline-form">
                @csrf
                <select name="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
                <input type="date" name="shift_date" value="{{ now()->toDateString() }}" required>
                <input type="time" name="start_time">
                <input type="time" name="end_time">
                <select name="attendance_status" required>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="rest">Repos</option>
                </select>
                <button class="btn-primary" type="submit">Ajouter</button>
            </form>

            <div style="overflow:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employe</th>
                            <th>Service</th>
                            <th>Presence</th>
                            <th>Progression</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td>
                                    <strong>{{ $shift->employee?->full_name }}</strong>
                                    <br>
                                    <small style="color:#64748b">Shift {{ $shift->start_time ?: '--' }} - {{ $shift->end_time ?: '--' }}</small>
                                </td>
                                <td><span class="badge badge-warning">{{ $shift->employee?->department ?: 'N/A' }}</span></td>
                                <td><span class="badge {{ $shift->attendance_status === 'present' ? 'badge-success' : ($shift->attendance_status === 'rest' ? 'badge-secondary' : 'badge-danger') }}">{{ ucfirst($shift->attendance_status) }}</span></td>
                                <td>
                                    <div style="height:8px;background:#e5e7eb;border-radius:999px;overflow:hidden">
                                        <span style="display:block;height:100%;width:{{ $shift->attendance_status === 'present' ? 80 : 20 }}%;background:#f55f0a"></span>
                                    </div>
                                </td>
                                <td>⋮</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center">Aucun horaire disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="head">
                <h3>Attribution des taches</h3>
            </div>
            <form method="POST" action="{{ route('employee-schedule.tasks.store') }}" class="inline-form" style="border-bottom:none">
                @csrf
                <select name="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
                <input name="title" placeholder="Titre de la tache" required>
                <input type="date" name="due_date">
                <select name="status" required>
                    <option value="pending">En attente</option>
                    <option value="in_progress">En cours</option>
                    <option value="done">Terminee</option>
                </select>
                <input name="description" placeholder="Description">
                <button class="btn-primary" type="submit">Assigner</button>
            </form>

            <div style="overflow:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Employe</th>
                            <th>Tache</th>
                            <th>Echeance</th>
                            <th>Statut</th>
                            <th>Mise a jour</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $task->employee?->full_name }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->due_date?->format('d/m/Y') ?: '-' }}</td>
                                <td><span class="badge badge-info">{{ strtoupper($task->status) }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('employee-schedule.tasks.update', $task) }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" style="padding:10px;border:1px solid #d5dbe5;border-radius:10px;background:#fff">
                                            <option value="pending">En attente</option>
                                            <option value="in_progress">En cours</option>
                                            <option value="done">Terminee</option>
                                        </select>
                                        <button class="btn-secondary" type="submit">Enregistrer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center">Aucune tache disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div style="display:grid;gap:16px">
            <section class="alert-panel">
                <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
                    <strong style="font-size:28px">Actions urgentes</strong>
                    <span class="badge badge-danger">Priorite</span>
                </div>
                <div style="margin-top:14px;display:grid;gap:12px">
                    <div style="border-left:3px solid #ef4444;padding-left:10px">
                        <strong>Fuite signalee chambre 304</strong>
                        <div style="color:#d2b8a5">Signalement recu depuis la reception.</div>
                    </div>
                    <div style="border-left:3px solid #f97316;padding-left:10px">
                        <strong>Arrivee client VIP</strong>
                        <div style="color:#d2b8a5">Preparation prioritaire de l accueil.</div>
                    </div>
                </div>
                <button class="btn-secondary" type="button" style="margin-top:14px;width:100%">Assigner une intervention</button>
            </section>

            <section class="card">
                <div class="head">
                    <h3>Charge par service</h3>
                </div>
                <div style="padding:18px 20px" class="load-list">
                    @foreach([['Reception', 92], ['Housekeeping', 64], ['Cuisine & bar', 78], ['Maintenance', 35]] as $item)
                        <div class="load-item">
                            <div style="display:flex;justify-content:space-between"><span>{{ $item[0] }}</span><strong>{{ $item[1] }}%</strong></div>
                            <div class="bar"><span style="width:{{ $item[1] }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
