<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Vinkla\GitLab\Facades\GitLab;
use GitLab\Exception\ErrorException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name')->get();

        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        // Tenta criar a namespace do cliente no GitLab
        try {
            $gitlab = GitLab::api('groups')->create(
                $request->input('name'),
                $request->input('path'),
                $request->input('description', '')
            );
        } catch (ErrorException $e) {
            return response()->json([
                'path' => [trans('validation.gitlab_unique')],
            ], 422);
        }

        // Salva o cliente no banco de dados
        $client = new Client($request->input());
        $client->gitlab_id = $gitlab['id'];
        $client->save();

        return response()->json([
            'message' => 'Cliente cadastrado com sucesso!',
            'client' => $client,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest $request
     * @param  int                                    $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        // Atualiza o cliente no banco de dados
        $client = Client::findOrFail($id);
        $client->fill($request->input());
        $client->save();

        // Atualiza a namespace do cliente no GitLab
        $gitlab = GitLab::api('groups')->update($client->gitlab_id, [
            'name' => $client->name,
            'description' => $client->description,
        ]);

        return response()->json([
            'message' => 'Cliente atualizado com sucesso!',
            'client' => $client,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::withCount('projects')->findOrFail($id);

        // Se o cliente possui projetos...
        if ($client->projects_count > 0) {
            return response()->json([
                'message' => 'Este cliente possui '.
                $client->projects_count.
                ($client->projects_count === 1 ? ' projeto' : ' projetos').
                '. Remova os projetos primeiro.',
            ], 412);
        }

        // Remove o cliente do banco de dados
        $client->delete();

        // Remove a namespace do cliente no GitLab
        try {
            GitLab::api('groups')->remove($client->gitlab_id);
        } catch (ErrorException $e) {
            return response()->json([
                'message' => 'Não foi possível remover o cliente do GitLab.',
            ], 500);
        }

        return response()->json([
            'message' => 'Cliente removido com sucesso!',
        ]);
    }
}
