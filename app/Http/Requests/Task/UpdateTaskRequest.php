<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use Src\Task\Domain\Enum\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusTypes = implode(',', array_column(TaskStatusEnum::cases(), 'value'));

        return [
            'name'        => 'sometimes|string|max:150',
            'description' => 'sometimes|nullable|string',
            'status'      => "sometimes|in:$statusTypes",
            'user_id'     => 'sometimes|nullable|integer|exists:users,id',
        ];
    }
}
