<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\UseCases\Livro\CriarLivroUseCase;
use App\Domain\UseCases\Livro\ListarLivrosUseCase;
use App\Domain\UseCases\Livro\VisualizarLivroUseCase;
use App\Domain\UseCases\Livro\AtualizarLivroUseCase;
use App\Domain\UseCases\Livro\ExcluirLivroUseCase;
use App\Domain\UseCases\Livro\DTOs\CriarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\ListarLivrosInputDTO;
use App\Domain\UseCases\Livro\DTOs\VisualizarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\AtualizarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\ExcluirLivroInputDTO;
use App\Domain\UseCases\Livro\Exceptions\LivroAlreadyExistsException;
use App\Domain\UseCases\Livro\Exceptions\LivroNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LivroController extends Controller
{
    public function __construct(
        private CriarLivroUseCase $criarLivroUseCase,
        private ListarLivrosUseCase $listarLivrosUseCase,
        private VisualizarLivroUseCase $visualizarLivroUseCase,
        private AtualizarLivroUseCase $atualizarLivroUseCase,
        private ExcluirLivroUseCase $excluirLivroUseCase
    ) {}

    /**
     * Lista todos os livros com paginação e filtros
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $input = ListarLivrosInputDTO::fromArray($request->all());
            $output = $this->listarLivrosUseCase->handle($input);
            
            return response()->json($output->toArray());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar livros: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cria um novo livro
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:100',
                'editora' => 'required|string|max:100',
                'edicao' => 'required|integer|min:1',
                'ano_publicacao' => 'required|string|size:4',
                'valor' => 'required|numeric|min:0',
                'autores_ids' => 'sometimes|array',
                'autores_ids.*' => 'integer|exists:autores,id',
                'assuntos_ids' => 'sometimes|array',
                'assuntos_ids.*' => 'integer|exists:assuntos,id',
            ]);

            $input = CriarLivroInputDTO::fromArray($validated);
            $output = $this->criarLivroUseCase->handle($input);
            
            return response()->json($output, 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (LivroAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar livro: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibe um livro específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $input = new VisualizarLivroInputDTO(id: $id);
            $output = $this->visualizarLivroUseCase->handle($input);
            
            return response()->json($output->toArray());
            
        } catch (LivroNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao visualizar livro: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualiza um livro existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:100',
                'editora' => 'required|string|max:100',
                'edicao' => 'required|integer|min:1',
                'ano_publicacao' => 'required|string|size:4',
                'valor' => 'required|numeric|min:0',
                'autores_ids' => 'sometimes|array',
                'autores_ids.*' => 'integer|exists:autores,id',
                'assuntos_ids' => 'sometimes|array',
                'assuntos_ids.*' => 'integer|exists:assuntos,id',
            ]);
            
            $validated['id'] = $id;
            $input = AtualizarLivroInputDTO::fromArray($validated);
            $output = $this->atualizarLivroUseCase->handle($input);
            
            return response()->json($output);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (LivroNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
            
        } catch (LivroAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar livro: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove um livro
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $input = new ExcluirLivroInputDTO(id: $id);
            $output = $this->excluirLivroUseCase->handle($input);
            
            return response()->json($output->toArray());
            
        } catch (LivroNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir livro: ' . $e->getMessage(),
            ], 500);
        }
    }
}
