<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\OtpMailerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Un SMTP mal configure (ex. MAIL_HOST de dev laisse en prod) fait planter
 * Notification::send avec une exception de transport - OtpMailerService doit
 * l'absorber sans jamais la laisser remonter et casser le flux auth appelant.
 */
class OtpMailerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_returns_false_and_logs_when_mail_transport_throws(): void
    {
        $user = User::factory()->create();

        $mock = \Mockery::mock($user)->makePartial();
        $mock->shouldReceive('notify')->once()->andThrow(
            new \Symfony\Component\Mailer\Exception\TransportException('Connection refused')
        );

        Log::shouldReceive('error')->once()->with('Echec envoi OTP email', \Mockery::on(
            fn ($context) => $context['user_id'] === $user->id && $context['context'] === 'verification'
        ));

        $result = OtpMailerService::send($mock, '123456', 'verification');

        $this->assertFalse($result);
    }

    public function test_send_returns_true_when_mail_succeeds(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $result = OtpMailerService::send($user, '123456', 'verification');

        $this->assertTrue($result);
    }
}
