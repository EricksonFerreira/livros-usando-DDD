<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssuntoController extends Controller
{   
    /**
     * @var AssuntoRepositoryInterface
     */
    private $assuntoRepository;

    /**
     * Construtor do controlador
     *
     * @param AssuntoRepositoryInterface $assuntoRepository
     */
    public function __construct(AssuntoRepositoryInterface $assuntoRepository)
    {
        $this->assuntoRepository = $assuntoRepository;
    }

    
    /**
     * Exibe a lista de assuntos paginados
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $assuntos = $this->assuntoRepository->all($perPage);
            
            return view('assuntos.index', compact('assuntos'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar assuntos: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar a lista de assuntos. Por favor, tente novamente.');
        }
    }

    /**
     * Mostra o formulário para criar um novo autor
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('assuntos.create');
    }

    /**
     * Armazena um novo assunto no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:100|unique:assuntos,descricao',
        ]);
        
        DB::beginTransaction();
        
        try {
            $this->assuntoRepository->create($request->only(['descricao']));
            
            DB::commit();
            
            return redirect()->route('assuntos.index')
                ->with('success', 'Assunto cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar assunto: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao cadastrar o assunto. Por favor, tente novamente.');
        }
    }

    /**
     * Mostra o formulário para editar o assunto especificado
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $assunto = $this->assuntoRepository->find($id);
            
            if (!$assunto) {
                return back()->with('error', 'Assunto não encontrado.');
            }
            
            return view('assuntos.edit', compact('assunto'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar o formulário de edição.');
        }
    }

    /**
     * Atualiza o assunto especificado no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:100|unique:assuntos,descricao,' . $id,
        ]);
        
        DB::beginTransaction();
        
        try {
            $assunto = $this->assuntoRepository->update($id, $request->only(['nome']));
            
            if (!$assunto) {
                throw new \Exception('Autor não encontrado para atualização.');
            }
            
            DB::commit();
            
            return redirect()->route('assuntos.index')
                ->with('success', 'Assunto atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar assunto: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao atualizar o assunto. Por favor, tente novamente.');
        }
    }

    /**
     * Remove o assunto especificado do banco de dados
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $result = $this->assuntoRepository->delete($id);
            
            if (!$result) {
                throw new \Exception('Assunto não encontrado para exclusão.');
            }
            
            DB::commit();
            
            return redirect()->route('assuntos.index')
                ->with('success', 'Assunto excluído com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir assunto: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir o assunto. Por favor, tente novamente.');
        }
    }
}
