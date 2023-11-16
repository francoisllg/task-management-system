<?php
declare(strict_types=1);

namespace Src\Task\Infraestructure\Repository;

use Src\Task\Domain\Entity\Task;
use Src\User\Domain\Entity\User;
use Src\Task\Domain\ValueObject\TaskId;
use Src\User\Domain\ValueObject\UserId;
use Src\Task\Domain\ValueObject\TaskName;
use Src\User\Domain\ValueObject\UserName;
use Src\User\Domain\ValueObject\UserEmail;
use Src\Task\Domain\ValueObject\TaskStatus;
use App\Models\Task\Task as EloquentTaskModel;
use Src\Task\Domain\ValueObject\TaskDescription;
use Src\Task\Domain\Interface\TaskRepositoryInterface;

final class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function create(Task $task): Task
    {
        $newTaskModel = new EloquentTaskModel();
        $newTaskModel->name = $task->getName()->value();
        $newTaskModel->status = $task->getStatus()->value();
        $newTaskModel->description = $task->getDescription()->value();

        if ($task->getUser() !== null) {
            $newTaskModel->user_id = $task->getUser()->getId()->value();
        }

        $newTaskModel->save();
        $task->setId(new TaskId($newTaskModel->id));
        return $task;
    }

    public function update(Task $task): bool
    {
        $result = false;
        $taskModel = EloquentTaskModel::find($task->getId()->value());

        if ($taskModel) {
            $taskModel->name = $task->getName()->value();
            $taskModel->status = $task->getStatus()->value();
            $taskModel->description = $task->getDescription()->value();

            if ($task->getUser() !== null) {
                $taskModel->user_id = $task->getUser()->getId()->value();
            }
            $result = boolval($taskModel->update());
        }

        return $result;
    }

    public function delete(TaskId $task_id): bool
    {
        $taskModel = EloquentTaskModel::find($task_id->value());
        return ($taskModel) ? boolval($taskModel->delete()) : false;
    }

    public function findById(TaskId $task_id): ?Task
    {
        $taskModel = EloquentTaskModel::find($task_id->value());

        if ($taskModel) {
            $task = new Task(
                new TaskId($taskModel->id),
                new TaskName($taskModel->name),
                new TaskStatus($taskModel->status),
                new TaskDescription($taskModel->description),
            );
            if ($taskModel->user_id !== null) {
                $task->setUser(
                    new User(
                        new UserId($taskModel->user->id),
                        new UserName($taskModel->user->name),
                        new UserEmail($taskModel->user->email),
                    )
                );
            }
            return $task;
        }

        return null;
    }

    public function findAll(): array
    {
        $allTaskModelData = EloquentTaskModel::all();
        $result = [];
        if ($allTaskModelData->count() > 0) {
            foreach ($allTaskModelData as $taskModelData) {
                $taskEntity = new Task(
                    new TaskId($taskModelData->id),
                    new TaskName($taskModelData->name),
                    new TaskStatus($taskModelData->status),
                    new TaskDescription($taskModelData->description),
                );

                if ($taskModelData->user_id !== null) {
                    $taskEntity->setUser(
                        new User(
                            new UserId($taskModelData->user->id),
                            new UserName($taskModelData->user->name),
                            new UserEmail($taskModelData->user->email),
                        )
                    );
                }
                $result[] = $taskEntity;
            }
        }

        return $result;
    }

    public function findAllByUserId(UserId $id): array
    {
        $allTaskModelData = EloquentTaskModel::where('user_id', $id->value())->get();
        $result = [];
        if ($allTaskModelData->count() > 0) {
            foreach ($allTaskModelData as $taskModelData) {
                $taskEntity = new Task(
                    new TaskId($taskModelData->id),
                    new TaskName($taskModelData->name),
                    new TaskStatus($taskModelData->status),
                    new TaskDescription($taskModelData->description),
                    new User(
                        new UserId($taskModelData->user->id),
                        new UserName($taskModelData->user->name),
                        new UserEmail($taskModelData->user->email),
                    )
                );

                $result[] = $taskEntity;
            }
        }
        return $result;
    }
}
