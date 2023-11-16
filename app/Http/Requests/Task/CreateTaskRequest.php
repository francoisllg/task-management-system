<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Src\Task\Application\Service\TaskStatusService;

class CreateTaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusTypes = implode(',', TaskStatusService::getTaskStatuses());

        return [
            'name'        => 'required|string|max:150',
            'description' => 'sometimes|string',
            'status'      => "sometimes|in:$statusTypes",
            'user_id'     => 'sometimes|integer|exists:users,id',
        ];
    }
}
