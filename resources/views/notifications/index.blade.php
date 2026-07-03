<x-layouts.dashboard>
  <x-slot name="title">Notifications</x-slot>
  <x-slot name="subtitle">Toutes vos alertes et mises à jour.</x-slot>

  @push('styles')
  <style>
    .notif-list { display:flex; flex-direction:column; gap:8px; }

    .notif-card {
      display:flex; align-items:flex-start; gap:14px;
      background:#fff; border:1px solid #e2e8f0; border-radius:12px;
      padding:14px 18px; transition:box-shadow .2s;
    }
    .notif-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .notif-card.unread { border-left:3px solid #547A95; background:#f8fbfd; }

    .notif-icon {
      width:38px; height:38px; border-radius:10px; flex-shrink:0;
      display:flex; align-items:center; justify-content:center;
      font-size:17px; background:#f1f5f9;
    }
    .notif-icon.info    { background:#e0f2fe; }
    .notif-icon.success { background:#dcfce7; }
    .notif-icon.warning { background:#fef9c3; }
    .notif-icon.danger  { background:#fee2e2; }

    .notif-body { flex:1; min-width:0; }
    .notif-title { font-size:14px; font-weight:700; color:#0f172a; margin-bottom:3px; }
    .notif-msg   { font-size:13px; color:#64748b; line-height:1.5; }
    .notif-time  { font-size:11px; color:#94a3b8; margin-top:4px; }

    .notif-actions { display:flex; gap:6px; align-items:center; }
    .btn-icon {
      width:30px; height:30px; border-radius:7px; border:none; cursor:pointer;
      display:flex; align-items:center; justify-content:center; font-size:13px;
      background:#f1f5f9; color:#64748b; transition:all .15s;
    }
    .btn-icon:hover { background:#e2e8f0; color:#0f172a; }

    .empty-state { text-align:center; padding:64px 24px; color:#94a3b8; }
    .empty-state svg { opacity:.3; margin:0 auto 16px; display:block; }

    .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px; flex-wrap:wrap; }
    .badge-count { display:inline-flex; align-items:center; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; background:#e0f2fe; color:#0284c7; }

    .btn-mark-all {
      padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600;
      background:#0f172a; color:#fff; border:none; cursor:pointer; transition:background .2s;
    }
    .btn-mark-all:hover { background:#1e293b; }

    .pagination-wrap { display:flex; justify-content:center; margin-top:24px; gap:6px; }
    .page-btn {
      min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid #e2e8f0;
      background:#fff; color:#0f172a; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s;
    }
    .page-btn:hover, .page-btn.active { background:#0f172a; color:#fff; border-color:#0f172a; }
    .page-btn:disabled { opacity:.4; cursor:default; }
  </style>
  @endpush

  <div class="top-bar">
    <div style="display:flex;align-items:center;gap:10px;">
      <h2 style="font-size:18px;font-weight:700;color:#0f172a;margin:0;">Notifications</h2>
      @php $unread = $notifications->getCollection()->where('read_at', null)->count(); @endphp
      @if($unread > 0)
        <span class="badge-count">{{ $unread }} non lue{{ $unread > 1 ? 's' : '' }}</span>
      @endif
    </div>
    @if($notifications->total() > 0)
      <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf @method('PUT')
        <button type="submit" class="btn-mark-all">Tout marquer comme lu</button>
      </form>
    @endif
  </div>

  @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#16a34a;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
      {{ session('success') }}
    </div>
  @endif

  @if($notifications->total() === 0)
    <div class="empty-state">
      <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <p style="font-size:15px;font-weight:600;color:#475569;margin-bottom:6px;">Aucune notification</p>
      <p style="font-size:13px;">Vous êtes à jour ! Les nouvelles alertes apparaîtront ici.</p>
    </div>
  @else
    <div class="notif-list">
      @foreach($notifications as $notif)
        @php
          $data    = $notif->data ?? [];
          $title   = $data['title']   ?? $data['subject'] ?? class_basename($notif->type);
          $message = $data['message'] ?? $data['body']    ?? $data['content'] ?? '';
          $url     = $data['url']     ?? null;
          $type    = $data['type']    ?? 'info';
          $icons   = ['info'=>'ℹ️','success'=>'✅','warning'=>'⚠️','danger'=>'🔴','trade'=>'📈','deposit'=>'💰','withdraw'=>'💸','kyc'=>'🪪'];
          $icon    = $icons[$type] ?? $icons['info'];
          $isUnread = is_null($notif->read_at);
        @endphp
        <div class="notif-card {{ $isUnread ? 'unread' : '' }}">
          <div class="notif-icon {{ $type }}">{{ $icon }}</div>
          <div class="notif-body">
            <div class="notif-title">{{ $title }}</div>
            @if($message)
              <div class="notif-msg">{{ $message }}</div>
            @endif
            <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
          </div>
          <div class="notif-actions">
            @if($isUnread)
              <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                @csrf @method('PUT')
                <button type="submit" class="btn-icon" title="Marquer comme lu">✓</button>
              </form>
            @endif
            @if($url)
              <a href="{{ $url }}" class="btn-icon" title="Voir">→</a>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" onsubmit="return confirm('Supprimer cette notification ?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-icon" title="Supprimer">🗑</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
      <div class="pagination-wrap">
        @if($notifications->onFirstPage())
          <button class="page-btn" disabled>‹</button>
        @else
          <a href="{{ $notifications->previousPageUrl() }}" class="page-btn">‹</a>
        @endif

        @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $pageUrl)
          <a href="{{ $pageUrl }}" class="page-btn {{ $page == $notifications->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($notifications->hasMorePages())
          <a href="{{ $notifications->nextPageUrl() }}" class="page-btn">›</a>
        @else
          <button class="page-btn" disabled>›</button>
        @endif
      </div>
    @endif
  @endif

</x-layouts.dashboard>
