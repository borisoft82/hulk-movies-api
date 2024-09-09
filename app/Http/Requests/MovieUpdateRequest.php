<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /*if ($this->movie->user_id === Helper::loggedUserId()) {
            return true;
        }
        return false;*/

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|unique:movies|max:255',
            'storyline' => 'nullable|string',
            "image" => 'nullable',
            'director' => 'nullable|string|max:255',
            'writer' => 'nullable|string|max:255',
            'cast' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:categories,id'
        ];
    }
}
