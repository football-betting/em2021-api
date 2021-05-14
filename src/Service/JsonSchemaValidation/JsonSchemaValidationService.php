<?php declare(strict_types=1);

namespace App\Service\JsonSchemaValidation;

use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\Validator;

final class JsonSchemaValidationService
{
    private const JSON_ERROR = 512;

    /**
     * @var \Opis\JsonSchema\Errors\ErrorFormatter
     */
    private ErrorFormatter $errorFormatter;

    /**
     * @var \Opis\JsonSchema\Validator
     */
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->errorFormatter = new ErrorFormatter();
    }

    /**
     * @param string $json
     * @param string $schemaName
     *
     * @return string[]
     */
    public function getErrors(string $json, string $schemaName): array
    {
        $pathToSchema = __DIR__ . '/../../../schema/json/' . $schemaName . '.json';

        if (!file_exists($pathToSchema)) {
            throw new \RuntimeException('File not found: ' . $pathToSchema);
        }

        $schemaUrl = 'https://api.example.com/' . $schemaName;
        $schemaResolver = $this->validator->resolver();
        if ($schemaResolver instanceof SchemaResolver) {
            $schemaResolver->registerFile(
                $schemaUrl,
                $pathToSchema
            );
        }

        $data = json_decode($json, false, self::JSON_ERROR, JSON_THROW_ON_ERROR);
        $result = $this->validator->validate($data, $schemaUrl);

        $errors = $result->error();
        if ($errors instanceof ValidationError) {
            $e = $this->errorFormatter->format($errors);
            return current($e);
        }

        return [];
    }
}
