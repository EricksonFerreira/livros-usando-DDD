<?php

namespace App\Http\Controllers;

use App\Domain\Models\Autor;
use App\Domain\Models\Assunto;
use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    /**
     * @var LivroRepositoryInterface
     */
    private $livroRepository;
    
    /**
     * @var AutorRepositoryInterface
     */
    private $autorRepository;
    
    /**
     * @var AssuntoRepositoryInterface
     */
    private $assuntoRepository;

    /**
     * Construtor do controlador
     *
     * @param LivroRepositoryInterface $livroRepository
     * @param AutorRepositoryInterface $autorRepository
     * @param AssuntoRepositoryInterface $assuntoRepository
     */
    public function __construct(
        LivroRepositoryInterface $livroRepository,
        AutorRepositoryInterface $autorRepository,
        AssuntoRepositoryInterface $assuntoRepository
    ) {
        $this->livroRepository = $livroRepository;
        $this->autorRepository = $autorRepository;
        $this->assuntoRepository = $assuntoRepository;
    }

    /**
     * Exibe a lista de livros paginados
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $livros = $this->livroRepository->all($perPage);
            
            return view('livros.index', compact('livros'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar livros: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar a lista de livros. Por favor, tente novamente.');
        }
    }
    
    /**
     * Mostra o formulário para criar um novo livro
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $autores = $this->autorRepository->all(1000); // Número grande para pegar todos
            $assuntos = $this->assuntoRepository->all(1000);
            
            return view('livros.create', compact('autores', 'assuntos'));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de cadastro: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar o formulário de cadastro.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'editora' => 'required|string|max:100',
            'edicao' => 'required|integer',
            'ano_publicacao' => 'required|string|size:4',
            'valor' => 'required|numeric|min:0',
            'autores' => 'required|array|min:1',
            'autores.*' => 'exists:autores,id',
            'assuntos' => 'required|array|min:1',
            'assuntos.*' => 'exists:assuntos,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Cria o livro
            $livro = $this->livroRepository->create($request->only([
                'titulo', 'editora', 'edicao', 'ano_publicacao', 'valor'
            ]));
            
            // Associa autores e assuntos
            $this->livroRepository->syncAutores($livro->id, $request->input('autores'));
            $this->livroRepository->syncAssuntos($livro->id, $request->input('assuntos'));
            
            DB::commit();
            
            return redirect()->route('livros.index')
                ->with('success', 'Livro cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar livro: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao cadastrar o livro. Por favor, tente novamente.');
        }
    }

    /**
     * Mostra o formulário para editar o livro especificado
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $livro = $this->livroRepository->findWithRelations($id, ['autores', 'assuntos']);
            
            if (!$livro) {
                return back()->with('error', 'Livro não encontrado.');
            }
            
            $autores = $this->autorRepository->all(1000);
            $assuntos = $this->assuntoRepository->all(1000);
            
            $autoresSelecionados = $livro->autores->pluck('id')->toArray();
            $assuntosSelecionados = $livro->assuntos->pluck('id')->toArray();
            
            return view('livros.edit', compact(
                'livro', 
                'autores', 
                'assuntos', 
                'autoresSelecionados', 
                'assuntosSelecionados'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar o formulário de edição.');
        }
    }

    /**
     * Atualiza o livro especificado no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:100',
            'editora' => 'required|string|max:100',
            'edicao' => 'required|integer',
            'ano_publicacao' => 'required|string|size:4',
            'valor' => 'required|numeric|min:0',
            'autores' => 'required|array|min:1',
            'autores.*' => 'exists:autores,id',
            'assuntos' => 'required|array|min:1',
            'assuntos.*' => 'exists:assuntos,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Atualiza o livro
            $livro = $this->livroRepository->update($id, $request->only([
                'titulo', 'editora', 'edicao', 'ano_publicacao', 'valor'
            ]));
            
            if (!$livro) {
                throw new \Exception('Livro não encontrado para atualização.');
            }
            
            // Sincroniza autores e assuntos
            $this->livroRepository->syncAutores($livro->id, $request->input('autores'));
            $this->livroRepository->syncAssuntos($livro->id, $request->input('assuntos'));
            
            DB::commit();
            
            return redirect()->route('livros.index')
                ->with('success', 'Livro atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar livro: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erro ao atualizar o livro. Por favor, tente novamente.');
        }
    }

    /**
     * Remove o livro especificado do banco de dados
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $result = $this->livroRepository->delete($id);
            
            if (!$result) {
                throw new \Exception('Livro não encontrado para exclusão.');
            }
            
            DB::commit();
            
            return redirect()->route('livros.index')
                ->with('success', 'Livro excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('erro', 'Erro ao remover o livro: ' . $e->getMessage());
        }
    }
}
