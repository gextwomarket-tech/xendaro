@extends('layouts.email')

@section('content')
<div style="background-color: #f8fafc; padding: 40px 20px; text-align: center;">
    <div style="background-color: white; max-width: 600px; margin: 0 auto; border-radius: 12px; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        
        <h1 style="color: #0f172a; margin-bottom: 20px; font-size: 24px;">Nouveau Message de Support</h1>
        
        <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
            {!! nl2br(e($body)) !!}
        </div>

        <p style="color: #64748b; font-size: 14px; margin-top: 30px;">
            Veuillez répondre à ce message dès que possible via le panneau d'administration.
        </p>

        <p style="color: #94a3b8; font-size: 12px; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            Email de support: <strong>{{ $supportEmail }}</strong>
        </p>

    </div>
</div>
@endsection
