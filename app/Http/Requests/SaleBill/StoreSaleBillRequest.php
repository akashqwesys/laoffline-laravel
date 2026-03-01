<?php

namespace App\Http\Requests\SaleBill;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'referenceDetails' => $this->referenceDetails
                ? json_decode($this->referenceDetails, true)
                : null,

            'productDetails' => $this->productDetails
                ? json_decode($this->productDetails, true)
                : [],

            'fabricDetails' => $this->fabricDetails
                ? json_decode($this->fabricDetails, true)
                : [],

            'transportDetails' => $this->transportDetails
                ? json_decode($this->transportDetails, true)
                : null,

            'changeAmount' => $this->changeAmount
                ? json_decode($this->changeAmount, true)
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'referenceDetails' => ['required'],
            'transportDetails' => ['required'],
            'changeAmount'     => ['required'],
            'productDetails'   => ['array'],
            'fabricDetails'    => ['array'],
            'final_total'      => ['required', 'numeric', 'gt:0'],
            'extra_attachment' => ['required', 'file', 'max:20480'],
        ];
    }

    public function getReferenceDetails(): object
    {
        return $this->toDeepObject($this->referenceDetails);
    }

    public function getProductDetails(): array
    {
        return array_map(
            fn($item) => $this->toDeepObject($item),
            $this->productDetails ?? []
        );
    }

    public function getFabricDetails(): array
    {
        return array_map(
            fn($item) => $this->toDeepObject($item),
            $this->fabricDetails ?? []
        );
    }

    public function getTransportDetails(): object
    {
        return $this->toDeepObject($this->transportDetails);
    }

    public function getChangeAmount(): object
    {
        return $this->toDeepObject($this->changeAmount);
    }

    private function toDeepObject($data)
    {
        if (is_array($data)) {

            // Check if associative array
            if ($this->isAssoc($data)) {
                return (object) collect($data)
                    ->map(fn($item) => $this->toDeepObject($item))
                    ->toArray();
            }

            // Indexed array → keep as array but convert children
            return array_map(fn($item) => $this->toDeepObject($item), $data);
        }

        return $data;
    }

    private function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
