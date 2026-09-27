<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CedulaEcuatoriana implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Debe tener 10 dígitos numéricos
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $fail('La cédula debe contener exactamente 10 dígitos numéricos.');
            return;
        }

        // 2. El código de provincia debe estar entre 01 y 24, o ser 30 (exterior)
        $provincia = (int) substr($value, 0, 2);
        if ($provincia < 1 || ($provincia > 24 && $provincia != 30)) {
            $fail('El código de provincia de la cédula es inválido.');
            return;
        }

        // 3. El tercer dígito debe ser menor a 6 (0, 1, 2, 3, 4, 5) para personas naturales
        $tercerDigito = (int) $value[2];
        if ($tercerDigito >= 6) {
            $fail('La cédula no pertenece a una persona natural.');
            return;
        }

        // 4. Algoritmo Módulo 10
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = (int) $value[$i] * $coeficientes[$i];
            $suma += ($valor >= 10) ? $valor - 9 : $valor;
        }

        $digitoVerificadorCalculado = ($suma % 10 == 0) ? 0 : (10 - ($suma % 10));
        $digitoVerificadorReal = (int) $value[9];

        if ($digitoVerificadorCalculado !== $digitoVerificadorReal) {
            $fail('La cédula es matemáticamente inválida.');
        }
    }
}
