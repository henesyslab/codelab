<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use GitLab\Exception\ErrorException;
use Vinkla\GitLab\Facades\GitLab;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('client')->get();

        return response()->json($projects);
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
     * @param  \App\Http\Requests\StoreProjectRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $client = Client::find($request->get('client_id'));

        // Verifica se o cliente existe no banco de dados
        if (null === $client) {
            return response()->json([
                'client_id' => [trans('validation.client_not_exists')],
            ], 422);
        }

        // Tenta criar o repositório do projeto no GitLab
        try {
            $gitlab = GitLab::api('projects')->create($request->input('name'), [
                    'path' => $request->input('path'),
                    'namespace_id' => $client->gitlab_id,
                    'description' => $request->input('description', ''),
                    'wiki_enabled' => false,
                    'request_access_enabled' => false,
                    'tag_list' => $request->input('tag_list', ''),
                ]);
        } catch (ErrorException $e) {
            return response()->json([
                'path' => [trans('validation.gitlab_unique')],
            ], 422);
        }

        // Salva o projeto no banco de dados
        $project = new Project($request->input());
        $project->gitlab_id = $gitlab['id'];
        $project->save();

        return response()->json([
            'message' => 'Projeto cadastrado com sucesso!',
            'project' => $project->load('client'),
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
        $project = Project::findOrFail($id);

        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest $request
     * @param  int                                     $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        // Atualiza o projeto no banco de dados
        $project = Project::findOrFail($id);
        $project->fill($request->input());
        $project->save();

        // Atualiza o repositório do projeto no GitLab
        $gitlab = GitLab::api('projects')->update($project->gitlab_id, [
            'name' => $project->name,
            'description' => $request->input('description', ''),
            'tag_list' => $request->input('tag_list', ''),
        ]);

        return response()->json([
            'message' => 'Projeto atualizado com sucesso!',
            'project' => $project,
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
        // Remove o projeto do banco de dados
        $project = Project::findOrFail($id);
        $project->delete();

        // Remove o repositório do projeto no GitLab
        try {
            GitLab::api('projects')->remove($project->gitlab_id);
        } catch (ErrorException $e) {
            // $e->getMessage();
        }

        return response()->json([
            'message' => 'Projeto removido com sucesso!',
        ]);
    }
}
