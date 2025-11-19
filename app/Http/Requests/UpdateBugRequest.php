<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBugRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $bug = $this->route('bug');
        return $bug && ($this->user()->id === $bug->user_id || $this->user()->isModerator());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'steps_to_reproduce' => ['nullable', 'string'],
            'expected_behavior' => ['nullable', 'string'],
            'actual_behavior' => ['nullable', 'string'],
            'device_info' => ['nullable', 'string', 'max:255'],
            'os_version' => ['nullable', 'string', 'max:255'],
            'app_version' => ['nullable', 'string', 'max:255'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['string', 'url'],
            'severity' => ['sometimes', 'in:low,medium,high,critical'],
        ];

        // Only moderators can update status
        if ($this->user()->isModerator()) {
            $rules['status'] = ['sometimes', 'in:open,investigating,confirmed,fixed,closed,wont-fix'];
        }

        return $rules;
    }
}
