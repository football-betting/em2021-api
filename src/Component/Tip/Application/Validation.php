<?php declare(strict_types=1);

namespace App\Component\Tip\Application;

use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\Validator;

class Validation
{
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

    public function getErrors(string $json): array
    {
        $this->validator->resolver()->registerFile(
            'http://api.example.com/tip.json',
            __DIR__ . '/../../../../schema/json/tipp.json'
        );

        $data = json_decode($json);
        $result = $this->validator->validate($data, 'http://api.example.com/tip.json');

        $errors = $result->error();
        if ($errors instanceof ValidationError) {
            return $this->errorFormatter->format($errors);
        }

        return [];
    }
}
