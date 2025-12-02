
<?php
namespace Cora\Exceptions;

class HttpException extends CoraException
{
    private $statusCode;
    private $body;

    public function __construct(string $message, int $statusCode = 0, ?string $body = null, \Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    public function getStatusCode(): int { return $this->statusCode; }
    public function getBody(): ?string { return $this->body; }
}
