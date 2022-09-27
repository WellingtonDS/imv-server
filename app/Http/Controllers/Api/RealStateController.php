<?php

namespace App\Http\Controllers\Api;
use App\Api\ApiMessages;
use App\Models\RealState;
use App\Http\Requests\RealStateRequest;
use App\Http\Controllers\Controller;


class RealStateController extends Controller
{

    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }


    public function index()
    {
        $realState = $this->realState->paginate('10');
        return response()->json($realState, 200);
    }

    
    //cadastrar imovel
    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        
        try {
            $realState = $this->realState->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                    ]
                ], 200);
                
            } catch (\Throwable $th) {
                $message = new ApiMessages($th->getMessage());
                return response()->json($message->getMessage(), 401);
            }
            
        }
        
    //mostrar um imovel
    public function show($id)
    {
        try {
            $realState = $this->realState->findOrFail($id); //dados em massa
            return response()->json([
                'data' => $realState
                ]
            , 200);
    
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    //atualizar imovel
    public function update ($id, RealStateRequest $request)
    {
        $data = $request->all();

        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel Atualizado com sucesso!'
                ]
            ], 200);

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    //deletar imovel
    public function destroy ($id)
    {
      
        try {
            $realState = $this->realState->findOrFail($id); //dados em massa
            $realState->delete();
            return response()->json([
                'data' => [
                    'msg' => 'Imóvel deletado com sucesso!'
                ]
            ], 200);

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
