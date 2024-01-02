<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IcalParseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->mergeIfMissing([
            'start_date' => now()->format('Y-m-d'),
            'end_date'   => now()->addYears(2)->format('Y-m-d'),
        ]);

        // Convert comma separated urls to array
        if (is_string($this->urls)) {
            $this->merge([
                'urls' => explode(',', $this->urls),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'urls'   => 'required|array',
            'urls.*' => 'required|url',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'text'       => 'nullable|string',
        ];
    }
}
