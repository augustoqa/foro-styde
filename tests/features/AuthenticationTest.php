<?php

use App\Mail\TokenMail;
use App\Token;
use Illuminate\Support\Facades\Mail;

class AuthenticationTest extends FeatureTestCase
{
    function test_a_guest_user_can_request_a_token()
    {
        // Having
        Mail::fake();

        $user = $this->defaultUser(['email' => 'chechaacual@gmail.com']);

        // When
        $this->visitRoute('login')
            ->type('chechaacual@gmail.com', 'email')
            ->press('Solicitar token');

        // Then: a new token is created in the database
        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token, 'A token was not created');

        // And sent to the user
        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id === $token->id;
        });

        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    function test_a_guest_user_cannot_request_a_token_without_an_email()
    {
        // Having
        Mail::fake();

        // When
        $this->visitRoute('login')
            ->press('Solicitar token');

        // Then: a new token is NOT created in the database
        $token = Token::first();

        $this->assertNull($token, 'A token was created');

        // And sent to the user
        Mail::assertNotSent(TokenMail::class);

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio.'
        ]);
    }

    function test_a_guest_user_cannot_request_a_token_with_an_invalid_email()
    {
        // When
        $this->visitRoute('login')
            ->type('Ducke', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Correo electrónico no es un correo válido'
        ]);
    }

    function test_a_guest_user_cannot_request_a_token_with_a_non_existent_email()
    {
        $this->defaultUser(['email' => 'admin@gmail.com']);

        // When
        $this->visitRoute('login')
            ->type('chechaacual@gmail.com', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no existe'
        ]);
    }
}
