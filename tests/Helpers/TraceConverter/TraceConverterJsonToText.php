<?php

declare(strict_types=1);

namespace Helpers\TraceConverter;

use Helpers\PhpSerializationFixer;
use TelegramOSINT\Exception\TGException;

class TraceConverterJsonToText
{
    /**
     * @var JsonTraceReader
     */
    private $traceReader;

    /**
     * @param JsonTraceReader $traceReader
     */
    public function __construct(JsonTraceReader $traceReader)
    {
        $this->traceReader = $traceReader;
    }

    /**
     * Read the file and convert text trace to JSON.
     *
     * @param string $pathToJsonFile
     *
     * @throws TGException
     *
     * @return string
     */
    public function convert(string $pathToJsonFile): string
    {
        $trace = $this->getTraceReader()->read($pathToJsonFile);

        $encodedRecords = [];
        foreach ($trace->getRecords() as $record) {
            $fixedSerialization = PhpSerializationFixer::replaceNamespace(
                serialize($record->getMessage()),
                'TelegramOSINT\\\\MTSerialization\\\\',
                'MTSerialization\\'
            );

            $encodedRecords[] = [
                $record->getType(),
                bin2hex($fixedSerialization),
                $record->getTimeStamp(),
            ];
        }

        return json_encode([$trace->getTimeStamp(), $encodedRecords], JSON_PRETTY_PRINT);
    }

    /**
     * Wrapper for shorter syntax.
     *
     * @param string $pathToJsonFile
     *
     * @throws TGException
     *
     * @return string
     */
    public static function fromFile(string $pathToJsonFile): string
    {
        assert(file_exists($pathToJsonFile));

        return (new self(new JsonTraceReader()))->convert($pathToJsonFile);
    }

    /**
     * @return JsonTraceReader
     */
    private function getTraceReader(): JsonTraceReader
    {
        return $this->traceReader;
    }
}
