{{-- KYC Status Reminder Badge for Navbar/Sidebar --}}
@php
    $user = auth()->user();
    $kycStatus = $user?->kyc_status;
@endphp

@if($kycStatus !== 'verified' && $user)
    <a href="{{ route('kyc.show') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105
        @if($kycStatus === 'pending')
            bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-700
        @else
            bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-700
        @endif
    ">
        <span class="material-symbols-outlined text-[14px]">
            {{ $kycStatus === 'pending' ? 'schedule' : 'warning' }}
        </span>
        <span class="hidden sm:inline">
            {{ $kycStatus === 'pending' ? 'KYC en cours' : 'KYC requis' }}
        </span>
        <span class="sm:hidden">
            {{ $kycStatus === 'pending' ? 'KYC…' : 'KYC!' }}
        </span>
        <span class="ml-1 w-1.5 h-1.5 rounded-full animate-pulse
            {{ $kycStatus === 'pending' ? 'bg-amber-500' : 'bg-red-500' }}">
        </span>
    </a>
@endif
