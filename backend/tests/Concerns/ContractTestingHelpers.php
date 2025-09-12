<?php

namespace Tests\Concerns;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Client;

trait ContractTestingHelpers
{
    private static $validator;
    private static $passportSetup = false;
    
    protected function setUpPassportClient()
    {
        if (!static::$passportSetup) {
            // Create personal access client
            $client = Client::factory()->asPersonalAccessTokenClient()->create([
                'name' => 'Test Personal Access Client',
            ]);
            
            // Set the personal access client ID in the config for this test
            config(['passport.personal_access_client.id' => $client->id]);
            config(['passport.personal_access_client.secret' => null]);
            
            static::$passportSetup = true;
        }
    }
    
    protected function getOpenApiValidator()
    {
        if (!static::$validator) {
            $yamlPath = base_path('../docs/API.yaml');
            if (!file_exists($yamlPath)) {
                $this->markTestSkipped('API.yaml specification file not found at: ' . $yamlPath);
            }
            static::$validator = (new ValidatorBuilder())
                ->fromYamlFile($yamlPath)
                ->getResponseValidator();
        }
        
        return static::$validator;
    }
    
    protected function assertResponseMatchesOpenApiSpec(
        Response $response, 
        string $method, 
        string $path, 
        int $statusCode = null
    ): void {
        $statusCode = $statusCode ?: $response->getStatusCode();
        
        // Convert Symfony Response to PSR-7 Response
        $psr7Factory = new PsrHttpFactory();
        $psrResponse = $psr7Factory->createResponse($response);
        
        $operationAddress = new OperationAddress($path, strtolower($method));
        
        try {
            $this->getOpenApiValidator()->validate($operationAddress, $psrResponse);
            $this->assertTrue(true, "Response matches OpenAPI specification");
        } catch (ValidationFailed $e) {
            $this->fail(
                "Response does not match OpenAPI specification for {$method} {$path}:\n" .
                "Status Code: {$statusCode}\n" .
                "Validation Error: " . $e->getMessage() . "\n" .
                "Response Body: " . $response->getContent()
            );
        }
    }
    
    protected function makeApiRequest(string $method, string $uri, array $data = [], array $headers = [])
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        
        return $this->json($method, $uri, $data, array_merge($defaultHeaders, $headers));
    }
    
    protected function makeAuthenticatedApiRequest(string $method, string $uri, array $data = [], array $headers = [])
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('test-token')->accessToken;
        
        $authHeaders = [
            'Authorization' => 'Bearer ' . $token,
        ];
        
        return $this->makeApiRequest($method, $uri, $data, array_merge($authHeaders, $headers));
    }
    
    protected function extractApiPath(string $fullPath): string
    {
        // Remove base API path to match OpenAPI spec paths
        return str_replace('/api/v1', '', $fullPath);
    }
}