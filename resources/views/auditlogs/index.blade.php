@extends('layouts.app')
@section('title','Journal d\'audit')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Journal d'audit</h1>
            <p class="text-sm text-gray-500">Traçabilité complète des actions</p>
        </div>
    </div>

    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Utilisateur</label>
                <select name="user_id" class="form-input text-sm">
                    <option value="">Tous</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(request('user_id')==$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Action</label>
                <input type="text" name="action" value="{{ request('action') }}" placeholder="login, POST..." class="form-input text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input text-sm">
            </div>
            <button type="submit" class="btn-primary">Filtrer</button>
            <a href="{{ route('audit-logs.index') }}" class="btn-secondary">Réinitialiser</a>
        </form>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-th">Icône</th>
                    <th class="table-th">Utilisateur</th>
                    <th class="table-th">Action</th>
                    <th class="table-th">IP</th>
                    <th class="table-th">Date & Heure</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="table-td text-center text-lg">{{ $log->action_icon }}</td>
                    <td class="table-td">
                        @if($log->user)
                        <p class="font-medium text-sm">{{ $log->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $log->user->getRoleNames()->first() }}</p>
                        @else
                        <span class="text-gray-400 text-sm">Système</span>
                        @endif
                    </td>
                    <td class="table-td">
                        <code class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $log->action }}</code>
                    </td>
                    <td class="table-td text-xs text-gray-500 font-mono">{{ $log->ip_address ?? '—' }}</td>
                    <td class="table-td text-sm text-gray-500">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                        <span class="text-xs text-gray-400 block">{{ $log->created_at->diffForHumans() }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Aucune entrée dans le journal</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $logs->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
