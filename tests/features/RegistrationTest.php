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

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    function test_create_user_form_validation()
    {
        $this->visitRoute('register')
            ->press('Regístrate')
            ->seePageIs('register')
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio'
            ]);
    }

    function test_that_the_mail_is_valid()
    {
        $this->visitRoute('register')
            ->type('checha', 'email')
            ->type('Ducke', 'username')
            ->type('Cesar', 'first_name')
            ->type('Acual', 'last_name')
            ->press('Regístrate')
            ->seePageIs('register')
            ->seeErrors([
                'email' => 'correo electrónico no es un correo válido'
            ]);
    }

    function test_user_email_has_not_been_taken()
    {
        $this->defaultUser([
            'email' => 'chechaacual@gmail.com',
        ]);

        $this->visitRoute('register')
            ->type('chechaacual@gmail.com', 'email')
            ->type('Ducke', 'username')
            ->type('Cesar', 'first_name')
            ->type('Acual', 'last_name')
            ->press('Regístrate')
            ->seePageIs('register')
            ->seeErrors([
                'email' => 'El correo electrónico ya ha sido registrado.'
            ]);
    }
}
