@php
    $badgeStatus = [
        'baru' => 'bg-amber-100 text-amber-700',
        'ditindak' => 'bg-emerald-100 text-emerald-700',
        'ditolak' => 'bg-slate-200 text-slate-600',
    ];
@endphp

@extends('layouts.app')

@section('title', 'Antrean Laporan Forum')

@section('content')
<div class="py-4 max-w-3xl mx-auto">
    <h1 class="text-2xl font-extrabold text-slate-900">Antrean Laporan Forum 🛡️</h1>
    <p class="mt-1 text-sm text-slate-600">Tinjau laporan dari komunitas dan tentukan tindak lanjutnya dengan bijak.</p>

    @if (session('status'))
        <div class="mt-4 rounded-xl border border-tenang-200 bg-tenang-50 px-4 py-3 text-sm text-tenang-800" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($reports->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-10 text-center text-sm text-slate-500">
            <p class="text-3xl" aria-hidden="true">🌿</p>
            <p class="mt-3 font-medium text-slate-700">Antrean bersih!</p>
            <p class="mt-1">Tidak ada laporan yang menunggu ditinjau. Forum aman dan hangat 🌱</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($reports as $report)
                <article class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        @if ($report->thread)
                            <a href="{{ route('forum.show', $report->thread->id) }}"
                               class="font-bold text-slate-900 hover:text-tenang-700 transition">
                                {{ $report->thread->title }}
                            </a>
                        @else
                            <span class="font-bold text-slate-400">(Thread sudah dihapus)</span>
                        @endif
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeStatus[$report->status] ?? $badgeStatus['baru'] }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </div>

                    <dl class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm">
                        <div>
                            <dt class="text-xs text-slate-400">Pelapor</dt>
                            <dd class="font-medium text-slate-700">{{ $report->reporter_name ?: 'Anonim' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Alasan</dt>
                            <dd class="font-medium text-slate-700">{{ $report->reason }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">Dilaporkan</dt>
                            <dd class="font-medium text-slate-700">{{ $report->created_at?->diffForHumans() }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('admin.reports.resolve', $report->id) }}"
                          class="mt-4 flex flex-wrap items-center gap-3 border-t border-tenang-100 pt-4">
                        @csrf
                        <label for="status-{{ $report->id }}" class="text-sm font-medium text-slate-700">Tindak lanjut</label>
                        <select id="status-{{ $report->id }}" name="status" required
                                class="rounded-xl border-slate-300 text-sm shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                            <option value="ditindak" {{ $report->status === 'ditindak' ? 'selected' : '' }}>Ditindaklanjuti</option>
                            <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="hapus_thread" value="1"
                                   class="rounded border-slate-300 text-tenang-600 focus:ring-tenang-500">
                            Hapus thread ini juga
                        </label>
                        <button type="submit"
                                class="rounded-xl bg-tenang-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                            Simpan
                        </button>
                    </form>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
