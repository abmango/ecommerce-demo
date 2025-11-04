<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cuit' => ['required', 'digits:11', 'numeric'],
            'phone' => ['required', 'min_digits:10', 'numeric'],
            'preferred_contact_method' => ['required', 'in:email,telefono,whatsapp'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'cuit' => $input['cuit'],
            'phone' => $input['phone'],
            'preferred_contact_method' => $input['preferred_contact_method'],
            'password' => Hash::make($input['password']),
        ]);

        $user->sendEmailVerificationNotification();

        return $user;
    }
}
