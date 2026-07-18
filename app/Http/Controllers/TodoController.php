<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $todos = $request->user()->todos()->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $todos]);
    }

    public function store(CreateTodoRequest $request): JsonResponse
    {
        $todo = $request->user()->todos()->create($request->validated());

        return response()->json(['data' => $todo], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $todo = $request->user()->todos()->find($id);

        if (!$todo) {
            return response()->json([
                'errors' => ['message' => 'Todo not found'],
            ], 404);
        }

        return response()->json(['data' => $todo]);
    }

    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        $todo = $request->user()->todos()->find($id);

        if (!$todo) {
            return response()->json([
                'errors' => ['message' => 'Todo not found'],
            ], 404);
        }

        $todo->fill($request->validated())->save();

        return response()->json(['data' => $todo]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $todo = $request->user()->todos()->find($id);

        if (!$todo) {
            return response()->json([
                'errors' => ['message' => 'Todo not found'],
            ], 404);
        }

        $todo->delete();

        return response()->json(['data' => ['message' => 'Todo deleted']]);
    }
}
