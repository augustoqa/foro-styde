<?php

use App\Mail\TokenMail;
use App\Token;
use App\User;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('chechaacual@gmail.com', 'email')
            ->type('ducke', 'username')
            ->type('Cesar', 'first_name')
            ->type('Acual', 'last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users', [
            'email' => 'chechaacual@gmail.com',
            'username' => 'ducke',
            'first_name' => 'Cesar',
            'last_name' => 'Acual'
        ]);

        $user = User::first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        // todo: finish this feature!

        // $this->seeRouteIs('register_confirmation')
        //     ->see('Gracias por registrarte')
        //     ->see('Enviamos a tu email un enlace para que inicies sesión');
    }
}
