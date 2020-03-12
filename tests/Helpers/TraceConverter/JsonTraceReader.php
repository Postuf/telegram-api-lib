<?php

declare(strict_types=1);

namespace Helpers\TraceConverter;

use Helpers\TraceConverter\Contracts\TraceInterface;
use Helpers\TraceConverter\Traces\Trace;
use Helpers\TraceConverter\Traces\TraceRecord;
use InvalidArgumentException;
use TelegramOSINT\Exception\TGException;
use TelegramOSINT\MTSerialization\OwnImplementation\OwnAnonymousMessage;

class JsonTraceReader
{
    /**
     * @param string $pathToJson
     *
     * @throws TGException
     *
     * @return TraceInterface
     */
    public function read(string $pathToJson): TraceInterface
    {
        if (!file_exists($pathToJson)) {
            throw new InvalidArgumentException("Trace file `$pathToJson` do not exists.");
        }

        $fileContentOrFalse = file_get_contents($pathToJson);
        if ($fileContentOrFalse === false) {
            throw new InvalidArgumentException("Trace file `$pathToJson` is not readable.");
        }

        $decodedFileContent = json_decode($fileContentOrFalse);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Trace file `$pathToJson` does not look like a valid JSON file.");
        }

        $traceTimestamp = $decodedFileContent->{Trace::JSON_FIELD_TIMESTAMP};

        $traceRecords = [];
        foreach ($decodedFileContent->{Trace::JSON_FIELD_RECORDS} as $traceRecord) {
            $type = $traceRecord->{TraceRecord::JSON_FIELD_TYPE};
            $timestamp = $traceRecord->{TraceRecord::JSON_FIELD_TIMESTAMP};
            $encodedMessage = $traceRecord->{TraceRecord::JSON_FIELD_MESSAGE};

            $message = OwnAnonymousMessage::jsonDeserialize($encodedMessage);

            $traceRecords[] = new TraceRecord($type, $message, $timestamp);
        }

        $trace = new Trace($traceTimestamp, $traceRecords);

        // sanity check: if we encode the trace we should get exactly the original JSON
        assert(json_encode($trace) === json_encode($decodedFileContent));

        return $trace;
    }
}
