<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CnsRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove os possíveis pontos e traços do CNS
        $cns = preg_replace('/[^0-9]/', '', $value);

        // Verifica se o CNS tem 15 dígitos
        if (strlen($cns) !== 15) {
            return false;
        }

        // Verifica se o CNS é válido
        $sum = 0;
        for ($i = 0; $i < 15; $i++) {
            $sum += $cns[$i] * (15 - $i);
        }
        $rest = $sum % 11;
        $digit = ($rest == 0 || $rest == 1) ? 0 : 11 - $rest;
        $validCns = substr($cns, 0, 15 - 1) . $digit;
        
        return $cns === $validCns;
    }

    public function message()
    {
        return 'O campo :attribute deve ser um CNS válido.';
    }
}
