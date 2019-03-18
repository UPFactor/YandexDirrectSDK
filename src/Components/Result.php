<?php

namespace YandexDirectSDK\Components;

use Exception;
use YandexDirectSDK\Exceptions\RequestException;
use YandexDirectSDK\Interfaces\ModelCommon as ModelCommonInterface;
use YandexDirectSDK\Interfaces\Model as ModelInterface;
use YandexDirectSDK\Interfaces\ModelCollection as ModelCollectionInterface;
/**
 * Class Result
 *
 * @property-read string                                        $response
 * @property-read Data                                          $data
 * @property-read ModelInterface|ModelCollectionInterface       $resource
 * @property-read integer                                       $code
 * @property-read array                                         $header
 * @property-read Data                                          $errors
 * @property-read Data                                          $warnings
 *
 * @package YandexDirectSDK\Components
 */
class Result
{
    /**
     * Original API response.
     *
     * @var string
     */
    protected $response;

    /**
     * Response code.
     *
     * @var int
     */
    protected $code = 0;

    /**
     * Response header parameters.
     *
     * @var array
     */
    protected $header = [];

    /**
     * Result of the API response.
     *
     * @var Data
     */
    protected $data;

    /**
     * Result related resources.
     *
     * @var ModelInterface|ModelCollectionInterface
     */
    protected $resource;

    /**
     * Error stack.
     *
     * @var Data
     */
    protected $errors;

    /**
     * Warning stack.
     *
     * @var Data
     */
    protected $warnings;

    /**
     * Create Result instance.
     *
     * @param resource $resource
     * @throws RequestException
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw RequestException::unknown('cURL Error.');
        }

        if (!($response = curl_exec($resource))) {
            throw RequestException::unknown("cURL Error. " . curl_error($resource));
        }

        $this->response = $response;
        $this->data = new Data();
        $this->errors = new Data();
        $this->warnings = new Data();

        $responseHeadersSize = curl_getinfo($resource, CURLINFO_HEADER_SIZE);
        $this->setCode(curl_getinfo($resource, CURLINFO_RESPONSE_CODE));
        $this->setHeader(substr($response, 0, $responseHeadersSize));
        $this->setResult(substr($response, $responseHeadersSize));
        curl_close($resource);
    }

    /**
     * Dynamic call of object properties.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * Get the value of all properties.
     *
     * @return array
     */
    public function fetch()
    {
        return array(
            'code' => $this->code,
            'header' => $this->header,
            'data' => $this->data->all(),
            'errors' => $this->errors->all(),
            'warnings' => $this->warnings->all()
        );
    }

    /**
     * Returns the original response from the API server.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the API server response code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the API response header.
     *
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Returns data from an API response.
     *
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns errors from the response of the server API.
     *
     * @return Data
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns warnings from the response of the server API.
     *
     * @return Data
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Associates [$resource] with the current response of the API server.
     *
     * @param ModelCommonInterface|ModelInterface|ModelCollectionInterface $resource
     * @return $this
     */
    public function setResource(ModelCommonInterface $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Returns the resource associated with the current response
     * of the API server.
     *
     * @return ModelInterface|ModelCollectionInterface|null
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets the value [$this->code].
     *
     * @param string|integer $code
     */
    protected function setCode($code):void
    {
        $this->code = (integer) $code;
    }

    /**
     * Sets the value [$this->header].
     *
     * @param string $header
     */
    protected function setHeader($header):void
    {
        $this->header = [];

        foreach (explode("\n", $header) as $item){
            if (empty(($item = trim($item)))) {
                continue;
            }

            if (preg_match('/^HTTP\/\d.\d\s+\d{3}\s+[a-z\s]+$/i', $item)) {
                continue;
            }

            $item = explode(":", $item, 2);
            $this->header[trim($item[0])] = trim($item[1]);
        }
    }

    /**
     * Sets the value [$this->data].
     *
     * @param string $result
     * @throws RequestException
     */
    protected function setResult($result):void
    {
        switch ($this->code){
            case 201:
            case 202: return;
            case 500: throw RequestException::internalServerError();
            case 502: throw RequestException::requestTimeout();
            case 400:
                $error = json_decode($result, true);

                if (json_last_error() !== JSON_ERROR_NONE or !isset($error['error'])){
                    throw RequestException::invalidResponse('The response contains not valid JSON', $this->response);
                }

                if (array_key_exists('error', $error)){
                    $error = $this->parseError($error['error']);
                    throw RequestException::badRequest("{$error['message']}. {$error['detail']}", $this->response);
                } else {
                    throw RequestException::badRequest('Unknown error', $this->response);
                }

        }

        $contentType = explode(';', $this->header['Content-Type'], 2);
        $contentType = $contentType[0] ?? '';

        switch ($contentType){
            case 'application/json': $this->setJsonResult($result); return; break;
            case 'text/tab-separated-values': $this->setTsvResult($result); return; break;
        }

        throw RequestException::invalidResponse('Unknown format', $this->response);
    }

    /**
     * Sets the value [$this->data] by JSON data.
     *
     * @param string $result
     * @throws RequestException
     */
    protected function setJsonResult($result):void
    {
        $result = json_decode($result, true);
        $warnings = [];
        $errors = [];

        if (json_last_error() !== JSON_ERROR_NONE){
            throw RequestException::invalidResponse('The response contains not valid JSON', $this->response);
        }

        if (array_key_exists('result', $result)){
            $result = $result['result'];
        } elseif(array_key_exists('error', $result)) {
            $error = $this->parseError($result['error']);
            throw RequestException::badRequest("{$error['message']}. {$error['detail']}", $this->response);
        }

        foreach ($result as $resultItemKey => $resultItemValue){
            if (!is_array($resultItemValue)){
                continue;
            }

            if (strpos($resultItemKey,'Results') === false){
                continue;
            }

            foreach ($resultItemValue as $key => $value){

                if (!is_array($value)){
                    continue;
                }

                if (array_key_exists('Warnings', $value)){
                    foreach ($value['Warnings'] as $warning){
                        $warnings[$key][] = $this->parseError($warning);
                    }
                }

                if (array_key_exists('Errors', $value)){
                    foreach ($value['Errors'] as $error){
                        $errors[$key][] = $this->parseError($error);
                    }
                }
            }
        }

        $this->data->reset($result);
        $this->warnings->reset($warnings);
        $this->errors->reset($errors);
    }

    /**
     * Sets the value [$this->data] by TSV data.
     *
     * @param string $result
     * @throws RequestException
     */
    protected function setTsvResult($result):void
    {
        $result = explode("\n", $result);

        if (count($result) < 3){
            throw RequestException::invalidResponse('The response contains not valid TSV. Number of lines < 3.', $this->response);
        }

        $result = array_slice($result, 1, -2);
        $columnNames = explode("\t", array_shift($result));
        $data = $result;

        try{
            foreach ($data as $key => $row){
                $data[$key] = array_combine($columnNames, explode("\t", $row));
            }
        } catch (Exception $error){
            throw RequestException::invalidResponse('The response contains not valid TSV. Not constant number of columns.', $this->response);
        }

        $this->data->reset($data);
    }

    /**
     * Parsing errors.
     * Converts an array of error or warning information to the specified format.
     *
     * @param $sourceError
     * @return array
     */
    protected function parseError($sourceError)
    {
        $error = array(
            'code' => 0,
            'message' => 'Unknown error',
            'detail' => ''
        );

        if (empty($sourceError)) {
            return $error;
        };

        foreach ($sourceError as $k => $v){
            $k = str_replace(array('error_','request_'),'',$k);
            $k = strtolower($k);
            switch ($k){
                case 'code':
                    $error['code'] = (integer) $v;
                    break;
                case 'message':
                case 'string':
                    $error['message'] = (string) $v;
                    break;
                case 'details':
                case 'detail':
                    $error['detail'] = (string) $v;
                    break;
            }
        }

        return $error;
    }

}