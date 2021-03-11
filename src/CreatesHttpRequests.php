<?php


namespace WmJasonWard\Laravel\Testing;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class CreatesHttpRequests
 *
 * Used in conjunction with MakesHttpRequests to create an Http Request
 * object for isolated testing of things like middleware handler functions
 *
 * @package Tests
 * @author Jason Ward
 */
trait CreatesHttpRequests
{

  /*
   * Functions needed from Illuminate\Foundation\Testing\Concerns\MakesHttpRequests trait
   *
   */
  abstract function transformHeadersToServerVars(array $headers);
  abstract function prepareCookiesForRequest();
  abstract function extractFilesFromDataArray(&$data);
  abstract function prepareUrlForRequest($uri);

  /**
   *  Creates an HttpRequest object that can be passed directly
   *  into a middleware handler
   *
   *
   * @param  string  $method
   * @param  string  $uri
   * @param  array  $parameters
   * @param  array  $cookies
   * @param  array  $files
   * @param  array  $server
   * @param  string|null  $content
   * @return Request
   */
  public function createRequest($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
  {
    $files = array_merge($files, $this->extractFilesFromDataArray($parameters));

    $symfonyRequest = SymfonyRequest::create(
      $this->prepareUrlForRequest($uri), $method, $parameters,
      $cookies, $files, array_replace($this->serverVariables, $server), $content
    );

    return Request::createFromBase($symfonyRequest);
  }

  /**
   * Creates a GET request for given URI.
   *
   * @param  string  $uri
   * @param  array  $headers
   * @return Request
   */
  public function createGetRequest($uri, array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('GET', $uri, [], $cookies, [], $server);
  }

  /**
   * Creates a POST request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createPostRequest($uri, array $data = [], array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('POST', $uri, $data, $cookies, [], $server);
  }

  /**
   * Creates a DELETE request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createDeleteRequest($uri, array $data = [], array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('DELETE', $uri, $data, $cookies, [], $server);
  }

  /**
   * Creates a PATCH request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createPatchRequest($uri, array $data = [], array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('PATCH', $uri, $data, $cookies, [], $server);
  }

  /**
   * Creates a PUT request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createPutRequest($uri, array $data = [], array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('PUT', $uri, $data, $cookies, [], $server);
  }

  /**
   * Visit the given URI with an OPTIONS request.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createOptionsRequest($uri, array $data = [], array $headers = [])
  {
    $server = $this->transformHeadersToServerVars($headers);
    $cookies = $this->prepareCookiesForRequest();

    return $this->createRequest('OPTIONS', $uri, $data, $cookies, [], $server);
  }


  /**
   * Call the given URI with a JSON request.
   *
   * @param  string  $method
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonRequest($method, $uri, array $data = [], array $headers = [])
  {
    $files = $this->extractFilesFromDataArray($data);

    $content = json_encode($data);

    $headers = array_merge([
      'CONTENT_LENGTH' => mb_strlen($content, '8bit'),
      'CONTENT_TYPE' => 'application/json',
      'Accept' => 'application/json',
    ], $headers);

    return $this->createRequest(
      $method,
      $uri,
      [],
      $this->prepareCookiesForJsonRequest(),
      $files,
      $this->transformHeadersToServerVars($headers),
      $content
    );
  }

  /**
   * Creates a JSON GET request for the given URI
   *
   * @param  string  $uri
   * @param  array  $headers
   * @return Request
   */
  public function createJsonGetRequest($uri, array $headers = [])
  {
    return $this->createJsonRequest('GET', $uri, [], $headers);
  }

  /**
   * Creates a JSON POST request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonPostRequest($uri, array $data = [], array $headers = [])
  {
    return $this->createJsonRequest('POST', $uri, $data, $headers);
  }

  /**
   * Creates a JSON PUT request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonPutRequest($uri, array $data = [], array $headers = [])
  {
    return $this->createJsonRequest('PUT', $uri, $data, $headers);
  }

  /**
   * Creates a JSON PATCH request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonPatchRequest($uri, array $data = [], array $headers = [])
  {
    return $this->createJsonRequest('PATCH', $uri, $data, $headers);
  }

  /**
   * Creates a JSON DELETE request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonDeleteRequest($uri, array $data = [], array $headers = [])
  {
    return $this->createJsonRequest('DELETE', $uri, $data, $headers);
  }

  /**
   * Creates a JSON OPTIONS request for the given URI.
   *
   * @param  string  $uri
   * @param  array  $data
   * @param  array  $headers
   * @return Request
   */
  public function createJsonOptionsRequest($uri, array $data = [], array $headers = [])
  {
    return $this->createJsonRequest('OPTIONS', $uri, $data, $headers);
  }

}
