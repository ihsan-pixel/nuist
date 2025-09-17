<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class AdminImport implements ToModel, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'name'        => $row[0],
            'email'       => $row[1],
            'phone'       => $row[2],
            'madrasah_id' => $row[3],
            'password'    => bcrypt('password123'), // default password
            'role'        => 'admin',
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|max:255',
            '1' => 'required|email|unique:users,email',
            '2' => 'required|string|max:255',
            '3' => 'required|exists:madrasahs,id',
        ];
    }
}
