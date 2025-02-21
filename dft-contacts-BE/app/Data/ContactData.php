<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ContactData extends Data
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $fname,
        public string $lname,
        public string $email,
        public string $phone,
        public ?string $address
    ){}
    
    /**
     * Method rules
     * Description: Get the validation rules that apply to the data.
     * @return array
     */
    public static function rules(): array
    {
        return [
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string']
        ];
    }
}