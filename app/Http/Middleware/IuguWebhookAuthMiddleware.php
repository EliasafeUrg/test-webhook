<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use function Laravel\Prompts\password;

class IuguWebhookAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar as credenciais Basic Auth
        $credentials = $request->getUser();
        $password = $request->getPassword();
        // dd('meuovo');
        // return response()->json([
        //     'data' => [
        //         'user' => $credentials,
        //         'password' => $password,
        //     ],
        //     'Basic' => 'Basic ' . base64_encode($credentials . ':' . $password),
        // ], 200);



        // Verifica se o cabeçalho Authorization existe
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (!Str::startsWith($request->header('Authorization'), 'Basic ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Log::info('Usuário autenticado com sucesso.', ['Authorization' => $request->header('Authorization')]);

        if (!$this->validateCredentials($request->header('Authorization'))) {
            // As credenciais são válidas, continue com o processamento do webhook
            return response()->json(
                [
                    'error' => 'Unauthorized',
                    'message' => 'Autenticação falhou',
                    // 'data' => $request->all()
                ]
            );
        }

        return $next($request);
    }

    private function validateCredentials($crecrencial)
    {
        // verifica se o token base com gatilho iugu
        $basicAuth  = config('app.iugu_webhook_token');
        return $crecrencial === 'Basic ' . base64_encode($basicAuth);
    }

    
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $token = config('app.iugu_webhook_token');

    //     // Verifica se o cabeçalho Authorization existe
    //     if (!$request->hasHeader('Authorization')) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Obtém o valor do cabeçalho Authorization
    //     $authorizationHeader = $request->header('Authorization');

    //     // Verifica se o valor do cabeçalho contém o prefixo 'Bearer'
    //     if (!Str::startsWith($authorizationHeader, 'Bearer ')) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Obtém o token da parte do cabeçalho após o prefixo 'Bearer'
    //     $tokenFromHeader = substr($authorizationHeader, 7);

    //     // Compara o token recebido com o token esperado
    //     if ($tokenFromHeader !== $token) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $next($request);
    // }
}
