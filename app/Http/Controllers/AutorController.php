<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutorController extends Controller
{   
    /**
     * @var AutorRepositoryInterface
     */
    private $autorRepository;

    /**
     * Construtor do controlador
     *
     * @param AutorRepositoryInterface $autorRepository
     */
    public function __construct(AutorRepositoryInterface $autorRepository)
    {
        $this->autorRepository = $autorRepository;
    }

    
    /**
     * Exibe a lista de autores paginados
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $autores = $this->autorRepository->all($perPage);
            
            return view('autores.index', compact('autores'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar autores: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar a lista de autores. Por favor, tente novamente.');
        }
    }

    /**
     * Mostra o formulário para criar um novo autor
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Armazena um novo autor no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:autores,nome',
        ]);
        
        DB::beginTransaction();
        
        try {
            $this->autorRepository->create($request->only(['nome']));
            
            DB::commit();
            
            return redirect()->route('autores.index')
                ->with('success', 'Autor cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar autor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao cadastrar o autor. Por favor, tente novamente.');
        }
    }

    /**
     * Mostra o formulário para editar o autor especificado
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $autor = $this->autorRepository->find($id);
            
            if (!$autor) {
                return back()->with('error', 'Autor não encontrado.');
            }
            
            return view('autores.edit', compact('autor'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar o formulário de edição.');
        }
    }

    /**
     * Atualiza o autor especificado no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:autores,nome,' . $id,
        ]);
        
        DB::beginTransaction();
        
        try {
            $autor = $this->autorRepository->update($id, $request->only(['nome']));
            
            if (!$autor) {
                throw new \Exception('Autor não encontrado para atualização.');
            }
            
            DB::commit();
            
            return redirect()->route('autores.index')
                ->with('success', 'Autor atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar autor: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao atualizar o autor. Por favor, tente novamente.');
        }
    }

    /**
     * Remove o autor especificado do banco de dados
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $result = $this->autorRepository->delete($id);
            
            if (!$result) {
                throw new \Exception('Autor não encontrado para exclusão.');
            }
            
            DB::commit();
            
            return redirect()->route('autores.index')
                ->with('success', 'Autor excluído com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir autor: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir o autor. Por favor, tente novamente.');
        }
    }
}
