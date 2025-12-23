<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research;

class ResearchController extends Controller
{
    public function index()
    {
        return response()->json(
            Research::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'  => ['required', 'exists:users,id'],
            'title'    => ['required', 'string', 'max:255'],
            'authors'  => ['required', 'string', 'max:255'],
            'abstract' => ['nullable', 'string'],
            'year'     => ['nullable', 'integer'],
            'doi'      => ['nullable', 'string'],
        ]);

        $research = Research::create($data);

        return response()->json($research, 201);
    }

    public function show($id)
    {
        return response()->json(
            Research::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $research = Research::findOrFail($id);

        $research->update(
            $request->only(['title','authors','abstract','year','doi'])
        );

        return response()->json($research);
    }

    public function destroy($id)
    {
        Research::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted']);
    }
}