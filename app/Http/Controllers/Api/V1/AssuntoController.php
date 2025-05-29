<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\UseCases\Assunto\AtualizarAssuntoUseCase;
use App\Domain\UseCases\Assunto\CriarAssuntoUseCase;
use App\Domain\UseCases\Assunto\DTOs\AtualizarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\CriarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\ExcluirAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\ListarAssuntosInputDTO;
use App\Domain\UseCases\Assunto\DTOs\VisualizarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\ExcluirAssuntoUseCase;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoAlreadyExistsException;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoNotFoundException;
use App\Domain\UseCases\Assunto\ListarAssuntosUseCase;
use App\Domain\UseCases\Assunto\VisualizarAssuntoUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssuntoController extends Controller
{
    public function __construct(
        private CriarAssuntoUseCase $criarAssuntoUseCase,
        private ListarAssuntosUseCase $listarAssuntosUseCase,
        private VisualizarAssuntoUseCase $visualizarAssuntoUseCase,
        private AtualizarAssuntoUseCase $atualizarAssuntoUseCase,
        private ExcluirAssuntoUseCase $excluirAssuntoUseCase
    ) {}

    /**
     * Lista todos os assuntos com paginação
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $input = new ListarAssuntosInputDTO(
                perPage: $request->input('per_page', 15),
                page: $request->input('page', 1)
            );

            $assuntos = $this->listarAssuntosUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $assuntos->toArray()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao listar assuntos: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Exibe um assunto específico
     */
    public function show(int $id): JsonResponse
    {
        try {
            $input = VisualizarAssuntoInputDTO::fromArray(['id' => $id]);
            $assunto = $this->visualizarAssuntoUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $assunto->toArray()
            ]);
            
        } catch (AssuntoNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar assunto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Cria um novo assunto
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $input = CriarAssuntoInputDTO::fromArray($request->all());
            $assunto = $this->criarAssuntoUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $assunto
            ], 201);
            
        } catch (AssuntoAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar assunto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Atualiza um assunto existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $dados = $request->all();
            $dados['id'] = $id;
            
            $input = AtualizarAssuntoInputDTO::fromArray($dados);
            $assunto = $this->atualizarAssuntoUseCase->handle($input);
            
            return response()->json([
                'success' => true,
                'data' => $assunto
            ]);
            
        } catch (AssuntoNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (AssuntoAlreadyExistsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar assunto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Remove um assunto
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $input = ExcluirAssuntoInputDTO::fromArray(['id' => $id]);
            $resultado = $this->excluirAssuntoUseCase->handle($input);
            
            return response()->json($resultado->toArray());
            
        } catch (AssuntoNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Erro ao excluir assunto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
