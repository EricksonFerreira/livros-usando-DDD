<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\UseCases\Autor\AtualizarAutorUseCase;
use App\Domain\UseCases\Autor\CriarAutorUseCase;
use App\Domain\UseCases\Autor\DTOs\AtualizarAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\CriarAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\ExcluirAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\ListarAutoresInputDTO;
use App\Domain\UseCases\Autor\DTOs\VisualizarAutorInputDTO;
use App\Domain\UseCases\Autor\ExcluirAutorUseCase;
use App\Domain\UseCases\Autor\Exceptions\AutorAlreadyExistsException;
use App\Domain\UseCases\Autor\Exceptions\AutorNotFoundException;
use App\Domain\UseCases\Autor\ListarAutoresUseCase;
use App\Domain\UseCases\Autor\VisualizarAutorUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutorController extends Controller
{
    public function __construct(
        private CriarAutorUseCase $criarAutorUseCase,
        private ListarAutoresUseCase $listarAutoresUseCase,
        private VisualizarAutorUseCase $visualizarAutorUseCase,
        private AtualizarAutorUseCase $atualizarAutorUseCase,
        private ExcluirAutorUseCase $excluirAutorUseCase
    ) {}

    /**
     * Lista todos os autores com paginação
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $input = new ListarAutoresInputDTO(
                perPage: $request->input('per_page', 15),
                page: $request->input('page', 1)
            );

            $autores = $this->listarAutoresUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $autores->toArray()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao listar autores: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Exibe um autor específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $input = VisualizarAutorInputDTO::fromArray(['id' => $id]);
            $autor = $this->visualizarAutorUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $autor->toArray()
            ]);
            
        } catch (AutorNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar autor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Cria um novo autor
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $input = CriarAutorInputDTO::fromArray($request->all());
            $autor = $this->criarAutorUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $autor
            ], 201);
            
        } catch (AutorAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar autor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Atualiza um autor existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $dados = $request->all();
            $dados['id'] = $id;
            
            $input = AtualizarAutorInputDTO::fromArray($dados);
            $autor = $this->atualizarAutorUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $autor
            ]);
            
        } catch (AutorNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (AutorAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar autor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Remove um autor
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $input = ExcluirAutorInputDTO::fromArray(['id' => $id]);
            $resultado = $this->excluirAutorUseCase->handle($input);
            
            return response()->json($resultado->toArray());
            
        } catch (AutorNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Erro ao excluir autor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
